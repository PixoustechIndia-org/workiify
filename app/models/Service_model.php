<?php
/*
 * Services
 *
 * The `services` table holds only what the site needs to *find* a service: its name, URL,
 * category, status, order, the card shown on the Services page, its menu entry and its SEO text.
 * The page's content (every heading, paragraph, photo, card, FAQ ...) lives in the CMS tables
 * (home_fields / home_items, page = "svc<id>"), exactly like the Home and About pages, and is
 * described by Service_schema. That is what lets the admin edit a service like any other page.
 *
 * The table creates and seeds itself the first time it is needed. If the database is ever
 * unreachable the public menu and Services page fall back to the built-in defaults.
 */
require_once __DIR__ . '/Service_schema.php';
require_once __DIR__ . '/Home_content_model.php';

class Service_model {
    private $db;
    private static $ready = false;
    private static $offline = false;

    // Slugs that would collide with a method on the Services controller.
    const RESERVED_SLUGS = ['index'];
    const SEED_MARKER_PAGE = '_services';
    const SEED_MARKER_KEY = 'content_v3';

    public static function categories() {
        return [
            'workspaces'       => ['label' => 'Workspaces',        'eyebrow' => 'Workspaces',        'heading' => 'A Desk, or a Whole Office, on Your Terms', 'subtitle' => 'From a single desk to a fully private office, find the plan that fits how you work.'],
            'meetings-events'  => ['label' => 'Meetings & Events', 'eyebrow' => 'Meetings & Events', 'heading' => 'Spaces to Host, Present and Celebrate',     'subtitle' => 'Polished, well-equipped rooms for everything from a client call to a full workshop.'],
            'virtual-day-pass' => ['label' => 'Virtual & Day Pass', 'eyebrow' => 'Virtual & Day Pass', 'heading' => 'Not Ready for a Full Commitment?',            'subtitle' => 'A professional address or a one-off visit, without signing up for a plan.'],
        ];
    }

    public function __construct() {
        $this->db = new Database();
        $this->install();
    }

    public static function isOffline() {
        return self::$offline;
    }

    public static function pageKey($id) {
        return 'svc' . (int)$id;
    }

