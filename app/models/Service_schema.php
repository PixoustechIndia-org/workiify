<?php
/*
 * The ONE shared template every service page is built from.
 *
 * A service's content lives in the same tables as the Home / About pages (home_fields and
 * home_items, scoped by page = "svc<id>"), so it is edited with the same admin screens:
 * one screen per section (text/images on top, repeatable entries underneath), with live
 * preview and undo history. This class only describes the sections, in the order they
 * appear on the page, plus the schemas the admin renders its forms from.
 *
 * Every section has a "Show this section" switch, so a service can use as much or as little
 * of the template as it needs.
 */
class Service_schema {
    // Fixed order the sections appear on the page.
    public static function contentSections() {
        return [
            ['key' => 'banner',        'label' => 'Page Banner',               'fields' => 'svc_banner',    'help' => 'The big image and heading at the top of the page.'],
            ['key' => 'introduction',  'label' => 'Introduction',              'fields' => 'svc_intro',     'items' => 'intro_strip', 'help' => 'A short welcome paragraph, a button, and a row of small highlights.'],
            ['key' => 'highlight-1',   'label' => 'Highlight Block 1',         'fields' => 'svc_hl1',       'items' => 'hl1_items',   'help' => 'A photo next to a heading and text, with sliding cards or a row list underneath.'],
            ['key' => 'options',       'label' => 'Options (cards)',           'fields' => 'svc_options',   'items' => 'options',     'help' => 'Cards with a photo, text and a link: for plans, workspace types or related services.'],
            ['key' => 'location',      'label' => 'Location',                  'fields' => 'svc_location',  'items' => 'location_points', 'help' => 'A full-width photo with the address and rotating location highlights.'],
            ['key' => 'feature-tabs',  'label' => 'Feature Tabs',              'fields' => 'svc_tabs',      'items' => 'tabs',        'help' => 'Clickable tabs, each with a photo and text (great for amenities).'],
            ['key' => 'who-its-for',   'label' => "Who It's For",              'fields' => 'svc_audience',  'items' => 'audience',    'help' => 'Photo cards that expand to show text.'],
            ['key' => 'key-points',    'label' => 'Key Points (numbered)',     'fields' => 'svc_keypoints', 'items' => 'keypoints',   'help' => 'Large numbered columns: 01, 02, 03 …'],
            ['key' => 'steps',         'label' => 'Steps',                     'fields' => 'svc_steps',     'items' => 'steps',       'help' => 'A numbered step-by-step guide.'],
            ['key' => 'highlight-2',   'label' => 'Highlight Block 2',         'fields' => 'svc_hl2',       'items' => 'hl2_items',   'help' => 'A second photo-and-text block, same options as Highlight Block 1.'],
            ['key' => 'faq',           'label' => 'FAQ',                       'fields' => 'svc_faq',       'items' => 'faq',         'help' => 'Frequently asked questions (click to expand).'],
            ['key' => 'closing-banner', 'label' => 'Closing Banner',           'fields' => 'svc_cta',       'help' => 'The final call-to-action at the bottom of the page.'],
            ['key' => 'explore-more',  'label' => 'Explore Other Services',    'fields' => 'svc_related',   'help' => 'Automatically lists your other services.'],
        ];
    }

    // The one settings screen: where it lives in the site, its card, menu entry and SEO.
    public static function settingsSection() {
        return ['key' => 'settings', 'label' => 'Service Settings', 'fields' => 'svc_settings', 'settings' => true,
                'help' => 'Name, address, visibility, the card on the Services page, the menu entry and search-engine text.'];
    }

    private static function f($key, $label, $type = 'text', $max = 0, array $extra = []) {
        $f = ['key' => $key, 'label' => $label, 'type' => $type];
        if ($max) { $f['maxlength'] = $max; }
        return $extra + $f;
    }

    private static function toggle($prefix, $label = 'Show this section on the page') {
        return ['key' => $prefix . '_show', 'label' => $label, 'type' => 'toggle'];
    }

