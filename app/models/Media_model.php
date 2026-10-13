<?php
class Media_model {
    private $db;
    private static $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

    public function __construct() {
        $this->db = new Database();
    }

    // Scans public/images (top level only) and registers any file not yet in the library,
    // so pre-existing site images show up alongside admin-uploaded ones automatically.
    public function syncFromDisk() {
        $dir = APPROOT . '/../public/images';
        if (!is_dir($dir)) { return; }

        $this->db->query('SELECT path FROM media_assets');
        $existing = array_flip(array_map(function ($r) { return $r->path; }, $this->db->resultSet()));

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') { continue; }
            $full = $dir . '/' . $file;
            if (!is_file($full)) { continue; }
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, self::$allowedExt, true)) { continue; }

            $path = '/images/' . $file;
            if (!isset($existing[$path])) {
                $this->add($path, $file);
            }
        }
    }

    public function getAll() {
        $this->syncFromDisk();
        $this->db->query('SELECT * FROM media_assets ORDER BY uploaded_at DESC, id DESC');
        $rows = $this->db->resultSet();
        $out = [];
        foreach ($rows as $r) {
            $out[] = ['id' => $r->id, 'path' => $r->path, 'original_name' => $r->original_name];
        }
        return $out;
    }

    public function add($path, $originalName = '') {
        $this->db->query('INSERT IGNORE INTO media_assets (path, original_name) VALUES (:p, :n)');
        $this->db->bind(':p', $path);
        $this->db->bind(':n', $originalName);
        return $this->db->execute();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM media_assets WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row ? ['id' => $row->id, 'path' => $row->path, 'original_name' => $row->original_name, 'uploaded_at' => $row->uploaded_at] : null;
    }

    // Every asset with what the admin table shows: kind, file size, pixel size and where it is used.
    public function getAllDetailed() {
        $this->syncFromDisk();
        $this->db->query('SELECT * FROM media_assets ORDER BY uploaded_at DESC, id DESC');
        $out = [];
        foreach ($this->db->resultSet() as $r) {
            $out[] = $this->details(['id' => $r->id, 'path' => $r->path, 'original_name' => $r->original_name, 'uploaded_at' => $r->uploaded_at]);
        }
        return $out;
    }

    public function details(array $a) {
        $full = APPROOT . '/../public' . $a['path'];
        $a['name'] = $a['original_name'] !== null && $a['original_name'] !== '' ? $a['original_name'] : basename($a['path']);
        $a['isUpload'] = self::isUpload($a['path']);
        $a['exists'] = is_file($full);
        $a['bytes'] = $a['exists'] ? filesize($full) : 0;
        $dim = $a['exists'] ? @getimagesize($full) : false;
        $a['width'] = $dim ? $dim[0] : null;
        $a['height'] = $dim ? $dim[1] : null;
        $a['ext'] = strtoupper(pathinfo($a['path'], PATHINFO_EXTENSION));
        $a['usage'] = $this->usage($a['path']);
        return $a;
    }

    public function rename($id, $name) {
        $this->db->query('UPDATE media_assets SET original_name = :n WHERE id = :id');
        $this->db->bind(':n', $name);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // Points this asset at a new file and swaps every reference to the old file for the new one
    // (page fields, repeatable entries -- stored as JSON, so "/" appears escaped -- and service
    // cards), all in one transaction so the site never shows a half-updated state.
    public function replacePath($id, $oldPath, $newPath) {
        $this->db->beginTransaction();
        try {
            $this->db->query('UPDATE home_fields SET field_value = :new WHERE field_value = :old');
            $this->db->bind(':new', $newPath);
            $this->db->bind(':old', $oldPath);
            $this->db->execute();

            $oldJson = substr(json_encode($oldPath), 1, -1);
            $newJson = substr(json_encode($newPath), 1, -1);
            $this->db->query('UPDATE home_items SET data = REPLACE(REPLACE(data, :oj, :nj), :o, :n) WHERE INSTR(data, :oj2) > 0 OR INSTR(data, :o2) > 0');
            $this->db->bind(':oj', '"' . $oldJson . '"');
            $this->db->bind(':nj', '"' . $newJson . '"');
            $this->db->bind(':o', '"' . $oldPath . '"');
            $this->db->bind(':n', '"' . $newPath . '"');
            $this->db->bind(':oj2', '"' . $oldJson . '"');
            $this->db->bind(':o2', '"' . $oldPath . '"');
            $this->db->execute();

            $this->db->query('UPDATE services SET card_image = :new WHERE card_image = :old');
            $this->db->bind(':new', $newPath);
            $this->db->bind(':old', $oldPath);
            $this->db->execute();

            // the freshly uploaded file was registered as its own row by the upload; fold it into this one
            $this->db->query('DELETE FROM media_assets WHERE path = :new AND id <> :id');
            $this->db->bind(':new', $newPath);
            $this->db->bind(':id', (int)$id);
            $this->db->execute();
            $this->db->query('UPDATE media_assets SET path = :new, uploaded_at = CURRENT_TIMESTAMP WHERE id = :id');
            $this->db->bind(':new', $newPath);
            $this->db->bind(':id', (int)$id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            error_log('Media replace failed: ' . $e->getMessage());
            return false;
        }
    }

    // Only admin uploads can be deleted: built-in images under /images are re-registered by
    // syncFromDisk() on every load, so "removing" one would silently undo itself.
    public static function isUpload($path) {
        return strpos((string)$path, '/images/uploads/') === 0;
    }

    // Where an image is still used on the site: a list of "Page / field" labels (empty when
    // it is safe to delete). Item data is stored as JSON, which escapes "/" as "\/".
    public function usage($path) {
        $where = [];
        $this->db->query('SELECT page, field_key FROM home_fields WHERE field_value = :p');
        $this->db->bind(':p', $path);
        foreach ($this->db->resultSet() as $r) { $where[] = Home_content_model::pageLabel($r->page) . ' / ' . $r->field_key; }

        $this->db->query('SELECT page, section_key FROM home_items WHERE INSTR(data, :p1) > 0 OR INSTR(data, :p2) > 0');
        // match whole JSON string values only, so "/a.png" never matches "/a.png.bak"
        $this->db->bind(':p1', json_encode($path));
        $this->db->bind(':p2', '"' . $path . '"');
        foreach ($this->db->resultSet() as $r) { $where[] = Home_content_model::pageLabel($r->page) . ' / ' . $r->section_key; }

        $this->db->query('SELECT title FROM services WHERE card_image = :p');
        $this->db->bind(':p', $path);
        foreach ($this->db->resultSet() as $r) { $where[] = 'Service card / ' . $r->title; }

        return array_values(array_unique($where));
    }

    public function delete($id) {
        $this->db->query('DELETE FROM media_assets WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