    // ------------------------------------------------------------------
    // Installation / seeding (runs once, then a single marker lookup per request)
    // ------------------------------------------------------------------
    private function install() {
        if (self::$ready) { return; }
        self::$ready = true;
        try {
            $this->db->query('SELECT 1 FROM home_fields WHERE page = :p AND field_key = :k');
            $this->db->bind(':p', self::SEED_MARKER_PAGE);
            $this->db->bind(':k', self::SEED_MARKER_KEY);
            if ($this->db->single()) { return; }

            $this->db->query("CREATE TABLE IF NOT EXISTS services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(80) NOT NULL UNIQUE,
                old_slugs TEXT NULL,
                title VARCHAR(150) NOT NULL,
                category VARCHAR(30) NOT NULL DEFAULT 'workspaces',
                status ENUM('published','draft') NOT NULL DEFAULT 'draft',
                sort_order INT NOT NULL DEFAULT 0,
                show_on_listing TINYINT(1) NOT NULL DEFAULT 1,
                show_in_nav TINYINT(1) NOT NULL DEFAULT 0,
                card_image VARCHAR(255) NOT NULL DEFAULT '',
                card_badge VARCHAR(80) NOT NULL DEFAULT '',
                card_summary VARCHAR(300) NOT NULL DEFAULT '',
                card_features TEXT NULL,
                card_cta VARCHAR(60) NOT NULL DEFAULT '',
                nav_label VARCHAR(60) NOT NULL DEFAULT '',
                nav_icon VARCHAR(50) NOT NULL DEFAULT '',
                nav_blurb VARCHAR(80) NOT NULL DEFAULT '',
                meta_title VARCHAR(120) NOT NULL DEFAULT '',
                meta_description VARCHAR(255) NOT NULL DEFAULT '',
                deleted_at DATETIME NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_public (status, deleted_at, category, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();

            // first install: the built-in services
            $this->db->query('SELECT COUNT(*) AS n FROM services');
            if ((int)$this->db->single()->n === 0) {
                foreach (self::defaults() as $row) {
                    $this->insertRow($row, true);
                }
            }

            // give every service its page content (built-in pages, or a conversion of a
            // service created with the earlier simple form)
            $this->db->query('SELECT * FROM services');
            foreach ($this->db->resultSet() as $row) {
                $this->seedContentIfMissing((array)$row);
            }

            $this->migrateShortNames();

            $content = new Home_content_model();
            $content->setPage(self::SEED_MARKER_PAGE);
            $content->setField(self::SEED_MARKER_KEY, '1');
        } catch (PDOException $e) {
            error_log('Service_model install failed: ' . $e->getMessage());
            self::$offline = true;
        }
    }

    // One-time tidy-up: services are listed by their short name ("Shared Workspace"; "in Coimbatore" stays
    // in the page content only), the separate "Coimbatore Guides" section is folded into the main
    // categories, and the duplicate "Virtual Office" entries become one. Only rows still carrying the
    // old built-in values are touched, so anything an admin has already edited is left alone.
    private function migrateShortNames() {
        $map = [
            'coworking-space-coimbatore' => ['Co Working Space in Coimbatore', 'Co Working Space', 'workspaces', 'Flexible plans',
                'Hot desks, dedicated desks, private offices and meeting rooms in Saravanampatti, with Wi-Fi, parking and a food court.',
                ['Hot desks to private offices', 'High-speed Wi-Fi and parking', 'Food court and game area'], 'Explore Co Working Space'],
            'shared-workspace-in-coimbatore' => ['Shared Workspace in Coimbatore', 'Shared Workspace', 'workspaces', '24/7 access',
                'A professional shared workspace for freelancers, startups and growing teams, open round the clock.',
                ['24/7 access', 'Hot, dedicated and private desks', 'Celebration hall for up to 60 people'], 'Explore Shared Workspace'],
            'flexible-office-space-coimbatore' => ['Flexible Office Space in Coimbatore', 'Flexible Office Space', 'workspaces', 'Flexible plans',
                'Private, managed and shared offices with flexible rental plans that grow with your team.',
                ['Private and managed offices', 'Flexible rental terms', 'Room to scale as you grow'], 'Explore Flexible Office Space'],
            'virtual-office-in-coimbatore' => ['Virtual Office in Coimbatore', 'Virtual Office', 'virtual-day-pass', 'Business address',
                'A professional business address with mail handling and GST / company registration support.',
                ['Business address in Saravanampatti', 'Mail handling', 'GST and company registration support'], 'Explore Virtual Office'],
        ];
        foreach ($map as $slug => [$oldTitle, $title, $category, $badge, $summary, $feat, $cta]) {
            $this->db->query("UPDATE services SET title = :t, category = :c, card_badge = :b, card_summary = :s, card_features = :f, card_cta = :cta
                              WHERE slug = :slug AND title = :old");
            $this->db->bind(':t', $title);
            $this->db->bind(':c', $category);
            $this->db->bind(':b', $badge);
            $this->db->bind(':s', $summary);
            $this->db->bind(':f', json_encode($feat, JSON_UNESCAPED_UNICODE));
            $this->db->bind(':cta', $cta);
            $this->db->bind(':slug', $slug);
            $this->db->bind(':old', $oldTitle);
            $this->db->execute();
        }

        // two "Virtual Office" services -> keep the full page, retire the simple one (its address redirects)
        $this->db->query("SELECT id, old_slugs FROM services WHERE slug = 'virtual-office-in-coimbatore' AND title = 'Virtual Office' AND deleted_at IS NULL");
        $full = $this->db->single();
        $this->db->query("SELECT id FROM services WHERE slug = 'virtual-office' AND title = 'Virtual Office' AND deleted_at IS NULL");
        $simple = $this->db->single();
        if ($full && $simple) {
            $old = json_decode($full->old_slugs ?? '[]', true) ?: [];
            $old[] = 'virtual-office';
            $this->db->query('UPDATE services SET old_slugs = :o, sort_order = 100, show_on_listing = 1 WHERE id = :id');
            $this->db->bind(':o', json_encode(array_values(array_unique($old))));
            $this->db->bind(':id', (int)$full->id);
            $this->db->execute();
            $this->db->query('UPDATE services SET deleted_at = NOW() WHERE id = :id');
            $this->db->bind(':id', (int)$simple->id);
            $this->db->execute();
        }

        // anything still filed under the removed "guides" category goes with the other workspaces
        $this->db->query("UPDATE services SET category = 'workspaces' WHERE category = 'guides'");
        $this->db->execute();
    }

    private function seedContentIfMissing(array $row) {
        $page = self::pageKey($row['id']);
        $this->db->query("SELECT 1 FROM home_fields WHERE page = :p AND field_key = '_seeded'");
        $this->db->bind(':p', $page);
        if ($this->db->single()) { return; }

        $json = self::seedJson();
        if (isset($json[$row['slug']])) {
            $fields = $json[$row['slug']]['fields'];
            $items = $json[$row['slug']]['items'];
            $base = Service_schema::newServiceFields($row['title']);
            $fields = array_merge($base, $fields);
        } else {
            // built-in simple services carry their old-style fields in defaults(); services created
            // with the earlier form carry them in their own row.
            $legacy = $row;
            foreach (self::defaults() as $d) {
                if ($d['slug'] === $row['slug']) { $legacy = array_merge($row, $d); break; }
            }
            [$fields, $items] = self::contentFromLegacy($legacy);
        }
        $this->writeContent($page, $fields, $items);
    }

    private static function seedJson() {
        static $json = null;
        if ($json === null) {
            $file = APPROOT . '/database/services_seed.json';
            $json = is_file($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
        }
        return $json;
    }

    // Writes a page's fields + items in one go (replacing anything already there).
    private function writeContent($page, array $fields, array $items) {
        $content = new Home_content_model();
        $content->setPage($page);
        $content->deletePage();
        foreach ($fields as $k => $v) {
            if ($v === null) { continue; }
            $content->setField($k, (string)$v);
        }
        foreach ($items as $section => $rows) {
            foreach ($rows as $data) {
                $content->addItem($section, $data);
            }
        }
        $content->setField('_seeded', '1');
    }

    // Converts a service created with the earlier simple form into template content.
    private static function contentFromLegacy(array $row) {
        $f = Service_schema::newServiceFields($row['title']);
        $items = [];
        $layout = $row['layout'] ?? 'split-left';
        $includes = json_decode($row['includes'] ?? '[]', true) ?: [];
        $benefits = json_decode($row['benefits'] ?? '[]', true) ?: [];
        $intro = trim((string)($row['detail_intro'] ?? ''));
        $ideal = trim((string)($row['ideal_for'] ?? ''));
        $badge = trim((string)($row['badge_text'] ?? ''));
        $img = $row['detail_image'] ?? '' ?: ($row['banner_image'] ?? '' ?: ($row['card_image'] ?? ''));
        $cta = trim((string)($row['cta_label'] ?? '')) ?: 'Enquire Now';
        $check = function ($list) {
            return array_map(function ($t) { return ['icon' => 'fa-check-circle', 'title' => $t, 'text' => '']; }, $list);
        };

        $f['banner_image'] = ($row['banner_image'] ?? '') ?: ($img ?: '/images/services_bg.jpg');
        $f['banner_tagline'] = (string)($row['banner_tagline'] ?? '');
        $f['cta_show'] = '0';
        $f['related_show'] = '1';

        if ($layout === 'centered') {
            $f['intro_show'] = '1';
            $f['intro_text'] = $intro . ($ideal !== '' ? "\n\nIdeal for: " . $ideal : '');
            $f['intro_btn_text'] = $cta;
            $items['intro_strip'] = $badge !== '' ? [['icon' => $row['badge_icon'] ?? '', 'text' => $badge]] : [];
            if ($includes) {
                $f['hl1_show'] = '1'; $f['hl1_style'] = 'list'; $f['hl1_side'] = 'left'; $f['hl1_heading'] = 'Includes';
                $items['hl1_items'] = $check($includes);
            }
            if ($benefits) {
                $f['hl2_show'] = '1'; $f['hl2_style'] = 'list'; $f['hl2_side'] = 'right'; $f['hl2_heading'] = 'Benefits';
                $items['hl2_items'] = $check($benefits);
            }
        } else {
            $f['intro_show'] = '0';
            $f['hl1_show'] = '1'; $f['hl1_style'] = 'list';
            $f['hl1_side'] = $layout === 'split-right' ? 'right' : 'left';
            $f['hl1_eyebrow'] = $badge;
            $f['hl1_heading'] = $row['title'];
            $f['hl1_text'] = $intro . ($ideal !== '' ? "\n\nIdeal for: " . $ideal : '');
            $f['hl1_image'] = $img;
            $f['hl1_btn_text'] = $cta; $f['hl1_btn_link'] = '/contact-us#enquiry';
            $items['hl1_items'] = $check($includes);
            if ($benefits) {
                $f['hl2_show'] = '1'; $f['hl2_style'] = 'list'; $f['hl2_side'] = 'right'; $f['hl2_heading'] = 'Benefits';
                $items['hl2_items'] = $check($benefits);
            }
        }
        return [$f, $items];
    }

    // ------------------------------------------------------------------
    // Public (front-end) reads
    // ------------------------------------------------------------------
    public function findBySlug($slug, $includeDrafts = false) {
        if (self::$offline) { return null; }
        $sql = 'SELECT * FROM services WHERE slug = :s AND deleted_at IS NULL' . ($includeDrafts ? '' : " AND status = 'published'");
        $this->db->query($sql);
        $this->db->bind(':s', $slug);
        $row = $this->db->single();
        return $row ? $this->hydrate((array)$row) : null;
    }

    // A published service whose slug was changed: returns its current record.
    public function findByOldSlug($slug) {
        if (self::$offline) { return null; }
        $this->db->query("SELECT * FROM services WHERE old_slugs LIKE :l AND deleted_at IS NULL AND status = 'published' LIMIT 1");
        $this->db->bind(':l', '%"' . str_replace(['%', '_'], ['\\%', '\\_'], $slug) . '"%');
        $row = $this->db->single();
        if (!$row) { return null; }
        $svc = $this->hydrate((array)$row);
        return in_array($slug, $svc['old_slugs'], true) ? $svc : null;
    }

    private function publishedRows() {
        if (self::$offline) {
            $rows = [];
            foreach (self::defaults() as $i => $row) {
                if (($row['status'] ?? 'draft') !== 'published') { continue; }
                $rows[] = $this->hydrate($row + ['id' => $i + 1]);
            }
            return $rows;
        }
        $this->db->query("SELECT * FROM services WHERE status = 'published' AND deleted_at IS NULL ORDER BY sort_order ASC, id ASC");
        return array_map(function ($r) { return $this->hydrate((array)$r); }, $this->db->resultSet());
    }

    // Services shown on the /services page, grouped by category (empty groups omitted).
    public function listingGrouped() {
        $groups = [];
        foreach (self::categories() as $key => $meta) {
            $groups[$key] = $meta + ['key' => $key, 'items' => []];
        }
        foreach ($this->publishedRows() as $svc) {
            if (!$svc['show_on_listing'] || !isset($groups[$svc['category']])) { continue; }
            $groups[$svc['category']]['items'][] = $svc;
        }
        return array_values(array_filter($groups, function ($g) { return !empty($g['items']); }));
    }

    public function navItems() {
        return array_values(array_filter($this->publishedRows(), function ($s) { return $s['show_in_nav']; }));
    }

    // "Explore other services": other published services that have a card.
    public function related($excludeId, $limit = 6) {
        $out = [];
        foreach ($this->publishedRows() as $svc) {
            if ($svc['id'] == $excludeId || !$svc['show_on_listing']) { continue; }
            $out[] = $svc;
            if (count($out) >= $limit) { break; }
        }
        return $out;
    }

    // Everything the shared template needs to render one service page.
    public function pageContent($id) {
        $content = new Home_content_model();
        $content->setPage(self::pageKey($id));
        $fields = $content->getAllFields();
        $defaults = Service_schema::newServiceFields('');
        $f = array_merge(array_fill_keys(array_keys($defaults), ''), $fields);
        return ['f' => $f, 'items' => $content->getAllItems()];
    }

    // ------------------------------------------------------------------
    // Admin reads
    // ------------------------------------------------------------------
    public function counts() {
        $c = ['all' => 0, 'published' => 0, 'draft' => 0, 'trash' => 0];
        if (self::$offline) { return $c; }
        $this->db->query("SELECT
            SUM(deleted_at IS NULL) AS live,
            SUM(deleted_at IS NULL AND status = 'published') AS pub,
            SUM(deleted_at IS NULL AND status = 'draft') AS dr,
            SUM(deleted_at IS NOT NULL) AS tr
            FROM services");
        $r = $this->db->single();
        if ($r) {
            $c = ['all' => (int)$r->live, 'published' => (int)$r->pub, 'draft' => (int)$r->dr, 'trash' => (int)$r->tr];
        }
        return $c;
    }

    public function adminList($trash = false) {
        if (self::$offline) { return []; }
        $this->db->query('SELECT * FROM services WHERE deleted_at IS ' . ($trash ? 'NOT NULL ORDER BY deleted_at DESC' : 'NULL ORDER BY sort_order ASC, id ASC'));
        return array_map(function ($r) { return $this->hydrate((array)$r); }, $this->db->resultSet());
    }

    // Live (not trashed) services, for the admin sidebar and the "copy from" list.
    public static function sidebarList() {
        try {
            $m = new self();
            return $m->adminList(false);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function get($id) {
        if (self::$offline) { return null; }
        $this->db->query('SELECT * FROM services WHERE id = :id');
        $this->db->bind(':id', (int)$id);
        $row = $this->db->single();
        return $row ? $this->hydrate((array)$row) : null;
    }

    // ------------------------------------------------------------------
    // Validation of the settings form (and of the "Add service" form)
    // ------------------------------------------------------------------
    public static function slugify($text) {
        $text = strtolower(trim($text));
        if (function_exists('iconv')) {
            $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
            if ($t !== false) { $text = strtolower($t); }
        }
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim(substr(trim($text, '-'), 0, 80), '-');
    }

    private static function text($value, $max, $label, array &$errors, $required = false) {
        $value = trim((string)$value);
        if ($required && $value === '') { $errors[] = "$label is required."; }
        if (mb_strlen($value) > $max) {
            $errors[] = "$label can be at most $max characters.";
            $value = mb_substr($value, 0, $max);
        }
        return $value;
    }

    private static function imagePath($value, $label, array &$errors) {
        $value = trim((string)$value);
        if ($value === '') { return ''; }
        if (!preg_match('#^/images/[A-Za-z0-9_./ -]+$#', $value) || strpos($value, '..') !== false) {
            $errors[] = "$label: invalid image.";
            return '';
        }
        return $value;
    }

    private static function iconClass($value, $label, array &$errors) {
        $value = trim((string)$value);
        if ($value === '') { return ''; }
        if (!preg_match('/^fa-[a-z0-9-]{1,40}$/', $value)) {
            $errors[] = "$label: use a Font Awesome class such as fa-wifi.";
            return '';
        }
        return $value;
    }

    // Name + address + category: shared by "Add service" and the settings screen.
    public function validateIdentity($title, $slugInput, $category, $ignoreId, array &$errors) {
        $title = self::text($title, 150, 'Service name', $errors, true);
        $slug = self::slugify((string)$slugInput);
        if ($slug === '') { $slug = self::slugify($title); }
        if (strlen($slug) < 2) {
            $errors[] = 'Page address: enter letters, numbers or hyphens (at least 2 characters).';
        } elseif (in_array($slug, self::RESERVED_SLUGS, true)) {
            $errors[] = "Page address: \"$slug\" is reserved. Choose another.";
        } elseif (!$this->slugAvailable($slug, $ignoreId)) {
            $errors[] = "Page address: \"$slug\" is already used by another service.";
        }
        if (!isset(self::categories()[$category])) {
            $errors[] = 'Please choose a category.';
            $category = 'workspaces';
        }
        return [$title, $slug, $category];
    }

    // Cleans the settings form. Returns [clean, errors].
    public function validateSettings(array $in, array $existing, array $contentFields) {
        $errors = [];
        [$title, $slug, $category] = $this->validateIdentity($in['title'] ?? '', $in['slug'] ?? '', $in['category'] ?? '', $existing['id'], $errors);

        $d = ['title' => $title, 'slug' => $slug, 'category' => $category];
        $d['status'] = ($in['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $d['show_on_listing'] = !empty($in['show_on_listing']) ? 1 : 0;
        $d['show_in_nav'] = !empty($in['show_in_nav']) ? 1 : 0;
        $d['card_image'] = self::imagePath($in['card_image'] ?? '', 'Card photo', $errors);
        $d['card_badge'] = self::text($in['card_badge'] ?? '', 80, 'Badge', $errors);
        $d['card_summary'] = self::text($in['card_summary'] ?? '', 300, 'Short description', $errors);
        $d['card_cta'] = self::text($in['card_cta'] ?? '', 60, 'Link text', $errors);
        $features = [];
        foreach (preg_split('/\r\n|\r|\n/', (string)($in['card_features'] ?? '')) as $line) {
            $line = trim($line);
            if ($line !== '') { $features[] = mb_substr($line, 0, 120); }
        }
        if (count($features) > 6) {
            $errors[] = 'Highlights: at most 6 lines are allowed.';
            $features = array_slice($features, 0, 6);
        }
        $d['card_features'] = $features;
        $d['nav_label'] = self::text($in['nav_label'] ?? '', 60, 'Menu label', $errors);
        $d['nav_icon'] = self::iconClass($in['nav_icon'] ?? '', 'Menu icon', $errors);
        $d['nav_blurb'] = self::text($in['nav_blurb'] ?? '', 80, 'Menu text', $errors);
        $d['meta_title'] = self::text($in['meta_title'] ?? '', 120, 'Google title', $errors);
        $d['meta_description'] = self::text($in['meta_description'] ?? '', 255, 'Google description', $errors);

        if ($d['show_in_nav'] && $d['nav_icon'] === '') { $d['nav_icon'] = 'fa-briefcase'; }
        if ($d['status'] === 'published' && $d['show_on_listing'] && $d['card_summary'] === '') {
            $errors[] = 'A service shown on the Services page needs a short description (or switch that option off).';
        }
        if ($d['status'] === 'published' && trim((string)($contentFields['banner_heading'] ?? '')) === '') {
            $errors[] = 'Add a main heading in "Page Banner" before publishing, or keep this service as a draft.';
        }
        return [$d, $errors];
    }

    public function slugAvailable($slug, $ignoreId = 0) {
        if (self::$offline) { return true; }
        $this->db->query('SELECT id FROM services WHERE slug = :s AND id <> :id');
        $this->db->bind(':s', $slug);
        $this->db->bind(':id', (int)$ignoreId);
        return !$this->db->single();
    }

    // ------------------------------------------------------------------
    // Writes
    // ------------------------------------------------------------------
    private static function withDefaults(array $row) {
        return $row + [
            'old_slugs' => [], 'status' => 'draft', 'category' => 'workspaces', 'sort_order' => 0, 'show_on_listing' => 1, 'show_in_nav' => 0,
            'card_image' => '', 'card_badge' => '', 'card_summary' => '', 'card_features' => [], 'card_cta' => '',
            'nav_label' => '', 'nav_icon' => '', 'nav_blurb' => '', 'meta_title' => '', 'meta_description' => '',
        ];
    }

    private static $columns = [
        'slug', 'old_slugs', 'title', 'category', 'status', 'sort_order', 'show_on_listing', 'show_in_nav',
        'card_image', 'card_badge', 'card_summary', 'card_features', 'card_cta', 'nav_label', 'nav_icon', 'nav_blurb', 'meta_title', 'meta_description',
    ];
    private static $jsonColumns = ['card_features', 'old_slugs'];

    private function insertRow(array $row, $ignoreDuplicate = false) {
        $row = self::withDefaults($row);
        $cols = self::$columns;
        $this->db->query(($ignoreDuplicate ? 'INSERT IGNORE' : 'INSERT') . ' INTO services (' . implode(',', $cols) . ') VALUES (' . implode(',', array_map(function ($c) { return ':' . $c; }, $cols)) . ')');
        foreach ($cols as $c) {
            $v = $row[$c];
            if (in_array($c, self::$jsonColumns, true)) { $v = json_encode(array_values((array)$v), JSON_UNESCAPED_UNICODE); }
            $this->db->bind(':' . $c, $v);
        }
        $this->db->execute();
        return (int)$this->db->lastInsertId();
    }

    // Creates a draft service with the shared template: blank, or a copy of another service's page.
    public function create($title, $slug, $category, $copyFromId = 0) {
        if (self::$offline) { throw new RuntimeException('Database unavailable'); }
        $this->db->query('SELECT COALESCE(MAX(sort_order), 0) + 10 AS n FROM services');
        $order = (int)$this->db->single()->n;

        $copy = $copyFromId ? $this->get($copyFromId) : null;
        $id = $this->insertRow([
            'slug' => $slug, 'title' => $title, 'category' => $category, 'status' => 'draft', 'sort_order' => $order,
            'show_on_listing' => 1, 'show_in_nav' => 0,
            'card_image' => $copy ? $copy['card_image'] : '', 'card_summary' => '', 'card_features' => [],
        ]);

        $page = self::pageKey($id);
        if ($copy) {
            $src = new Home_content_model();
            $src->setPage(self::pageKey($copy['id']));
            $fields = $src->getAllFields();
            $items = [];
            foreach ($src->getAllItems() as $section => $rows) { $items[$section] = $rows; }
            unset($fields['_seeded']);
            $fields['banner_heading'] = $title;
            $this->writeContent($page, $fields, $items);
        } else {
            $this->writeContent($page, Service_schema::newServiceFields($title), []);
        }
        return $id;
    }

    public function saveSettings($id, array $d) {
        if (self::$offline) { throw new RuntimeException('Database unavailable'); }
        $existing = $this->get($id);
        if (!$existing) { throw new RuntimeException('Service not found'); }

        // keep old URLs alive: remember the previous slug as a redirect
        $old = $existing['old_slugs'];
        if ($existing['slug'] !== $d['slug']) { $old[] = $existing['slug']; }
        $d['old_slugs'] = array_values(array_diff(array_unique($old), [$d['slug']]));

        $cols = array_values(array_diff(self::$columns, ['sort_order']));
        $this->db->query('UPDATE services SET ' . implode(', ', array_map(function ($c) { return "$c = :$c"; }, $cols)) . ' WHERE id = :id');
        foreach ($cols as $c) {
            $v = $d[$c];
            if (in_array($c, self::$jsonColumns, true)) { $v = json_encode(array_values((array)$v), JSON_UNESCAPED_UNICODE); }
            $this->db->bind(':' . $c, $v);
        }
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // Allow-listed single-flag updates used by the list screen.
    public function setFlag($id, $field, $value) {
        $allowed = ['status' => ['published', 'draft'], 'show_in_nav' => [0, 1], 'show_on_listing' => [0, 1]];
        if (!isset($allowed[$field]) || !in_array($value, $allowed[$field], true)) { return false; }
        $this->db->query("UPDATE services SET $field = :v WHERE id = :id AND deleted_at IS NULL");
        $this->db->bind(':v', $value);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    public function trash($id) {
        $this->db->query('UPDATE services SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL');
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // Restores into Draft so a restored page never goes live by surprise.
    public function restore($id) {
        $this->db->query("UPDATE services SET deleted_at = NULL, status = 'draft' WHERE id = :id AND deleted_at IS NOT NULL");
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // Permanent delete (only for something already in the Trash): the service and all of its content.
    public function purge($id) {
        $this->db->query('DELETE FROM services WHERE id = :id AND deleted_at IS NOT NULL');
        $this->db->bind(':id', (int)$id);
        $this->db->execute();
        if ($this->db->rowCount() < 1) { return false; }

        $content = new Home_content_model();
        $content->setPage(self::pageKey($id));
        $content->deletePage(true);
        return true;
    }

    // Moves a service up/down within its category by swapping positions, keeping the overall
    // sort values (and so the menu order across categories) consistent.
    public function move($id, $direction) {
        $svc = $this->get($id);
        if (!$svc || $svc['deleted_at']) { return false; }

        $this->db->query('SELECT id, sort_order FROM services WHERE category = :c AND deleted_at IS NULL ORDER BY sort_order ASC, id ASC');
        $this->db->bind(':c', $svc['category']);
        $rows = $this->db->resultSet();
        $ids = array_map(function ($r) { return (int)$r->id; }, $rows);
        $slots = array_map(function ($r) { return (int)$r->sort_order; }, $rows);
        sort($slots);
        if (count(array_unique($slots)) !== count($slots)) {
            $base = $slots ? min($slots) : 0;
            foreach ($slots as $i => $_) { $slots[$i] = $base + $i; }
        }

        $pos = array_search((int)$id, $ids, true);
        $to = $direction === 'up' ? $pos - 1 : $pos + 1;
        if ($pos === false || $to < 0 || $to >= count($ids)) { return false; }
        [$ids[$pos], $ids[$to]] = [$ids[$to], $ids[$pos]];

        $this->db->beginTransaction();
        try {
            foreach ($ids as $i => $sid) {
                $this->db->query('UPDATE services SET sort_order = :o WHERE id = :id');
                $this->db->bind(':o', $slots[$i]);
                $this->db->bind(':id', $sid);
                $this->db->execute();
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
        return true;
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------
    private function hydrate(array $r) {
        foreach (self::$jsonColumns as $c) {
            $v = $r[$c] ?? [];
            if (is_string($v)) { $v = json_decode($v, true); }
            $r[$c] = is_array($v) ? array_values($v) : [];
        }
        if (!isset(self::categories()[$r['category'] ?? ''])) { $r['category'] = 'workspaces'; }
        $r['id'] = (int)($r['id'] ?? 0);
        $r['show_on_listing'] = (int)($r['show_on_listing'] ?? 1);
        $r['show_in_nav'] = (int)($r['show_in_nav'] ?? 0);
        $r['sort_order'] = (int)($r['sort_order'] ?? 0);
        $r += ['deleted_at' => null, 'status' => 'draft', 'category' => 'workspaces', 'card_image' => '', 'card_badge' => '', 'card_summary' => '', 'card_cta' => '',
               'nav_label' => '', 'nav_icon' => '', 'nav_blurb' => '', 'meta_title' => '', 'meta_description' => ''];
        return $r;
    }

    // ------------------------------------------------------------------
    // Built-in services. Their page content for the four Coimbatore pages comes from
    // database/services_seed.json; the simpler ones are converted from the fields below.
    // ------------------------------------------------------------------
    public static function defaults() {
        $img = function ($f) { return '/images/' . $f; };
        $hot = $img('hot_desk_enhanced_1791353156473.jpg');
        $ded = $img('dedicated_desk_enhanced_1791353327423.jpg');
        $prv = $img('private_office_enhanced_1791353356099.jpg');
        $mtg = $img('meeting_room_enhanced_1791353342929.jpg');

        return [
            ['slug' => 'hot-desk', 'title' => 'Hot Desk', 'category' => 'workspaces', 'status' => 'published', 'sort_order' => 10, 'show_in_nav' => 1,
             'card_image' => $hot, 'card_badge' => 'Plan: Weekly', 'card_summary' => 'Perfect for freelancers and remote workers who need a vibrant and flexible workspace every day.',
             'card_features' => ['Any open desk', 'High-speed Wi-Fi', '24/7 access'], 'card_cta' => 'Enquire about Hot Desk',
             'nav_label' => 'Hot Desk', 'nav_icon' => 'fa-chair', 'nav_blurb' => 'Flexible seating, pay as you go',
             'banner_image' => $hot, 'layout' => 'split-left', 'detail_image' => $hot,
             'detail_intro' => 'Perfect for freelancers and remote workers who need a vibrant and flexible workspace every day.',
             'badge_icon' => 'fa-calendar-alt', 'badge_text' => 'Plan: Weekly', 'ideal_for' => 'Freelancers, remote workers, students',
             'includes' => json_encode(['Any open desk', 'High-speed Wi-Fi', '24/7 access', 'Cafeteria and kitchen']), 'cta_label' => 'Enquire Now'],

            ['slug' => 'dedicated-desk', 'title' => 'Dedicated Desk', 'category' => 'workspaces', 'status' => 'published', 'sort_order' => 20, 'show_in_nav' => 1,
             'card_image' => $ded, 'card_badge' => 'Plan: Yearly', 'card_summary' => 'Your own reserved workstation in a collaborative environment with professional amenities.',
             'card_features' => ['Reserved desk and locker', '24/7 access and Wi-Fi', '2 hours free boardroom use per month'], 'card_cta' => 'Enquire about Dedicated Desk',
             'nav_label' => 'Dedicated Desk', 'nav_icon' => 'fa-desktop', 'nav_blurb' => 'Your own desk, every day',
             'banner_image' => $ded, 'layout' => 'split-right', 'detail_image' => $ded,
             'detail_intro' => 'Your own reserved workstation in a collaborative environment with professional amenities.',
             'badge_icon' => 'fa-calendar-alt', 'badge_text' => 'Plan: Yearly', 'ideal_for' => 'Professionals needing a fixed seat',
             'includes' => json_encode(['Reserved desk', 'Locker', '24/7 access', 'Wi-Fi', '2 hours free boardroom use per month']), 'cta_label' => 'Enquire Now'],

            ['slug' => 'private-office', 'title' => 'Private Office', 'category' => 'workspaces', 'status' => 'published', 'sort_order' => 30, 'show_in_nav' => 1,
             'card_image' => $prv, 'card_badge' => 'Plan: Yearly', 'card_summary' => 'Secure and fully furnished office spaces ideal for startups, teams, and established businesses.',
             'card_features' => ['Furnished lockable office', '6 hours free boardroom use per month', 'Reception services and business address'], 'card_cta' => 'Enquire about Private Office',
             'nav_label' => 'Private Office', 'nav_icon' => 'fa-door-closed', 'nav_blurb' => 'Lockable offices for teams',
             'banner_image' => $prv, 'layout' => 'split-left', 'detail_image' => $prv,
             'detail_intro' => 'Secure and fully furnished office spaces ideal for startups, teams, and established businesses.',
             'badge_icon' => 'fa-calendar-alt', 'badge_text' => 'Plan: Yearly', 'ideal_for' => 'Startups, teams, corporates (up to 160 employees across coworking and private offices)',
             'includes' => json_encode(['Furnished lockable office', '6 hours free boardroom use per month', 'Reception services', 'Business address', 'Customisable layout']), 'cta_label' => 'Enquire Now'],

            ['slug' => 'meeting-room', 'title' => 'Meeting Room', 'category' => 'meetings-events', 'status' => 'published', 'sort_order' => 40, 'show_in_nav' => 1,
             'card_image' => $mtg, 'card_badge' => 'Booking: Hourly – maximum 2 hours', 'card_summary' => 'Host client meetings, team discussions, and presentations in a polished and well-equipped setting.',
             'card_features' => ['Audio-visual equipment and Wi-Fi', 'AC and refreshment service', 'Maximum 2 hours'], 'card_cta' => 'Check availability',
             'nav_label' => 'Meeting Room', 'nav_icon' => 'fa-users', 'nav_blurb' => 'Hourly rooms with AV setup',
             'banner_image' => $mtg, 'layout' => 'split-right', 'detail_image' => $mtg,
             'detail_intro' => 'Host client meetings, team discussions, and presentations in a polished and well-equipped setting.',
             'badge_icon' => 'fa-clock', 'badge_text' => 'Booking: Hourly – maximum 2 hours',
             'includes' => json_encode(['Audio-visual equipment', 'Wi-Fi', 'AC', 'Refreshment service']), 'cta_label' => 'Enquire Now'],

            ['slug' => 'event-spaces', 'title' => 'Event Spaces', 'category' => 'meetings-events', 'status' => 'published', 'sort_order' => 50, 'show_in_nav' => 1,
             'card_image' => $img('event_spaces.png'), 'card_badge' => 'Custom setup', 'card_summary' => 'Space for workshops, seminars, training sessions and meetups, customisable to your event.',
             'card_features' => ['Workshops and seminars', 'Training sessions and meetups', 'Flexible, customisable layout'], 'card_cta' => 'Plan an event',
             'nav_label' => 'Event Spaces', 'nav_icon' => 'fa-calendar-days', 'nav_blurb' => 'Workshops, seminars, meetups',
             'banner_image' => $img('gallery-building-exterior.png'), 'layout' => 'split-left', 'detail_image' => $img('event_spaces.png'),
             'detail_intro' => 'Space for workshops, seminars, training sessions and meetups, customisable to your event.',
             'includes' => json_encode(['Audio-Visual & Presentation Setup', 'Flexible Seating Configurations', 'High-Speed Wi-Fi & Power Backup', 'Hospitality & Event Support']),
             'cta_label' => 'Enquire Now'],

            ['slug' => 'coworking-space-coimbatore', 'title' => 'Co Working Space',
             'category' => 'workspaces', 'status' => 'published', 'sort_order' => 60, 'show_in_nav' => 1,
             'card_image' => $img('coworking_main_1791350864870.jpg'), 'card_badge' => 'Flexible plans', 'card_summary' => 'Hot desks, dedicated desks, private offices and meeting rooms in Saravanampatti, with Wi-Fi, parking and a food court.',
             'card_features' => ['Hot desks to private offices', 'High-speed Wi-Fi and parking', 'Food court and game area'], 'card_cta' => 'Explore Co Working Space',
             'nav_label' => 'Co Working Space', 'nav_icon' => 'fa-city', 'nav_blurb' => 'Guide to coworking in Coimbatore',
             'meta_title' => 'Coworking Space in Coimbatore - Workiify Spaces',
             'meta_description' => 'Workiify Spaces offers a flexible coworking space in Coimbatore at Saravanampatti, near KGiSL Campus - hot desks, dedicated desks, private offices and meeting rooms.'],

            ['slug' => 'shared-workspace-in-coimbatore', 'title' => 'Shared Workspace',
             'category' => 'workspaces', 'status' => 'published', 'sort_order' => 70, 'show_in_nav' => 1,
             'card_image' => $img('gallery-shared-workspace-floor.png'), 'card_badge' => '24/7 access', 'card_summary' => 'A professional shared workspace for freelancers, startups and growing teams, open round the clock.',
             'card_features' => ['24/7 access', 'Hot, dedicated and private desks', 'Celebration hall for up to 60 people'], 'card_cta' => 'Explore Shared Workspace',
             'nav_label' => 'Shared Workspace', 'nav_icon' => 'fa-people-roof', 'nav_blurb' => '24/7 access in Saravanampatti',
             'meta_title' => 'Best Shared Workspace in Coimbatore | Workiify',
             'meta_description' => 'Find a professional workspace in Saravanampatti with flexible options, modern facilities, and a convenient location near KGiSL Campus'],

            ['slug' => 'flexible-office-space-coimbatore', 'title' => 'Flexible Office Space',
             'category' => 'workspaces', 'status' => 'published', 'sort_order' => 80, 'show_in_nav' => 1,
             'card_image' => $prv, 'card_badge' => 'Flexible plans', 'card_summary' => 'Private, managed and shared offices with flexible rental plans that grow with your team.',
             'card_features' => ['Private and managed offices', 'Flexible rental terms', 'Room to scale as you grow'], 'card_cta' => 'Explore Flexible Office Space',
             'nav_label' => 'Flexible Office Space', 'nav_icon' => 'fa-building-user', 'nav_blurb' => 'Private, managed & shared offices',
             'meta_title' => 'Flexible Office Space in Coimbatore | Workiify',
             'meta_description' => 'Find flexible office space in Coimbatore with Workiify. Explore workspace options in Saravanampatti for startups and growing businesses. Enquire today!'],

            ['slug' => 'virtual-office-in-coimbatore', 'title' => 'Virtual Office',
             'category' => 'virtual-day-pass', 'status' => 'published', 'sort_order' => 100, 'show_in_nav' => 1,
             'card_image' => $img('gallery-front-office-reception.png'), 'card_badge' => 'Business address', 'card_summary' => 'A professional business address with mail handling and GST / company registration support.',
             'card_features' => ['Business address in Saravanampatti', 'Mail handling', 'GST and company registration support'], 'card_cta' => 'Explore Virtual Office',
             'nav_label' => 'Virtual Office', 'nav_icon' => 'fa-laptop-house', 'nav_blurb' => 'Business address & registration',
             'meta_title' => 'Virtual Office in Coimbatore | Affordable Business Address',
             'meta_description' => 'Find a virtual office in Coimbatore with a professional business address, flexible plans, mail handling and registration support. Explore our Saravanampatti office today.'],

            ['slug' => 'virtual-office', 'title' => 'Virtual Office', 'category' => 'virtual-day-pass', 'status' => 'published', 'sort_order' => 100, 'show_in_nav' => 0,
             'card_image' => $img('gallery-front-office-reception.png'), 'card_badge' => 'Business address',
             'card_summary' => 'A virtual office lets you work remotely while presenting your business from a well-established, professional business address.',
             'card_features' => ['Business address for GST / company registration', 'Dedicated phone number', "Calls answered in your company's name"], 'card_cta' => 'Get a business address',
             'nav_label' => 'Virtual Office', 'nav_icon' => 'fa-laptop-house', 'nav_blurb' => 'A professional business address',
             'banner_image' => $img('gallery-front-office-reception.png'), 'layout' => 'centered',
             'detail_intro' => 'A virtual office lets you work remotely while presenting your business from a well-established, professional business address.',
             'includes' => json_encode(['Business address (for GST / company registration)', 'Dedicated phone number', "Calls answered in your company's name", 'Messages passed on by email', 'Meeting room access at a small extra fee']),
             'benefits' => json_encode(['Professional image', 'Never miss a client call', 'No commute or lease costs']), 'cta_label' => 'Enquire Now'],

            ['slug' => 'day-pass', 'title' => 'Day Pass', 'category' => 'virtual-day-pass', 'status' => 'published', 'sort_order' => 110, 'show_in_nav' => 0,
             'card_image' => $img('gallery-boardroom.png'), 'card_badge' => 'Conference hall only',
             'card_summary' => 'Use Workiify for a single day without a monthly plan – ideal for freelancers, travellers, or anyone trying the space before joining.',
             'card_features' => ['No monthly commitment', 'Try the space before you join', 'Available in the conference hall'], 'card_cta' => 'Book a day pass',
             'nav_label' => 'Day Pass', 'nav_icon' => 'fa-ticket', 'nav_blurb' => 'Work for a single day',
             'banner_image' => $img('gallery-boardroom.png'), 'layout' => 'centered',
             'detail_intro' => 'Use Workiify for a single day without a monthly plan – ideal for freelancers, travellers, or anyone trying the space before joining. Day Pass is available in the conference hall only.',
             'badge_icon' => 'fa-map-marker-alt', 'badge_text' => 'Available in the conference hall only', 'cta_label' => 'Enquire Now'],
        ];
    }
}