    // eyebrow + heading + subtitle: shared by most sections
    private static function heading($p, $eyebrow = true) {
        $out = [];
        if ($eyebrow) { $out[] = self::f($p . '_eyebrow', 'Small label above the heading (also used in the jump menu)', 'text', 40); }
        $out[] = self::f($p . '_heading', 'Heading', 'text', 140);
        $out[] = self::f($p . '_subtitle', 'Text under the heading', 'textarea', 600);
        return $out;
    }

    private static function highlight($p, $name) {
        return [
            'label' => $name,
            'fields' => [
                self::toggle($p),
                self::f($p . '_style', 'Style', 'select', 0, ['options' => ['slides' => 'Sliding cards (one at a time, swipeable)', 'list' => 'Row list (all items visible)']]),
                self::f($p . '_side', 'Photo position', 'select', 0, ['options' => ['left' => 'Photo on the left', 'right' => 'Photo on the right']]),
                self::f($p . '_eyebrow', 'Small label above the heading (also used in the jump menu)', 'text', 40),
                self::f($p . '_heading', 'Heading', 'text', 140),
                self::f($p . '_text', 'Paragraph', 'textarea', 600),
                self::f($p . '_image', 'Photo', 'image'),
                self::f($p . '_badge_big', 'Badge on the photo: large text (optional, e.g. 24/7)', 'text', 24),
                self::f($p . '_badge_small', 'Badge on the photo: small text (optional)', 'text', 40),
                self::f($p . '_btn_text', 'Button text (optional)', 'text', 70),
                self::f($p . '_btn_link', 'Button link (optional, e.g. /contact-us#enquiry)', 'text', 160),
            ],
        ];
    }

