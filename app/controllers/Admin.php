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
        $this->testimonialModel = $this->model('Testimonial');
        $this->enquiryModel = $this->model('Enquiry');
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

    // Picks a human-readable label for an item revision (same "first non-image
    // field" convention the section list view uses to title each row).
    private function itemLabel(array $schema, array $data) {
        $imageKey = null;
        foreach ($schema['fields'] as $f) {
            if ($f['type'] === 'image') { $imageKey = $f['key']; break; }
        }
        foreach ($schema['fields'] as $f) {
            if ($f['key'] !== $imageKey && !empty($data[$f['key']])) {
                return mb_substr((string)$data[$f['key']], 0, 60);
            }
        }
        return $schema['label'];
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
        $pages = [];
        foreach (Home_content_model::pages() as $pageKey => $pageLabel) {
            $pages[] = [
                'key' => $pageKey,
                'label' => $pageLabel,
                'sections' => Home_content_model::navSections($pageKey),
            ];
        }
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - Workiify',
            'pages' => $pages,
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
    // entries below), e.g. /admin/section/home/our-services
    public function section($page = '', $key = '') {
        $this->requireLogin();
        if (!isset(Home_content_model::pages()[$page])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $this->contentModel->setPage($page);

        $section = null;
        foreach (Home_content_model::navSections($page) as $s) {
            if ($s['key'] === $key) { $section = $s; break; }
        }
        if (!$section) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }

        $message = '';
        $fieldsData = null;

        if (!empty($section['fields'])) {
            $fieldGroups = Home_content_model::fieldGroups($page);
            $fieldsSchema = $fieldGroups[$section['fields']];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->checkCsrf();

                $fieldKeys = array_column($fieldsSchema['fields'], 'key');
                $before = $this->contentModel->getFields($fieldKeys);
                $this->contentModel->saveRevision('fields', $section['key'], $section['label'], $before);

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
            $itemSchemas = Home_content_model::itemSchemas($page);
            $itemsData = [
                'sectionKey' => $section['items'],
                'schema' => $itemSchemas[$section['items']],
                'items' => $this->contentModel->getItems($section['items']),
            ];
        }

        $this->view('admin/section', [
            'title' => $section['label'] . ' - Admin - Workiify',
            'page' => $page,
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

    // Maps a CMS page to its real front-end view + the item collections that
    // view needs, so the preview can render the exact same template real
    // visitors see (header/footer included) instead of a hand-built facsimile.
    private function previewMap($page) {
        $maps = [
            'home' => [
                'view' => 'pages/home',
                'title' => 'Home - Workiify',
                'items' => [
                    'hero' => 'hero', 'counters' => 'counters', 'services' => 'services',
                    'amenities' => 'amenities', 'whyChoosePoints' => 'why_choose_points',
                    'audience' => 'audience', 'testimonials' => 'testimonials', 'gallery' => 'gallery',
                ],
            ],
            'about' => [
                'view' => 'pages/about_us',
                'title' => 'About Us - Workiify',
                'items' => [
                    'workspaceTiles' => 'workspace_tiles', 'whyChooseCards' => 'why_choose_cards',
                    'whyChooseIcons' => 'why_choose_icons', 'communityPhotos' => 'community_photos',
                    'testimonials' => 'about_testimonials_items', 'clientLogos' => 'client_logos',
                ],
            ],
            'contact' => [
                'view' => 'pages/contact_us',
                'title' => 'Contact Us - Workiify',
                'items' => [],
                'extra' => ['hideTourBand' => true],
            ],
            'gallery' => [
                'view' => 'pages/gallery',
                'title' => 'Gallery - Workiify',
                'items' => ['photos' => 'gallery_photos'],
            ],
        ];
        return $maps[$page] ?? null;
    }

    // Renders the real page template with the pending (unsaved) field edits
    // overlaid on top of what's currently saved, so the admin can see the
    // effect of their changes before committing them. POSTed from the section
    // edit screen's live-preview panel; never writes anything.
    public function preview($page = '') {
        $this->requireLogin();
        if (!isset(Home_content_model::pages()[$page])) {
            http_response_code(404);
            exit;
        }
        $map = $this->previewMap($page);
        if (!$map) {
            http_response_code(404);
            exit;
        }
        $this->checkCsrf();
        $this->contentModel->setPage($page);

        $allFieldKeys = [];
        foreach (Home_content_model::fieldGroups($page) as $group) {
            foreach ($group['fields'] as $f) {
                $allFieldKeys[] = $f['key'];
            }
        }
        $fields = $this->contentModel->getFields($allFieldKeys);

        $overrides = [];
        if (!empty($_POST['fields_json'])) {
            $decoded = json_decode($_POST['fields_json'], true);
            if (is_array($decoded)) {
                $overrides = $decoded;
            }
        }
        foreach ($overrides as $k => $v) {
            if (array_key_exists($k, $fields)) {
                $fields[$k] = is_string($v) ? $v : '';
            }
        }

        $data = array_merge([
            'title' => $map['title'],
            'f' => $fields,
        ], $map['extra'] ?? []);

        foreach ($map['items'] as $viewKey => $schemaKey) {
            $data[$viewKey] = $this->contentModel->getItems($schemaKey);
        }

        $this->view($map['view'], $data);
    }

    // Add or edit one item, e.g. /admin/item-edit/home/services/new or /admin/item-edit/home/services/5
    public function item_edit($page = '', $section = '', $id = 'new') {
        $this->requireLogin();
        if (!isset(Home_content_model::pages()[$page])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $this->contentModel->setPage($page);

        $schemas = Home_content_model::itemSchemas($page);
        if (!isset($schemas[$section])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $schema = $schemas[$section];
        $data = [];
        $isNew = ($id === 'new');

        $navKey = Home_content_model::navKeyForItems($section, $page);

        if ($isNew && !empty($schema['maxItems']) && count($this->contentModel->getItems($section)) >= $schema['maxItems']) {
            $this->setFlash('Maximum of ' . $schema['maxItems'] . ' ' . strtolower($schema['label']) . ' reached — remove one before adding another, or the layout will no longer line up.', 'error');
            header('Location: ' . URLROOT . '/admin/section/' . $page . '/' . $navKey);
            exit;
        }

        if (!$isNew) {
            $existing = $this->contentModel->getItem((int)$id);
            if (!$existing || $existing['section_key'] !== $section || $existing['page'] !== $page) {
                header('Location: ' . URLROOT . '/admin/section/' . $page . '/' . $navKey);
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
                    header('Location: ' . URLROOT . '/admin/section/' . $page . '/' . $navKey);
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
                $this->contentModel->saveRevision('item', $section, $this->itemLabel($schema, $data), $data, (int)$id);
                $this->contentModel->updateItem((int)$id, $newData);
            }
            header('Location: ' . URLROOT . '/admin/section/' . $page . '/' . $navKey);
            exit;
        }

        $this->view('admin/item_edit', [
            'title' => ($isNew ? 'Add' : 'Edit') . ' ' . $schema['label'] . ' - Admin - Workiify',
            'page' => $page,
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

    // Delete one item (POST only), e.g. posts to /admin/item-delete/home/services/5
    public function item_delete($page = '', $section = '', $id = '') {
        $this->requireLogin();
        if (!isset(Home_content_model::pages()[$page])) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $this->contentModel->setPage($page);

        $schemas = Home_content_model::itemSchemas($page);
        $schema = $schemas[$section] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $existing = $this->contentModel->getItem((int)$id);
            if ($existing && $existing['section_key'] === $section && $existing['page'] === $page) {
                $currentCount = count($this->contentModel->getItems($section));
                if ($schema && !empty($schema['minItems']) && $currentCount <= $schema['minItems']) {
                    $this->setFlash('At least ' . $schema['minItems'] . ' ' . strtolower($schema['label']) . ' are required to keep this layout aligned — edit this entry instead of deleting it.', 'error');
                } else {
                    $label = $schema ? $this->itemLabel($schema, $existing['data']) : ('Item #' . $id);
                    $this->contentModel->saveRevision('item_deleted', $section, $label, $existing['data'], (int)$id);
                    $this->contentModel->deleteItem((int)$id);
                }
            }
        }
        header('Location: ' . URLROOT . '/admin/section/' . $page . '/' . Home_content_model::navKeyForItems($section, $page));
        exit;
    }

    // One combined undo screen across every page: every field-section save and
    // every item save/delete leaves a revision behind here, newest first.
    public function history() {
        $this->requireLogin();
        $pageLabels = Home_content_model::pages();
        $revisions = Home_content_model::getRecentRevisions(50);

        foreach ($revisions as &$rev) {
            $rev['sectionLabel'] = $rev['section_key'];
            foreach (Home_content_model::navSections($rev['page']) as $s) {
                // 'fields' revisions store the nav section's own key; 'item' /
                // 'item_deleted' revisions store the item schema key (navSections'
                // 'items' value) -- check both so either kind resolves to its label.
                if ($s['key'] === $rev['section_key'] || ($s['items'] ?? null) === $rev['section_key']) {
                    $rev['sectionLabel'] = $s['label'];
                    break;
                }
            }
        }
        unset($rev);

        $this->view('admin/history', [
            'title' => 'History - Admin - Workiify',
            'revisions' => $revisions,
            'pageLabels' => $pageLabels,
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Reverts one revision (POST only). Restoring itself leaves a fresh
    // revision behind first, so undo can be undone too.
    public function restore_revision($id = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $revision = Home_content_model::getRevision((int)$id);
            if ($revision) {
                $this->contentModel->setPage($revision['page']);
                $this->contentModel->restoreRevision($revision);
                $this->setFlash('Restored "' . $revision['label'] . '".', 'success');
            }
        }
        header('Location: ' . URLROOT . '/admin/history');
        exit;
    }

    // ---- Feedback Module ----------------------------------------------
    public function feedback() {
        $this->requireLogin();
        
        $testimonials = $this->testimonialModel->getAllTestimonials();

        $this->view('admin/feedback', [
            'title' => 'Feedback - Workiify Admin',
            'testimonials' => $testimonials,
            'flash' => $this->takeFlash(),
        ]);
    }

    public function feedback_edit($id) {
        $this->requireLogin();
        
        $testimonial = $this->testimonialModel->getTestimonialById($id);
        
        if (!$testimonial) {
            $this->setFlash('Feedback not found.', 'error');
            header('Location: ' . URLROOT . '/admin/feedback');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            
            $data = [
                'id' => $id,
                'full_name' => trim($_POST['full_name'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'designation' => trim($_POST['designation'] ?? ''),
                'rating' => (int)($_POST['rating'] ?? $testimonial->rating),
                'feedback' => trim($_POST['feedback'] ?? ''),
                'status' => $_POST['status'] ?? 'Pending',
                'show_on_home' => isset($_POST['show_on_home']) ? 1 : 0
            ];

            if ($this->testimonialModel->updateTestimonial($data)) {
                $this->setFlash('Feedback updated successfully.', 'success');
                header('Location: ' . URLROOT . '/admin/feedback');
                exit;
            } else {
                $this->setFlash('Something went wrong.', 'error');
            }
        }

        $this->view('admin/feedback_edit', [
            'title' => 'Edit Feedback - Workiify Admin',
            'testimonial' => $testimonial,
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    // ---- Enquiries Module --------------------------------------------
    public function enquiries() {
        $this->requireLogin();

        $this->view('admin/enquiries', [
            'title' => 'Enquiries - Workiify Admin',
            'enquiries' => $this->enquiryModel->getAllEnquiries(),
            'flash' => $this->takeFlash(),
        ]);
    }

    public function enquiry_view($id) {
        $this->requireLogin();

        $enquiry = $this->enquiryModel->getEnquiryById($id);

        if (!$enquiry) {
            $this->setFlash('Enquiry not found.', 'error');
            header('Location: ' . URLROOT . '/admin/enquiries');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();

            $status = $_POST['status'] ?? 'New';
            if (!in_array($status, ['New', 'Contacted', 'Closed'], true)) $status = 'New';

            if ($this->enquiryModel->updateStatus($id, $status)) {
                $this->setFlash('Enquiry updated successfully.', 'success');
            } else {
                $this->setFlash('Something went wrong.', 'error');
            }
            header('Location: ' . URLROOT . '/admin/enquiry_view/' . $id);
            exit;
        }

        $this->view('admin/enquiry_view', [
            'title' => 'View Enquiry - Workiify Admin',
            'enquiry' => $enquiry,
            'csrf_token' => $this->csrfToken(),
        ]);
    }
}
