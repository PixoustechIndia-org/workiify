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
        if (!is_dir($dir)) return;

        $this->db->query('SELECT path FROM media_assets');
        $existing = array_flip(array_map(function ($r) { return $r->path; }, $this->db->resultSet()));

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') continue;
            $full = $dir . '/' . $file;
            if (!is_file($full)) continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, self::$allowedExt, true)) continue;

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
        return $row ? ['id' => $row->id, 'path' => $row->path, 'original_name' => $row->original_name] : null;
    }

    public function delete($id) {
        $this->db->query('DELETE FROM media_assets WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