    public static function fieldGroups() {
        $cats = [];
        foreach (Service_model::categories() as $k => $c) { $cats[$k] = $c['label']; }

        return [
            'svc_settings' => [
                'label' => 'Service Settings',
                'fields' => [
                    ['key' => '_h_basics', 'label' => 'Basics', 'type' => 'heading'],
                    self::f('title', 'Service name', 'text', 150),
                    self::f('slug', 'Page address (the part after /services/)', 'text', 80),
                    self::f('category', 'Which section of the Services page it appears in', 'select', 0, ['options' => $cats]),
                    self::f('status', 'Status', 'select', 0, ['options' => ['published' => 'Published (visible to everyone)', 'draft' => 'Draft (hidden from visitors)']]),
                    self::f('show_on_listing', 'Show a card for it on the Services page', 'toggle'),
                    self::f('show_in_nav', 'Show it in the site menu (Services dropdown)', 'toggle'),
                    ['key' => '_h_card', 'label' => 'Card on the Services page', 'type' => 'heading'],
                    self::f('card_image', 'Card photo', 'image'),
                    self::f('card_badge', 'Badge (optional, e.g. Plan: Weekly)', 'text', 80),
                    self::f('card_summary', 'Short description', 'textarea', 300),
                    self::f('card_features', 'Highlights: one per line, up to 6 (optional)', 'textarea', 600),
                    self::f('card_cta', 'Link text (default: "Enquire about …")', 'text', 60),
                    ['key' => '_h_menu', 'label' => 'Site menu entry', 'type' => 'heading'],
                    self::f('nav_label', 'Menu label (default: the service name)', 'text', 60),
                    self::f('nav_icon', 'Menu icon', 'icon', 40),
                    self::f('nav_blurb', 'Small text under the label (optional)', 'text', 80),
                    ['key' => '_h_seo', 'label' => 'Search engines (SEO)', 'type' => 'heading'],
                    self::f('meta_title', 'Page title in Google (aim for under 60 characters)', 'text', 120),
                    self::f('meta_description', 'Description in Google (aim for under 160 characters)', 'textarea', 255),
                ],
            ],
            'svc_banner' => [
                'label' => 'Page Banner',
                'fields' => [
                    self::f('banner_image', 'Background photo', 'image'),
                    self::f('banner_heading', 'Main heading (H1)', 'text', 140),
                    self::f('banner_tagline', 'Tagline under the heading (optional)', 'text', 200),
                ],
            ],
            'svc_intro' => [
                'label' => 'Introduction',
                'fields' => [
                    self::toggle('intro'),
                    self::f('intro_heading', 'Section heading (optional)', 'text', 140),
                    self::f('intro_text', 'Paragraph', 'textarea', 800),
                    self::f('intro_btn_text', 'Button text', 'text', 60),
                    self::f('intro_btn_link', 'Button link', 'text', 160),
                ],
            ],
            'svc_hl1' => self::highlight('hl1', 'Highlight Block 1'),
            'svc_options' => ['label' => 'Options (cards)', 'fields' => array_merge([self::toggle('options')], self::heading('options'))],
            'svc_location' => [
                'label' => 'Location',
                'fields' => [
                    self::toggle('location'),
                    self::f('location_heading', 'Heading: first line', 'text', 90),
                    self::f('location_highlight', 'Heading: second line (shown in blue)', 'text', 90),
                    self::f('location_text', 'Paragraph (optional)', 'textarea', 500),
                    self::f('location_address', 'Address', 'textarea', 220),
                    self::f('location_image', 'Background photo', 'image'),
                    self::f('location_btn_text', 'Button text (optional)', 'text', 70),
                    self::f('location_btn_link', 'Button link (optional)', 'text', 160),
                ],
            ],
            'svc_tabs' => ['label' => 'Feature Tabs', 'fields' => array_merge([self::toggle('tabs')], self::heading('tabs'))],
            'svc_audience' => ['label' => "Who It's For", 'fields' => array_merge([self::toggle('audience')], self::heading('audience'))],
            'svc_keypoints' => ['label' => 'Key Points', 'fields' => array_merge([self::toggle('keypoints')], self::heading('keypoints'))],
            'svc_steps' => ['label' => 'Steps', 'fields' => array_merge([self::toggle('steps')], self::heading('steps'))],
            'svc_hl2' => self::highlight('hl2', 'Highlight Block 2'),
            'svc_faq' => [
                'label' => 'FAQ',
                'fields' => [
                    self::toggle('faq'),
                    self::f('faq_heading', 'Heading', 'text', 140),
                    self::f('faq_text', 'Text beside the heading', 'textarea', 240),
                    self::f('faq_btn_text', 'Button text (optional)', 'text', 60),
                    self::f('faq_btn_link', 'Button link (optional)', 'text', 400),
                ],
            ],
            'svc_cta' => [
                'label' => 'Closing Banner',
                'fields' => [
                    self::toggle('cta'),
                    self::f('cta_heading', 'Heading', 'text', 140),
                    self::f('cta_text', 'Text', 'textarea', 300),
                    self::f('cta_btn_text', 'Button text', 'text', 60),
                    self::f('cta_btn_link', 'Button link', 'text', 160),
                    self::f('cta_image', 'Background photo (optional: leave empty for a plain banner)', 'image'),
                ],
            ],
            'svc_related' => [
                'label' => 'Explore Other Services',
                'fields' => [
                    self::toggle('related'),
                    self::f('related_heading', 'Heading', 'text', 80),
                    self::f('related_btn_text', 'Button text', 'text', 40),
                ],
            ],
        ];
    }

