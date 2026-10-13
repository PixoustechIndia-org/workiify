<?php
class Admin extends Controller {
    // Repeated values, defined once
    private const URL_ADMIN = '/admin';
    private const URL_LOGIN = '/admin/login';
    private const URL_SECTION = '/admin/section/';
    private const URL_SERVICES = '/admin/services';
    private const URL_MEDIA = '/admin/media';
    private const URL_FEEDBACK = '/admin/feedback';
    private const URL_ENQUIRY_VIEW = '/admin/enquiry-view/';
    private const PUBLIC_DIR = '/../public';
    private const MIME_JPEG = 'image/jpeg';
    private const MSG_DB_ERROR = 'Could not save (a database error occurred). Nothing was changed.';
    private const MSG_BAD_STATUS = 'Choose a valid status.';
    private const LOG_ENQUIRY = 'Enquiry from ';
    private const LOG_FEEDBACK = 'Feedback from ';

    private $adminModel;
    private $contentModel;
    private $mediaModel;
    private $serviceModel;
    private $testimonialModel;
    private $enquiryModel;

    // Rows per page on the admin list screens.
    private const PER_PAGE = 15;
    // Largest image accepted by any admin upload.
    private const MAX_UPLOAD_BYTES = 5 * 1024 * 1024;
    // Failed logins allowed before the form locks for LOGIN_LOCK_SECONDS.
    private const LOGIN_MAX_ATTEMPTS = 5;
    private const LOGIN_LOCK_SECONDS = 300;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminModel = $this->model('Admin_model');
        $this->contentModel = $this->model('Home_content_model');
        $this->mediaModel = $this->model('Media_model');
        $this->testimonialModel = $this->model('Testimonial');
        $this->enquiryModel = $this->model('Enquiry');
        $this->serviceModel = $this->model('Service_model');
    }

    private function requireLogin() {
        if (empty($_SESSION['admin_logged_in'])) {
            $this->redirect(self::URL_LOGIN);
        }
    }

    private function redirect($path) {
        header('Location: ' . URLROOT . $path);
        exit;
    }

    // A stale or missing token (e.g. the form sat open past a logout) sends the admin back with
    // a message instead of a blank "CSRF failed" page.
    private function checkCsrf() {
        if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token'])) {
            $this->setFlash('Your session expired before that form was sent, so nothing was saved. Please try again.', 'error');
            $this->redirect(empty($_SESSION['admin_logged_in']) ? self::URL_LOGIN : self::URL_ADMIN);
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

    // ---- List-screen helpers -------------------------------------------

    private function queryParam($key, array $allowed = null) {
        $v = trim((string)($_GET[$key] ?? ''));
        if ($allowed !== null && !in_array($v, $allowed, true)) { return ''; }
        return mb_substr($v, 0, 100);
    }

    private function dateParam($key) {
        $v = $this->queryParam($key);
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) && strtotime($v) ? $v : '';
    }

    private function pagination($total) {
        $pages = max(1, (int)ceil($total / self::PER_PAGE));
        $page = min($pages, max(1, (int)($_GET['page'] ?? 1)));
        return ['page' => $page, 'pages' => $pages, 'total' => $total, 'perPage' => self::PER_PAGE];
    }

    // Where to go back to after an action on a list row: the same filtered/paged list.
    private function listReturn($base) {
        $r = (string)($_POST['return'] ?? '');
        if ($r !== '' && strpos($r, $base) === 0 && !preg_match('/[\r\n]/', $r)) { return $r; }
        return $base;
    }

    // ---- Auth ----------------------------------------------------------

    public function login() {
        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect(self::URL_ADMIN);
        }

        $error = '';
        $username = '';
        $lockedFor = max(0, (int)($_SESSION['login_locked_until'] ?? 0) - time());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string)($_POST['username'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token'])) {
                $error = 'The login form expired. Please try again.';
            } elseif ($lockedFor > 0) {
                $error = 'Too many failed attempts. Please wait ' . ceil($lockedFor / 60) . ' minute(s) and try again.';
            } elseif ($username === '' || $password === '') {
                $error = 'Please enter both your username and password.';
            } else {
                $user = $this->adminModel->verify($username, $password);
                if ($user) {
                    session_regenerate_id(true);
                    unset($_SESSION['login_failures'], $_SESSION['login_locked_until']);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $user->username;
                    $this->redirect(self::URL_ADMIN);
                }
                $_SESSION['login_failures'] = (int)($_SESSION['login_failures'] ?? 0) + 1;
                if ($_SESSION['login_failures'] >= self::LOGIN_MAX_ATTEMPTS) {
                    $_SESSION['login_failures'] = 0;
                    $_SESSION['login_locked_until'] = time() + self::LOGIN_LOCK_SECONDS;
                    $error = 'Too many failed attempts. Please wait ' . (self::LOGIN_LOCK_SECONDS / 60) . ' minutes and try again.';
                } else {
                    $error = 'Invalid username or password.';
                }
            }
        }

        $this->view('admin/login', [
            'title' => 'Admin Login - Workiify',
            'error' => $error,
            'username' => $username,
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        $this->redirect(self::URL_LOGIN);
    }

    // ---- Dashboard -----------------------------------------------------

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
        $svcCounts = ['published' => 0, 'draft' => 0];
        foreach ($this->serviceModel->adminList(false) as $s) {
            $svcCounts[$s['status'] === 'published' ? 'published' : 'draft']++;
        }
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - Workiify',
            'pages' => $pages,
            'enquiryCounts' => $this->enquiryModel->statusCounts(),
            'feedbackCounts' => $this->testimonialModel->statusCounts(),
            'serviceCounts' => $svcCounts,
            'recentEnquiries' => $this->enquiryModel->getRecent(5),
            'flash' => $this->takeFlash(),
        ]);
    }

    // ---- Uploads -------------------------------------------------------

    // Low-level upload: returns the new relative path (e.g. /images/uploads/xxx.jpg) or null.
    // Only real JPG/PNG/WebP/GIF files are accepted (extension and content must agree).
    private function handleUpload($inputName) {
        if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES[$inputName];
        $allowed = ['jpg' => self::MIME_JPEG, 'jpeg' => self::MIME_JPEG, 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
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
        if (!move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
            return null;
        }
        $path = '/images/uploads/' . $filename;
        $this->mediaModel->add($path, $file['name']);
        return $path;
    }

    // Handles one image input for any form. Returns the new library path, or null when nothing
    // was uploaded; a rejected upload adds a message to $errors under the field's key.
    private function uploadImage($key, $label, array &$errors) {
        $file = $_FILES[$key] ?? null;
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[$key] = $label . ': the upload failed (the file may be larger than the server allows).';
            return null;
        }
        if ($file['size'] > self::MAX_UPLOAD_BYTES) {
            $errors[$key] = $label . ': the image is larger than 5 MB.';
            return null;
        }
        $path = $this->handleUpload($key);
        if (!$path) {
            $errors[$key] = $label . ': please upload a JPG, PNG, WebP or GIF image.';
        }
        return $path;
    }

    // Reads one CMS form field out of $_POST according to its type. Returns the value to store,
    // or null when the field should be left as it is (e.g. an image input with nothing chosen).
    private function fieldValueFromPost(array $f, array &$errors) {
        switch ($f['type']) {
            case 'heading':
                return null;
            case 'image':
                $uploaded = $this->uploadImage($f['key'], $f['label'], $errors);
                if ($uploaded) { return $uploaded; }
                $existing = isset($_POST[$f['key'] . '_existing']) ? (string)$_POST[$f['key'] . '_existing'] : null;
                // Only library paths may be stored, never an arbitrary URL typed into the hidden input.
                if ($existing !== null && $existing !== '' && strpos($existing, '/images/') !== 0) { return null; }
                return $existing;
            case 'toggle':
                return isset($_POST[$f['key']]) ? '1' : '0';
            case 'select':
                $v = (string)($_POST[$f['key']] ?? '');
                return array_key_exists($v, $f['options']) ? $v : null;
            default:
                $value = $this->clamp(trim((string)($_POST[$f['key']] ?? '')), $f);
                $err = Home_content_model::checkRule(Home_content_model::fieldRule($f), $value);
                if ($err !== null) { $errors[$f['key']] = $f['label'] . ': ' . $err; }
                return $value;
        }
    }

    // The values the Service Settings screen shows, taken from the service's own row.
    private function serviceSettingsValues(array $svc, array $fields) {
        $out = [];
        foreach ($fields as $f) {
            $k = $f['key'];
            if ($f['type'] === 'heading') { continue; }
            if ($k === 'card_features') { $out[$k] = implode("\n", $svc['card_features']); continue; }
            if ($f['type'] === 'toggle') { $out[$k] = !empty($svc[$k]) ? '1' : '0'; continue; }
            $out[$k] = (string)($svc[$k] ?? '');
        }
        return $out;
    }

    // ---- Page content (CMS) --------------------------------------------

    // One combined edit screen per nav section (text/images on top, repeatable
    // entries below), e.g. /admin/section/home/our-services or /admin/section/svc3/faq
    public function section($page = '', $key = '') {
        $this->requireLogin();
        if (!Home_content_model::pageExists($page)) {
            $this->redirect(self::URL_ADMIN);
        }
        $this->contentModel->setPage($page);

        $section = null;
        foreach (Home_content_model::navSections($page) as $s) {
            if ($s['key'] === $key) { $section = $s; break; }
        }
        if (!$section) {
            $this->redirect(self::URL_ADMIN);
        }

        $svc = Home_content_model::isServicePage($page) ? $this->serviceModel->get(Home_content_model::servicePageId($page)) : null;
        $errors = [];
        $fieldsData = null;

        if (!empty($section['fields'])) {
            $fieldGroups = Home_content_model::fieldGroups($page);
            $fieldsSchema = $fieldGroups[$section['fields']];
            $inputFields = array_values(array_filter($fieldsSchema['fields'], function ($f) { return $f['type'] !== 'heading'; }));
            $fieldKeys = array_column($inputFields, 'key');
            $isSettings = !empty($section['settings']) && $svc;
            $values = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->checkCsrf();
                $redirectTo = !empty($_POST['redirect_to_section']) ? (string)$_POST['redirect_to_section'] : null;
                $targetSec = $key;
                if ($redirectTo !== null) {
                    foreach (Home_content_model::navSections($page) as $s) {
                        if ($s['key'] === $redirectTo) { $targetSec = $redirectTo; break; }
                    }
                }

                if ($isSettings) {
                    // Service Settings live on the service's own record (so the menu, the Services page and
                    // the URL router can find them), not in the page-content tables.
                    $in = $_POST;
                    $in['card_image'] = $this->uploadImage('card_image', 'Card photo', $errors) ?: (string)($_POST['card_image_existing'] ?? '');
                    [$clean, $validationErrors] = $this->serviceModel->validateSettings($in, $svc, $this->contentModel->getFields(['banner_heading']));
                    $errors = array_merge($errors, $validationErrors);
                    if (empty($errors)) {
                        try {
                            $this->serviceModel->saveSettings($svc['id'], $clean);
                            $this->setFlash('Settings saved' . ($clean['status'] === 'published' ? ' and live on the site.' : ' (this service is still a draft).'), 'success');
                            $this->redirect(self::URL_SECTION . $page . '/' . $targetSec);
                        } catch (Throwable $e) {
                            error_log('Service settings save failed: ' . $e->getMessage());
                            $errors[] = self::MSG_DB_ERROR;
                        }
                    }
                    // show what was typed, so nothing is lost
                    $values = [];
                    foreach ($inputFields as $f) {
                        if ($f['type'] === 'toggle') {
                            $values[$f['key']] = isset($_POST[$f['key']]) ? '1' : '0';
                        } elseif ($f['key'] === 'card_image') {
                            $values[$f['key']] = (string)$in['card_image'];
                        } else {
                            $values[$f['key']] = (string)($_POST[$f['key']] ?? '');
                        }
                    }
                } else {
                    // Validate everything first; nothing is written unless the whole section is valid.
                    $before = $this->contentModel->getFields($fieldKeys);
                    $submitted = [];
                    foreach ($inputFields as $f) {
                        $submitted[$f['key']] = $this->fieldValueFromPost($f, $errors);
                    }
                    if (empty($errors)) {
                        $this->contentModel->saveRevision('fields', $section['key'], $section['label'], $before);
                        foreach ($submitted as $k => $value) {
                            if ($value !== null) { $this->contentModel->setField($k, $value); }
                        }
                        $this->setFlash('"' . $section['label'] . '" saved. The website now shows these changes.', 'success');
                        $this->redirect(self::URL_SECTION . $page . '/' . $targetSec);
                    }
                    $values = [];
                    foreach ($submitted as $k => $value) { $values[$k] = $value ?? $before[$k]; }
                }
            }

            if ($values === null) {
                $values = $isSettings ? $this->serviceSettingsValues($svc, $inputFields) : $this->contentModel->getFields($fieldKeys);
            }
            $fieldsData = ['schema' => $fieldsSchema, 'values' => $values];
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
            'sectionHelp' => $section['help'] ?? '',
            'pageLabel' => Home_content_model::pageLabel($page),
            'isService' => (bool)$svc,
            'service' => $svc,
            'allSections' => Home_content_model::navSections($page),
            'publicUrl' => Home_content_model::pagePublicUrl($page),
            'serviceStatus' => $svc ? $svc['status'] : null,
            'fieldsData' => $fieldsData,
            'itemsData' => $itemsData,
            'errors' => $errors,
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
        if (Home_content_model::isServicePage($page)) {
            $this->previewService($page);
            return;
        }
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
        if (!Home_content_model::pageExists($page)) {
            $this->redirect(self::URL_ADMIN);
        }
        $this->contentModel->setPage($page);

        $schemas = Home_content_model::itemSchemas($page);
        if (!isset($schemas[$section])) {
            $this->redirect(self::URL_ADMIN);
        }
        $schema = $schemas[$section];
        $data = [];
        $isNew = ($id === 'new');
        $errors = [];

        $navKey = Home_content_model::navKeyForItems($section, $page);
        $back = self::URL_SECTION . $page . '/' . $navKey;
        $maxMessage = 'Maximum of ' . ($schema['maxItems'] ?? 0) . ' ' . strtolower($schema['label']) . ' reached. Remove one before adding another, or the layout will no longer line up.';

        if ($isNew && !empty($schema['maxItems']) && count($this->contentModel->getItems($section)) >= $schema['maxItems']) {
            $this->setFlash($maxMessage, 'error');
            $this->redirect($back);
        }

        if (!$isNew) {
            $existing = ctype_digit((string)$id) ? $this->contentModel->getItem((int)$id) : null;
            if (!$existing || $existing['section_key'] !== $section || $existing['page'] !== $page) {
                $this->setFlash('That entry no longer exists. It may have been deleted.', 'error');
                $this->redirect($back);
            }
            $data = $existing['data'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();

            if ($isNew && !empty($schema['maxItems']) && count($this->contentModel->getItems($section)) >= $schema['maxItems']) {
                $this->setFlash($maxMessage, 'error');
                $this->redirect($back);
            }

            $newData = [];
            foreach ($schema['fields'] as $f) {
                $value = $this->fieldValueFromPost($f, $errors);
                $newData[$f['key']] = $value ?? ($data[$f['key']] ?? '');
            }
            $hasContent = false;
            foreach ($newData as $v) { if (trim((string)$v) !== '') { $hasContent = true; break; } }
            if (!$hasContent) {
                $errors[] = 'This entry is empty. Fill in at least one field before saving.';
            }

            if (empty($errors)) {
                if ($isNew) {
                    $this->contentModel->addItem($section, $newData);
                    $this->setFlash('New entry added to "' . $schema['label'] . '".', 'success');
                } else {
                    $this->contentModel->saveRevision('item', $section, $this->itemLabel($schema, $data), $data, (int)$id);
                    $this->contentModel->updateItem((int)$id, $newData);
                    $this->setFlash('Entry updated. You can undo this from History.', 'success');
                }
                $this->redirect($back);
            }
            $data = $newData;   // re-show what was typed
        }

        $this->view('admin/item_edit', [
            'title' => ($isNew ? 'Add' : 'Edit') . ' ' . $schema['label'] . ' - Admin - Workiify',
            'pageLabel' => Home_content_model::pageLabel($page),
            'page' => $page,
            'section' => $section,
            'navKey' => $navKey,
            'schema' => $schema,
            'id' => $id,
            'isNew' => $isNew,
            'data' => $data,
            'errors' => $errors,
            'csrfToken' => $this->csrfToken(),
            'mediaAssets' => $this->mediaModel->getAll(),
        ]);
    }

    // Delete one item (POST only), e.g. posts to /admin/item-delete/home/services/5
    public function item_delete($page = '', $section = '', $id = '') {
        $this->requireLogin();
        if (!Home_content_model::pageExists($page)) {
            $this->redirect(self::URL_ADMIN);
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
                    $this->setFlash('At least ' . $schema['minItems'] . ' ' . strtolower($schema['label']) . ' are required to keep this layout aligned. Edit this entry instead of deleting it.', 'error');
                } else {
                    $label = $schema ? $this->itemLabel($schema, $existing['data']) : ('Item #' . $id);
                    $this->contentModel->saveRevision('item_deleted', $section, $label, $existing['data'], (int)$id);
                    $this->contentModel->deleteItem((int)$id);
                    $this->setFlash('"' . $label . '" was deleted. You can restore it from History.', 'success');
                }
            } else {
                $this->setFlash('That entry no longer exists.', 'error');
            }
        }
        $this->redirect(self::URL_SECTION . $page . '/' . Home_content_model::navKeyForItems($section, $page));
    }

    // Moves one entry up/down within its list (POST only), e.g. /admin/item-move/svc3/faq/12/up
    public function item_move($page = '', $section = '', $id = '', $direction = '') {
        $this->requireLogin();
        if (!Home_content_model::pageExists($page)) {
            $this->redirect(self::URL_ADMIN);
        }
        $this->contentModel->setPage($page);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($direction, ['up', 'down'], true)) {
            $this->checkCsrf();
            $existing = $this->contentModel->getItem((int)$id);
            if ($existing && $existing['page'] === $page && $existing['section_key'] === $section) {
                $this->contentModel->moveItem((int)$id, $direction);
            }
        }
        $this->redirect(self::URL_SECTION . $page . '/' . Home_content_model::navKeyForItems($section, $page));
    }

    // ---- Media library -------------------------------------------------

    // Central media library: upload once here, reuse the same asset across any field.
    public function media() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $errors = [];
            $uploaded = $this->uploadImage('new_asset', 'Image', $errors);
            if ($uploaded) {
                $this->setFlash('Image uploaded to the library.', 'success');
            } else {
                $this->setFlash($errors ? reset($errors) : 'Please choose an image to upload.', 'error');
            }
            $this->redirect(self::URL_MEDIA);
        }

        $all = $this->mediaModel->getAllDetailed();
        $counts = ['all' => count($all), 'upload' => 0, 'builtin' => 0, 'used' => 0, 'unused' => 0];
        foreach ($all as $a) {
            $counts[$a['isUpload'] ? 'upload' : 'builtin']++;
            $counts[$a['usage'] ? 'used' : 'unused']++;
        }

        $filters = [
            'q' => $this->queryParam('q'),
            'kind' => $this->queryParam('kind', ['upload', 'builtin', 'used', 'unused']),
        ];
        $sort = $this->queryParam('sort', ['name', 'date', 'size', 'used']) ?: 'date';
        $dir = $this->queryParam('dir', ['asc', 'desc']);
        if (!$dir) {
            $dir = $sort === 'name' ? 'asc' : 'desc';
        }

        $rows = array_values(array_filter($all, function ($a) use ($filters) {
            if ($filters['q'] !== '' && stripos($a['name'] . ' ' . $a['path'], $filters['q']) === false) { return false; }
            switch ($filters['kind']) {
                case 'upload': return $a['isUpload'];
                case 'builtin': return !$a['isUpload'];
                case 'used': return (bool)$a['usage'];
                case 'unused': return !$a['usage'];
                default: return true;
            }
        }));
        usort($rows, function ($x, $y) use ($sort, $dir) {
            switch ($sort) {
                case 'name': $c = strcasecmp($x['name'], $y['name']); break;
                case 'size': $c = $x['bytes'] <=> $y['bytes']; break;
                case 'used': $c = count($x['usage']) <=> count($y['usage']); break;
                default: $c = strcmp($x['uploaded_at'], $y['uploaded_at']) ?: ($x['id'] <=> $y['id']);
            }
            return $dir === 'asc' ? $c : -$c;
        });
        $pg = $this->pagination(count($rows));

        $this->view('admin/media', [
            'title' => 'Media Library - Admin - Workiify',
            'assets' => array_slice($rows, ($pg['page'] - 1) * self::PER_PAGE, self::PER_PAGE),
            'counts' => $counts,
            'filters' => $filters,
            'sort' => $sort,
            'dir' => $dir,
            'pg' => $pg,
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Edit one library image: rename it, or replace the picture itself. A replacement is stored as a
    // new upload and every page using the old image is switched to it, so the change shows everywhere
    // at once (built-in site images are never overwritten on disk).
    public function media_edit($id = '') {
        $this->requireLogin();
        $asset = ctype_digit((string)$id) ? $this->mediaModel->getById((int)$id) : null;
        if (!$asset) {
            $this->setFlash('That image is no longer in the library.', 'error');
            $this->redirect(self::URL_MEDIA);
        }
        $asset = $this->mediaModel->details($asset);
        $errors = [];
        $form = ['name' => $asset['name']];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $form['name'] = trim((string)($_POST['name'] ?? ''));
            if ($form['name'] === '') { $errors['name'] = 'Name is required.'; }
            elseif (mb_strlen($form['name']) > 150) { $errors['name'] = 'Name must be 150 characters or fewer.'; }
            elseif (preg_match('/[<>"\\\\]/', $form['name'])) { $errors['name'] = 'Name cannot contain < > " or \\ characters.'; }

            $newPath = empty($errors) ? $this->uploadImage('replace_file', 'Replacement image', $errors) : null;
            if (isset($errors['replace_file'])) {
                $errors['replace_file'] = preg_replace('/^Replacement image: /', '', $errors['replace_file']);
                $errors['replace_file'] = ucfirst($errors['replace_file']);
            }

            if (empty($errors)) {
                $ok = $this->mediaModel->rename($asset['id'], $form['name']);
                $msg = 'Image details saved.';
                if ($ok && $newPath) {
                    $usedIn = count($asset['usage']);
                    $ok = $this->mediaModel->replacePath($asset['id'], $asset['path'], $newPath);
                    if ($ok) {
                        if ($asset['isUpload']) {
                            $oldFull = APPROOT . self::PUBLIC_DIR . $asset['path'];
                            if (is_file($oldFull)) { unlink($oldFull); }
                        }
                        $msg = 'Image replaced.';
                        if ($usedIn) {
                            $msg = 'Image replaced and updated on ' . $usedIn . ' place' . ($usedIn === 1 ? '' : 's') . ' across the website.';
                        }
                    } else {
                        $full = APPROOT . self::PUBLIC_DIR . $newPath;
                        if (is_file($full)) { unlink($full); }
                        $this->mediaModel->delete((int)($this->mediaIdForPath($newPath) ?? 0));
                    }
                }
                if ($ok) {
                    $this->setFlash($msg, 'success');
                    $this->redirect($this->listReturn(self::URL_MEDIA));
                }
                $errors[] = self::MSG_DB_ERROR;
            }
        }

        $this->view('admin/media_edit', [
            'title' => 'Edit Image - Media Library - Workiify',
            'asset' => $asset,
            'form' => $form,
            'errors' => $errors,
            'returnTo' => $this->listReturn(self::URL_MEDIA),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    private function mediaIdForPath($path) {
        foreach ($this->mediaModel->getAll() as $a) {
            if ($a['path'] === $path) { return $a['id']; }
        }
        return null;
    }

    public function media_delete($id = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $asset = $this->mediaModel->getById((int)$id);
            if (!$asset) {
                $this->setFlash('That image is no longer in the library.', 'error');
            } elseif (!Media_model::isUpload($asset['path'])) {
                $this->setFlash('Built-in site images cannot be removed from the library.', 'error');
            } elseif ($usage = $this->mediaModel->usage($asset['path'])) {
                $this->setFlash('This image is still used in: ' . implode(', ', array_slice($usage, 0, 4)) . (count($usage) > 4 ? ' and more' : '') . '. Replace it there first, then remove it.', 'error');
            } else {
                $full = APPROOT . self::PUBLIC_DIR . $asset['path'];
                if (is_file($full)) {
                    unlink($full);
                }
                $this->mediaModel->delete((int)$id);
                $this->setFlash('"' . ($asset['original_name'] ?: basename($asset['path'])) . '" was deleted.', 'success');
            }
        }
        $this->redirect($this->listReturn(self::URL_MEDIA));
    }

    // ---- History -------------------------------------------------------

    // One combined undo screen across every page: every field-section save and
    // every item save/delete leaves a revision behind here, newest first.
    public function history() {
        $this->requireLogin();
        $pageFilter = $this->queryParam('p', Home_content_model::revisionPages());
        $pg = $this->pagination(Home_content_model::countRevisions($pageFilter));
        $revisions = Home_content_model::getRecentRevisions(self::PER_PAGE, ($pg['page'] - 1) * self::PER_PAGE, $pageFilter);

        $pageLabels = Home_content_model::pages();
        foreach (Home_content_model::revisionPages() as $p) {
            if (!isset($pageLabels[$p])) { $pageLabels[$p] = Home_content_model::pageLabel($p); }
        }

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
            'pageFilter' => $pageFilter,
            'pg' => $pg,
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
            if ($revision && Home_content_model::pageExists($revision['page'])) {
                $this->contentModel->setPage($revision['page']);
                $this->contentModel->restoreRevision($revision);
                $this->setFlash('Restored "' . $revision['label'] . '".', 'success');
            } else {
                $this->setFlash('That history entry can no longer be restored (its page was removed).', 'error');
            }
        }
        $this->redirect($this->listReturn('/admin/history'));
    }

    // ---- Services Module -----------------------------------------------
    // Adding, removing, publishing and ordering services. A service's own content is edited
    // through the same section screens as every other page (see section() above).

    // Only ever redirects back into the services area.
    private function serviceReturnPath() {
        $r = $_POST['return'] ?? '';
        if (is_string($r) && (strpos($r, self::URL_SERVICES) === 0 || strpos($r, '/admin/section/svc') === 0) && !preg_match('/[\r\n]/', $r)) {
            return $r;
        }
        return self::URL_SERVICES;
    }

    public function services() {
        $this->requireLogin();
        $this->view('admin/services', [
            'title' => 'Services - Admin - Workiify',
            'services' => $this->serviceModel->adminList(false),
            'trashed' => $this->serviceModel->adminList(true),
            'offline' => Service_model::isOffline(),
            'categories' => Service_model::categories(),
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Add a service: it gets the shared page template, blank or copied from another service.
    public function service_new() {
        $this->requireLogin();
        $errors = [];
        $form = ['title' => '', 'slug' => '', 'category' => 'workspaces', 'copy_from' => 0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $form['title'] = (string)($_POST['title'] ?? '');
            $form['slug'] = (string)($_POST['slug'] ?? '');
            $form['category'] = (string)($_POST['category'] ?? 'workspaces');
            $form['copy_from'] = (int)($_POST['copy_from'] ?? 0);

            [$title, $slug, $category] = $this->serviceModel->validateIdentity($form['title'], $form['slug'], $form['category'], 0, $errors);
            $copy = null;
            if ($form['copy_from']) {
                $copy = $this->serviceModel->get($form['copy_from']);
                if (!$copy || $copy['deleted_at']) {
                    $errors[] = 'The service you chose to copy no longer exists.';
                }
            }
            if (empty($errors)) {
                try {
                    $id = $this->serviceModel->create($title, $slug, $category, $copy ? $copy['id'] : 0);
                    $this->setFlash('"' . $title . '" was created as a draft using the shared page template. Fill in its sections, then set it to Published in Service Settings.', 'success');
                    $this->redirect(self::URL_SECTION . Service_model::pageKey($id) . '/banner');
                } catch (Throwable $e) {
                    error_log('Service create failed: ' . $e->getMessage());
                    $errors[] = 'Could not create the service (a database error occurred).';
                }
            }
        }

        $this->view('admin/service_new', [
            'title' => 'Add a Service - Admin - Workiify',
            'form' => $form,
            'errors' => $errors,
            'categories' => Service_model::categories(),
            'existing' => $this->serviceModel->adminList(false),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // POST-only actions from the list screen, e.g. /admin/service-action/trash/7
    public function service_action($action = '', $id = '') {
        $this->requireLogin();
        $back = function () {
            $this->redirect($this->serviceReturnPath());
        };
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(self::URL_SERVICES);
        }
        $this->checkCsrf();

        $svc = $this->serviceModel->get((int)$id);
        if (!$svc) {
            $this->setFlash('That service no longer exists.', 'error');
            $back();
        }
        $name = '"' . $svc['title'] . '"';

        switch ($action) {
            case 'publish':
                $this->contentModel->setPage(Service_model::pageKey($svc['id']));
                $heading = $this->contentModel->getField('banner_heading', '');
                if (trim($heading) === '') {
                    $this->setFlash($name . ' has no main heading yet. Open its Page Banner, add one, then publish.', 'error');
                } elseif ($svc['show_on_listing'] && $svc['card_summary'] === '') {
                    $this->setFlash($name . ' has no short description for its card yet. Open Service Settings and add one (or hide it from the Services page), then publish.', 'error');
                } else {
                    $this->serviceModel->setFlag($svc['id'], 'status', 'published');
                    $this->setFlash($name . ' is now live.', 'success');
                }
                break;
            case 'unpublish':
                $this->serviceModel->setFlag($svc['id'], 'status', 'draft');
                $this->setFlash($name . ' was unpublished: it is now a draft and hidden from visitors.', 'success');
                break;
            case 'up':
            case 'down':
                if (!$this->serviceModel->move($svc['id'], $action)) {
                    $this->setFlash($name . ' is already at the ' . ($action === 'up' ? 'top' : 'bottom') . ' of its category.', 'info');
                }
                break;
            case 'trash':
                $this->serviceModel->trash($svc['id']);
                $this->setFlash($name . ' was removed from the site. You can bring it back from "Recently removed" below.', 'success');
                break;
            case 'restore':
                $this->serviceModel->restore($svc['id']);
                $this->setFlash($name . ' was restored as a draft. Review it, then publish when ready.', 'success');
                break;
            case 'purge':
                if (!$svc['deleted_at']) {
                    $this->setFlash('Remove it first.', 'error');
                } elseif ($this->serviceModel->purge($svc['id'])) {
                    $this->setFlash($name . ' was permanently deleted, along with all of its content.', 'success');
                }
                break;
            default:
                $this->setFlash('Unknown action.', 'error');
        }
        $back();
    }

    // Live preview of a service page with the section form's unsaved edits applied.
    private function previewService($page) {
        $svc = $this->serviceModel->get(Home_content_model::servicePageId($page));
        if (!$svc || $svc['deleted_at']) {
            http_response_code(404);
            exit;
        }
        $this->checkCsrf();
        $content = $this->serviceModel->pageContent($svc['id']);
        $overrides = json_decode($_POST['fields_json'] ?? '[]', true);
        if (is_array($overrides)) {
            foreach ($overrides as $k => $v) {
                if (array_key_exists($k, $content['f'])) {
                    $content['f'][$k] = is_string($v) ? $v : '';
                }
            }
        }
        $this->view('pages/service_page', [
            'title' => $svc['title'] . ' - Workiify',
            'service' => $svc,
            'content' => $content,
            'related' => $this->serviceModel->related($svc['id']),
            'isDraft' => false,
            'hideTourBand' => true,
        ]);
    }

    // ---- Feedback Module ----------------------------------------------

    public function feedback() {
        $this->requireLogin();

        $filters = [
            'q' => $this->queryParam('q'),
            'status' => $this->queryParam('status', Testimonial::statuses()),
            'rating' => $this->queryParam('rating', ['1', '2', '3', '4', '5']),
            'featured' => $this->queryParam('featured', ['0', '1']),
        ];
        $sort = $this->queryParam('sort', Testimonial::sortKeys()) ?: 'date';
        $dir = $this->queryParam('dir', ['asc', 'desc']);
        if (!$dir) {
            $dir = $sort === 'date' ? 'desc' : 'asc';
        }

        [, $total] = $this->testimonialModel->search($filters, $sort, $dir, 1, 1);
        $pg = $this->pagination($total);
        [$rows] = $this->testimonialModel->search($filters, $sort, $dir, $pg['page'], self::PER_PAGE);

        $this->view('admin/feedback', [
            'title' => 'Feedback - Workiify Admin',
            'testimonials' => $rows,
            'filters' => $filters,
            'sort' => $sort,
            'dir' => $dir,
            'pg' => $pg,
            'counts' => $this->testimonialModel->statusCounts(),
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Validates the feedback form. Returns [clean data, errors keyed by field].
    private function validateFeedback(array $in) {
        $d = [
            'full_name' => trim((string)($in['full_name'] ?? '')),
            'company' => trim((string)($in['company'] ?? '')),
            'designation' => trim((string)($in['designation'] ?? '')),
            'email' => trim((string)($in['email'] ?? '')),
            'rating' => (string)($in['rating'] ?? ''),
            'feedback' => trim((string)($in['feedback'] ?? '')),
            'status' => (string)($in['status'] ?? ''),
            'show_on_home' => isset($in['show_on_home']) ? 1 : 0,
        ];
        $e = [];
        if ($d['full_name'] === '') { $e['full_name'] = 'Full name is required.'; }
        elseif (mb_strlen($d['full_name']) > 100) { $e['full_name'] = 'Full name must be 100 characters or fewer.'; }
        if (mb_strlen($d['company']) > 150) { $e['company'] = 'Company must be 150 characters or fewer.'; }
        if (mb_strlen($d['designation']) > 100) { $e['designation'] = 'Designation must be 100 characters or fewer.'; }
        if ($d['email'] !== '' && (!filter_var($d['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($d['email']) > 255)) { $e['email'] = 'Enter a valid email address.'; }
        if (!in_array($d['rating'], ['1', '2', '3', '4', '5'], true)) { $e['rating'] = 'Choose a rating from 1 to 5 stars.'; }
        $len = mb_strlen($d['feedback']);
        if ($len === 0) { $e['feedback'] = 'Feedback text is required.'; }
        elseif ($len < 20 || $len > 1000) { $e['feedback'] = 'Feedback must be between 20 and 1000 characters (currently ' . $len . ').'; }
        if (!in_array($d['status'], Testimonial::statuses(), true)) { $e['status'] = self::MSG_BAD_STATUS; }
        return [$d, $e];
    }

    // Optional avatar upload for a testimonial (stored like the public form does, in
    // /uploads/testimonials). Returns the new file name, or null when nothing was uploaded.
    private function feedbackPhotoUpload(array &$errors) {
        $file = $_FILES['photo'] ?? null;
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) { return null; }
        if ($file['error'] !== UPLOAD_ERR_OK) { $errors['photo'] = 'The photo upload failed. Please try again.'; return null; }
        if ($file['size'] > self::MAX_UPLOAD_BYTES) { $errors['photo'] = 'The photo must be smaller than 5 MB.'; return null; }
        $allowed = ['jpg' => self::MIME_JPEG, 'jpeg' => self::MIME_JPEG, 'png' => 'image/png', 'webp' => 'image/webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!isset($allowed[$ext]) || $mime !== $allowed[$ext]) { $errors['photo'] = 'The photo must be a JPG, PNG or WebP image.'; return null; }
        $dir = APPROOT . '/../public/uploads/testimonials/';
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $name = 'avatar_' . bin2hex(random_bytes(6)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . $name)) { $errors['photo'] = 'The photo could not be saved.'; return null; }
        return $name;
    }

    // Removes a testimonial photo file once no testimonial refers to it any more.
    private function removeFeedbackPhotoIfUnused($photo) {
        if (!$photo || $this->testimonialModel->countPhotoUsage($photo) > 0) { return; }
        $full = APPROOT . '/../public/uploads/testimonials/' . basename($photo);
        if (is_file($full)) { unlink($full); }
    }

    // Add (/admin/feedback-edit/new) or review/edit (/admin/feedback-edit/5) a testimonial.
    public function feedback_edit($id = 'new') {
        $this->requireLogin();
        $isNew = ($id === 'new');
        $testimonial = null;

        if (!$isNew) {
            $testimonial = ctype_digit((string)$id) ? $this->testimonialModel->getTestimonialById((int)$id) : null;
            if (!$testimonial) {
                $this->setFlash('That feedback entry was not found. It may have been deleted.', 'error');
                $this->redirect(self::URL_FEEDBACK);
            }
        }

        $form = $testimonial ? (array)$testimonial : [
            'full_name' => '', 'company' => '', 'designation' => '', 'email' => '', 'rating' => '5',
            'feedback' => '', 'status' => 'Approved', 'show_on_home' => 0, 'photo' => null,
        ];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$clean, $errors] = $this->validateFeedback($_POST);
            $oldPhoto = $testimonial->photo ?? null;
            $clean['photo'] = (string)$oldPhoto;
            if (empty($errors)) {
                $newPhoto = $this->feedbackPhotoUpload($errors);
                if ($newPhoto) { $clean['photo'] = $newPhoto; }
                elseif (!empty($_POST['remove_photo'])) { $clean['photo'] = ''; }
            }

            if (empty($errors)) {
                if ($isNew) {
                    $ok = (bool)$this->testimonialModel->create($clean);
                } else {
                    $clean['id'] = (int)$id;
                    $ok = $this->testimonialModel->updateTestimonial($clean);
                }
                if ($ok) {
                    if ($oldPhoto && $clean['photo'] !== $oldPhoto) { $this->removeFeedbackPhotoIfUnused($oldPhoto); }
                    $note = ($clean['show_on_home'] && $clean['status'] !== 'Approved') ? ' It is marked as featured, but only Approved feedback appears on the website.' : '';
                    $this->setFlash(($isNew ? 'Testimonial from ' . $clean['full_name'] . ' added.' : self::LOG_FEEDBACK . $clean['full_name'] . ' updated.') . $note, 'success');
                    $this->redirect($this->listReturn(self::URL_FEEDBACK));
                }
                $errors[] = self::MSG_DB_ERROR;
            }
            $form = array_merge($form, $clean);
            $form['photo'] = $oldPhoto;
        }

        $this->view('admin/feedback_edit', [
            'title' => ($isNew ? 'Add Testimonial' : 'Edit Feedback') . ' - Workiify Admin',
            'isNew' => $isNew,
            'id' => $isNew ? null : (int)$id,
            'form' => $form,
            'meta' => $testimonial,
            'errors' => $errors,
            'returnTo' => $this->listReturn(self::URL_FEEDBACK),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Quick Approve / Reject from the list (POST only), e.g. /admin/feedback-status/5/Approved
    public function feedback_status($id = '', $status = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $t = $this->testimonialModel->getTestimonialById((int)$id);
            if (!$t) {
                $this->setFlash('That feedback entry was not found.', 'error');
            } elseif (!in_array($status, Testimonial::statuses(), true)) {
                $this->setFlash('Unknown status.', 'error');
            } else {
                $this->testimonialModel->setStatus($t->id, $status);
                $this->setFlash(self::LOG_FEEDBACK . $t->full_name . ' marked as ' . $status . '.', 'success');
            }
        }
        $this->redirect($this->listReturn(self::URL_FEEDBACK));
    }

    public function feedback_delete($id = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $t = $this->testimonialModel->getTestimonialById((int)$id);
            if (!$t) {
                $this->setFlash('That feedback entry was already deleted.', 'error');
            } elseif ($this->testimonialModel->delete($t->id)) {
                $this->removeFeedbackPhotoIfUnused($t->photo);
                $this->setFlash(self::LOG_FEEDBACK . $t->full_name . ' was deleted.', 'success');
            } else {
                $this->setFlash('Could not delete (a database error occurred).', 'error');
            }
        }
        $this->redirect($this->listReturn(self::URL_FEEDBACK));
    }

    // ---- Enquiries Module --------------------------------------------

    public function enquiries() {
        $this->requireLogin();

        $filters = [
            'q' => $this->queryParam('q'),
            'status' => $this->queryParam('status', Enquiry::statuses()),
            'source' => $this->queryParam('source', array_keys(Enquiry::sources())),
            'from' => $this->dateParam('from'),
            'to' => $this->dateParam('to'),
        ];
        $sort = $this->queryParam('sort', Enquiry::sortKeys()) ?: 'date';
        $dir = $this->queryParam('dir', ['asc', 'desc']);
        if (!$dir) {
            $dir = $sort === 'date' ? 'desc' : 'asc';
        }

        [, $total] = $this->enquiryModel->search($filters, $sort, $dir, 1, 1);
        $pg = $this->pagination($total);
        [$rows] = $this->enquiryModel->search($filters, $sort, $dir, $pg['page'], self::PER_PAGE);

        $this->view('admin/enquiries', [
            'title' => 'Enquiries - Workiify Admin',
            'enquiries' => $rows,
            'filters' => $filters,
            'sort' => $sort,
            'dir' => $dir,
            'pg' => $pg,
            'counts' => $this->enquiryModel->statusCounts(),
            'sources' => Enquiry::sources(),
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    private function findEnquiryOrBack($id) {
        $enquiry = ctype_digit((string)$id) ? $this->enquiryModel->getEnquiryById((int)$id) : null;
        if (!$enquiry) {
            $this->setFlash('That enquiry was not found. It may have been deleted.', 'error');
            $this->redirect('/admin/enquiries');
        }
        return $enquiry;
    }

    public function enquiry_view($id = '') {
        $this->requireLogin();
        $enquiry = $this->findEnquiryOrBack($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $status = (string)($_POST['status'] ?? '');
            if (!in_array($status, Enquiry::statuses(), true)) {
                $this->setFlash(self::MSG_BAD_STATUS, 'error');
            } elseif ($this->enquiryModel->updateStatus($enquiry->id, $status)) {
                $this->setFlash('Status changed to ' . $status . '.', 'success');
            } else {
                $this->setFlash('Something went wrong. The status was not changed.', 'error');
            }
            $this->redirect(self::URL_ENQUIRY_VIEW . $enquiry->id);
        }

        $this->view('admin/enquiry_view', [
            'title' => 'View Enquiry - Workiify Admin',
            'enquiry' => $enquiry,
            'sources' => Enquiry::sources(),
            'flash' => $this->takeFlash(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    // Validates the enquiry form with the same rules as the website's own enquiry form.
    // Returns [clean data, errors keyed by field].
    private function validateEnquiry(array $in) {
        $phone = preg_replace('/[\s\-()]/', '', (string)($in['contact_no'] ?? ''));
        $phone = preg_replace('/^(\+91|0091|0)(?=\d{10}$)/', '', $phone);
        $d = [
            'company_name' => trim((string)($in['company_name'] ?? '')),
            'company_address' => trim((string)($in['company_address'] ?? '')),
            'contact_no' => $phone,
            'email' => trim((string)($in['email'] ?? '')),
            'req_type' => (string)($in['req_type'] ?? ''),
            'req_value' => trim((string)($in['req_value'] ?? '')),
            'additional' => trim((string)($in['additional'] ?? '')),
            'source' => (string)($in['source'] ?? ''),
            'status' => (string)($in['status'] ?? ''),
        ];
        $e = [];
        if ($d['company_name'] === '') { $e['company_name'] = 'Company name is required.'; }
        elseif (mb_strlen($d['company_name']) > 150) { $e['company_name'] = 'Company name must be 150 characters or fewer.'; }
        if ($d['company_address'] === '') { $e['company_address'] = 'Company address is required.'; }
        elseif (mb_strlen($d['company_address']) > 250) { $e['company_address'] = 'Company address must be 250 characters or fewer.'; }
        if (!preg_match('/^[6-9]\d{9}$/', $d['contact_no'])) { $e['contact_no'] = 'Enter a valid 10-digit mobile number (starting with 6-9).'; }
        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($d['email']) > 150) { $e['email'] = 'Enter a valid email address.'; }
        if (!in_array($d['req_type'], ['space', 'seats'], true)) { $e['req_type'] = 'Choose a requirement type.'; }
        if (!ctype_digit($d['req_value']) || (int)$d['req_value'] < 1 || (int)$d['req_value'] > 1000000) { $e['req_value'] = 'Enter a whole number greater than 0.'; }
        if (mb_strlen($d['additional']) > 1000) { $e['additional'] = 'Additional requirements must be 1000 characters or fewer.'; }
        if (!array_key_exists($d['source'], Enquiry::sources())) { $e['source'] = 'Choose where this enquiry came from.'; }
        if (!in_array($d['status'], Enquiry::statuses(), true)) { $e['status'] = self::MSG_BAD_STATUS; }
        return [$d, $e];
    }

    // Add (/admin/enquiry-edit/new) or edit (/admin/enquiry-edit/5) an enquiry.
    public function enquiry_edit($id = 'new') {
        $this->requireLogin();
        $isNew = ($id === 'new');
        $enquiry = $isNew ? null : $this->findEnquiryOrBack($id);

        $form = $enquiry ? (array)$enquiry : [
            'company_name' => '', 'company_address' => '', 'contact_no' => '', 'email' => '',
            'req_type' => 'seats', 'req_value' => '', 'additional' => '', 'source' => 'phone', 'status' => 'New',
        ];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$clean, $errors] = $this->validateEnquiry($_POST);
            if (empty($errors)) {
                if ($isNew) {
                    $newId = $this->enquiryModel->create($clean);
                    if ($newId) {
                        $this->setFlash(self::LOG_ENQUIRY . $clean['company_name'] . ' added.', 'success');
                        $this->redirect(self::URL_ENQUIRY_VIEW . $newId);
                    }
                } elseif ($this->enquiryModel->update($enquiry->id, $clean)) {
                    $this->setFlash(self::LOG_ENQUIRY . $clean['company_name'] . ' updated.', 'success');
                    $this->redirect(self::URL_ENQUIRY_VIEW . $enquiry->id);
                }
                $errors[] = self::MSG_DB_ERROR;
            }
            $form = array_merge($form, $clean);
        }

        $this->view('admin/enquiry_edit', [
            'title' => ($isNew ? 'Add Enquiry' : 'Edit Enquiry') . ' - Workiify Admin',
            'isNew' => $isNew,
            'enquiry' => $enquiry,
            'form' => $form,
            'errors' => $errors,
            'sources' => Enquiry::sources(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function enquiry_delete($id = '') {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $enquiry = $this->enquiryModel->getEnquiryById((int)$id);
            if (!$enquiry) {
                $this->setFlash('That enquiry was already deleted.', 'error');
            } elseif ($this->enquiryModel->delete($enquiry->id)) {
                $this->setFlash(self::LOG_ENQUIRY . $enquiry->company_name . ' was deleted.', 'success');
            } else {
                $this->setFlash('Could not delete (a database error occurred).', 'error');
            }
        }
        $this->redirect($this->listReturn('/admin/enquiries'));
    }
}
