<?php
class Admin extends Controller {
    private $adminModel;
    private $contentModel;
    private $mediaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminModel = $this->model('Admin_model');
        $this->contentModel = $this->model('Home_content_model');
        $this->mediaModel = $this->model('Media_model');
    }

    private function requireLogin() {
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/admin/login');
            exit;
        }
    }

    private function checkCsrf() {
        if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die('Invalid request (CSRF check failed). Go back and try again.');
        }
    }

    private function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function setFlash($message, $type = 'info') {
        $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
    }

    private function takeFlash() {
        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);
        return $flash;
    }

    // Keeps a value within the field's maxlength cap (counting characters, not
    // bytes) so a saved value can never overflow the card/heading it renders into,
    // even if someone bypasses the client-side maxlength attribute.
    private function clamp($value, array $fieldDef) {
        if (!empty($fieldDef['maxlength']) && mb_strlen($value) > $fieldDef['maxlength']) {
            return mb_substr($value, 0, $fieldDef['maxlength']);
        }
        return $value;
    }

    public function login() {
        if (!empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->adminModel->verify($username, $password);
            if ($user) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user->username;
                header('Location: ' . URLROOT . '/admin');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        }

        $this->view('admin/login', [
            'title' => 'Admin Login - Workiify',
            'error' => $error,
        ]);
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . URLROOT . '/admin/login');
        exit;
    }

    public function index() {
        $this->requireLogin();
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - Workiify',
            'navSections' => Home_content_model::navSections(),
        ]);
    }

    // Upload helper: returns new relative path (e.g. /images/uploads/xxx.jpg) or null
    private function handleUpload($inputName) {
        if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES[$inputName];
        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!isset($allowed[$ext])) {
            return null;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if ($mime !== $allowed[$ext]) {
            return null;
        }

        $destDir = APPROOT . '/../public/images/uploads';
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $filename = 'upload_' . bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename);
        $path = '/images/uploads/' . $filename;
        $this->mediaModel->add($path, $file['name']);
        return $path;
    }

    // One combined edit screen per nav section (text/images on top, repeatable
    // entries below), e.g. /admin/section/our-services
    public function section($key = '') {
        $this->requireLogin();
        $section = null;
        foreach (Home_content_model::navSections() as $s) {
            if ($s['key'] === $key) { $section = $s; break; }
        }
        if (!$section) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }

        $message = '';
        $fieldsData = null;

        if (!empty($section['fields'])) {
            $fieldGroups = Home_content_model::fieldGroups();
            $fieldsSchema = $fieldGroups[$section['fields']];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->checkCsrf();
                foreach ($fieldsSchema['fields'] as $f) {
                    if ($f['type'] === 'image') {
                        $uploaded = $this->handleUpload($f['key']);
                        if ($uploaded) {
                            $this->contentModel->setField($f['key'], $uploaded);
                        } elseif (isset($_POST[$f['key'] . '_existing'])) {
                            $this->contentModel->setField($f['key'], $_POST[$f['key'] . '_existing']);
                        }
                    } else {
                        $this->contentModel->setField($f['key'], $this->clamp(trim($_POST[$f['key']] ?? ''), $f));
                    }
                }
                $message = 'Saved successfully.';
            }

            $fieldsData = [
                'schema' => $fieldsSchema,
                'values' => $this->contentModel->getFields(array_column($fieldsSchema['fields'], 'key')),
            ];
        }

        $itemsData = null;
        if (!empty($section['items'])) {
            $itemSchemas = Home_content_model::itemSchemas();
            $itemsData = [
                'sectionKey' => $section['items'],
                'schema' => $itemSchemas[$section['items']],
                'items' => $this->contentModel->getItems($section['items']),
            ];
        }

        $this->view('admin/section', [
            'title' => $section['label'] . ' - Admin - Workiify',
            'navKey' => $key,
            'sectionLabel' => $section['label'],
            'fieldsData' => $fieldsData,
            'itemsData' => $itemsData,
            'message' => $message,
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
            'mediaAssets' => $this->mediaModel->getAll(),
        ]);
    }

    // Add or edit one item, e.g. /admin/item-edit/services/new or /admin/item-edit/services/5
    public function item_edit($section = '', $id = 'new') {
        $this->requireLogin();
        $schemas = Home_content_model::itemSchemas();
        if (!isset($schemas[$section])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $schema = $schemas[$section];
        $data = [];
        $isNew = ($id === 'new');

        $navKey = Home_content_model::navKeyForItems($section);

        if ($isNew && !empty($schema['maxItems']) && count($this->contentModel->getItems($section)) >= $schema['maxItems']) {
            $this->setFlash('Maximum of ' . $schema['maxItems'] . ' ' . strtolower($schema['label']) . ' reached — remove one before adding another, or the layout will no longer line up.', 'error');
            header('Location: ' . URLROOT . '/admin/section/' . $navKey);
            exit;
        }

        if (!$isNew) {
            $existing = $this->contentModel->getItem((int)$id);
            if (!$existing || $existing['section_key'] !== $section) {
                header('Location: ' . URLROOT . '/admin/section/' . $navKey);
                exit;
            }
            $data = $existing['data'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();

            if ($isNew && !empty($schema['maxItems'])) {
                $currentCount = count($this->contentModel->getItems($section));
                if ($currentCount >= $schema['maxItems']) {
                    $this->setFlash('Maximum of ' . $schema['maxItems'] . ' ' . strtolower($schema['label']) . ' reached — remove one before adding another, or the layout will no longer line up.', 'error');
                    header('Location: ' . URLROOT . '/admin/section/' . $navKey);
                    exit;
                }
            }

            $newData = [];
            foreach ($schema['fields'] as $f) {
                if ($f['type'] === 'image') {
                    $uploaded = $this->handleUpload($f['key']);
                    $newData[$f['key']] = $uploaded ?: ($_POST[$f['key'] . '_existing'] ?? '');
                } else {
                    $newData[$f['key']] = $this->clamp(trim($_POST[$f['key']] ?? ''), $f);
                }
            }
            if ($isNew) {
                $this->contentModel->addItem($section, $newData);
            } else {
                $this->contentModel->updateItem((int)$id, $newData);
            }
            header('Location: ' . URLROOT . '/admin/section/' . $navKey);
            exit;
        }

        $this->view('admin/item_edit', [
            'title' => ($isNew ? 'Add' : 'Edit') . ' ' . $schema['label'] . ' - Admin - Workiify',
            'section' => $section,
            'navKey' => $navKey,
            'schema' => $schema,
            'id' => $id,
            'isNew' => $isNew,
            'data' => $data,
            'csrfToken' => $this->csrfToken(),
            'mediaAssets' => $this->mediaModel->getAll(),
        ]);
    }

    // Central media library: upload once here, reuse the same asset across any field.
    public function media() {
        $this->requireLogin();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $uploaded = $this->handleUpload('new_asset');
            $message = $uploaded ? 'Image uploaded to the library.' : 'Please choose a valid image file (jpg, png, webp, gif or svg).';
        }

        $this->view('admin/media', [
            'title' => 'Media Library - Admin - Workiify',
            'message' => $message,
            'assets' => $this->mediaModel->getAll(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function media_delete($id = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $asset = $this->mediaModel->getById((int)$id);
            if ($asset) {
                $full = APPROOT . '/../public' . $asset['path'];
                if (strpos($asset['path'], '/images/uploads/') === 0 && is_file($full)) {
                    unlink($full);
                }
                $this->mediaModel->delete((int)$id);
            }
        }
        header('Location: ' . URLROOT . '/admin/media');
        exit;
    }

    // Delete one item (POST only), e.g. posts to /admin/item-delete/services/5
    public function item_delete($section = '', $id = '') {
        $this->requireLogin();
        $schemas = Home_content_model::itemSchemas();
        $schema = $schemas[$section] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $existing = $this->contentModel->getItem((int)$id);
            if ($existing && $existing['section_key'] === $section) {
                $currentCount = count($this->contentModel->getItems($section));
                if ($schema && !empty($schema['minItems']) && $currentCount <= $schema['minItems']) {
                    $this->setFlash('At least ' . $schema['minItems'] . ' ' . strtolower($schema['label']) . ' are required to keep this layout aligned — edit this entry instead of deleting it.', 'error');
                } else {
                    $this->contentModel->deleteItem((int)$id);
                }
            }
        }
        header('Location: ' . URLROOT . '/admin/section/' . Home_content_model::navKeyForItems($section));
        exit;
    }
}