    public static function itemSchemas() {
        $icon = self::f('icon', 'Icon', 'icon', 40);
        return [
            'intro_strip' => ['label' => 'Highlights Row', 'minItems' => 0, 'maxItems' => 4, 'fields' => [
                $icon, self::f('text', 'Text', 'text', 50),
            ]],
            'hl1_items' => ['label' => 'Highlight Block 1: Cards / Rows', 'minItems' => 0, 'maxItems' => 8, 'fields' => [
                $icon, self::f('title', 'Title', 'text', 120), self::f('text', 'Text (optional)', 'textarea', 500),
            ]],
            'options' => ['label' => 'Option Cards', 'minItems' => 0, 'maxItems' => 6, 'fields' => [
                self::f('image', 'Photo', 'image'), self::f('tag', 'Small tag on the photo (optional)', 'text', 30),
                self::f('title', 'Title', 'text', 120), self::f('text', 'Text', 'textarea', 500),
                self::f('chips', 'Small labels, separated by commas (optional)', 'text', 120),
                self::f('note', 'Footer note (optional, e.g. Pricing on enquiry)', 'text', 40),
                self::f('link_text', 'Link text', 'text', 40), self::f('link_url', 'Link address (e.g. /services/hot-desk)', 'text', 160),
            ]],
            'location_points' => ['label' => 'Location Highlights (rotate automatically)', 'minItems' => 0, 'maxItems' => 6, 'fields' => [
                $icon, self::f('title', 'Bold lead-in', 'text', 70), self::f('text', 'Text', 'textarea', 260),
            ]],
            'tabs' => ['label' => 'Tabs', 'minItems' => 0, 'maxItems' => 8, 'fields' => [
                $icon, self::f('label', 'Tab label', 'text', 36), self::f('image', 'Photo', 'image'),
                self::f('title', 'Title', 'text', 120), self::f('text', 'Text', 'textarea', 500),
            ]],
            'audience' => ['label' => 'Photo Cards', 'minItems' => 0, 'maxItems' => 5, 'fields' => [
                self::f('image', 'Photo', 'image'), self::f('title', 'Title', 'text', 80), self::f('text', 'Text', 'textarea', 450),
            ]],
            'keypoints' => ['label' => 'Numbered Points', 'minItems' => 0, 'maxItems' => 6, 'fields' => [
                self::f('title', 'Title', 'text', 120), self::f('text', 'Text', 'textarea', 450),
            ]],
            'steps' => ['label' => 'Steps', 'minItems' => 0, 'maxItems' => 6, 'fields' => [
                self::f('title', 'Title', 'text', 120), self::f('text', 'Text', 'textarea', 450),
            ]],
            'hl2_items' => ['label' => 'Highlight Block 2: Cards / Rows', 'minItems' => 0, 'maxItems' => 8, 'fields' => [
                $icon, self::f('title', 'Title', 'text', 120), self::f('text', 'Text (optional)', 'textarea', 500),
            ]],
            'faq' => ['label' => 'Questions', 'minItems' => 0, 'maxItems' => 15, 'fields' => [
                self::f('question', 'Question', 'text', 160), self::f('answer', 'Answer', 'textarea', 900),
            ]],
        ];
    }

    // Every content field key (not the settings), for loading a whole page in one go.
    public static function contentFieldKeys() {
        $groups = self::fieldGroups();
        $keys = [];
        foreach (self::contentSections() as $s) {
            foreach ($groups[$s['fields']]['fields'] as $f) {
                if ($f['type'] !== 'heading') { $keys[] = $f['key']; }
            }
        }
        return $keys;
    }

    // Values a brand-new service starts with: only the banner and introduction are on;
    // everything else is switched off until the admin fills it in.
    public static function newServiceFields($title) {
        $f = [];
        foreach (self::contentFieldKeys() as $k) { $f[$k] = ''; }
        foreach (self::contentFieldKeys() as $k) { if (substr($k, -5) === '_show') { $f[$k] = '0'; } }
        $f['intro_show'] = '1';
        $f['cta_show'] = '1';
        $f['related_show'] = '1';
        $f['banner_heading'] = $title;
        $f['banner_image'] = '/images/services_bg.jpg';
        $f['intro_btn_text'] = 'Enquire Now';
        $f['intro_btn_link'] = '/contact-us#enquiry';
        $f['hl1_style'] = 'slides'; $f['hl1_side'] = 'left';
        $f['hl2_style'] = 'list';   $f['hl2_side'] = 'right';
        $f['faq_btn_text'] = 'Ask a Question';
        $f['faq_btn_link'] = 'https://api.whatsapp.com/send/?phone=919655500001&text=Hi%20Workiify,%20I%20have%20a%20question&type=phone_number&app_absent=0';
        $f['cta_heading'] = 'Interested in ' . $title . '?';
        $f['cta_text'] = 'Get in touch and we will help you find the right option.';
        $f['cta_btn_text'] = 'Enquire Now';
        $f['cta_btn_link'] = '/contact-us#enquiry';
        $f['related_heading'] = 'Explore Other Services';
        $f['related_btn_text'] = 'View All Services';
        return $f;
    }
}
