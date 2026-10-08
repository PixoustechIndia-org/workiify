<?php
class Home_content_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Field groups for the singular (non-repeatable) home_fields settings screens.
    // 'maxlength' caps are sized to the space each field actually has in the layout
    // (heading, card, button, etc.) so a long entry can't overflow or wrap badly.
    public static function fieldGroups() {
        return [
            'services' => [
                'label' => 'Our Services Section',
                'fields' => [
                    ['key' => 'services_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'services_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 120],
                    ['key' => 'services_cta_text', 'label' => 'Button Text', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'services_cta_link', 'label' => 'Button Link', 'type' => 'text', 'maxlength' => 60],
                ],
            ],
            'amenities' => [
                'label' => 'Amenities Section',
                'fields' => [
                    ['key' => 'amenities_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'amenities_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 90],
                ],
            ],
            'welcome' => [
                'label' => 'Welcome to Workiify Section',
                'fields' => [
                    ['key' => 'welcome_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'welcome_text', 'label' => 'Paragraph', 'type' => 'textarea', 'maxlength' => 420],
                    ['key' => 'welcome_image', 'label' => 'Image', 'type' => 'image'],
                    ['key' => 'welcome_btn_text', 'label' => 'Button Text', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'welcome_btn_link', 'label' => 'Button Link', 'type' => 'text', 'maxlength' => 80],
                ],
            ],
            'whychoose' => [
                'label' => 'Why Choose Us Section',
                'fields' => [
                    ['key' => 'whychoose_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'whychoose_image', 'label' => 'Image', 'type' => 'image'],
                ],
            ],
            'audience' => [
                'label' => "Who It's For Section",
                'fields' => [
                    ['key' => 'audience_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'audience_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 110],
                ],
            ],
            'testimonials' => [
                'label' => 'Testimonials Section',
                'fields' => [
                    ['key' => 'testimonials_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'google_rating', 'label' => 'Google Rating', 'type' => 'text', 'maxlength' => 5],
                ],
            ],
            'gallery' => [
                'label' => 'Workspace Gallery Section',
                'fields' => [
                    ['key' => 'gallery_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'gallery_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 90],
                    ['key' => 'gallery_cta_text', 'label' => 'Button Text', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'gallery_cta_link', 'label' => 'Button Link', 'type' => 'text', 'maxlength' => 60],
                ],
            ],
            'contact' => [
                'label' => 'Get In Touch Section',
                'fields' => [
                    ['key' => 'contact_badge', 'label' => 'Small Badge Text', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'contact_heading_line1', 'label' => 'Heading Line 1', 'type' => 'text', 'maxlength' => 45],
                    ['key' => 'contact_heading_highlight', 'label' => 'Heading Highlight', 'type' => 'text', 'maxlength' => 50],
                    ['key' => 'contact_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 160],
                    ['key' => 'contact_address', 'label' => 'Address (HTML allowed)', 'type' => 'textarea', 'maxlength' => 120],
                    ['key' => 'contact_phone', 'label' => 'Phone (display)', 'type' => 'text', 'maxlength' => 20],
                    ['key' => 'contact_phone_link', 'label' => 'Phone (tel: link, e.g. +9199...)', 'type' => 'text', 'maxlength' => 20],
                    ['key' => 'contact_email', 'label' => 'Email', 'type' => 'text', 'maxlength' => 60],
                ],
            ],
        ];
    }

    // Schemas for repeatable collections. 'maxlength' caps text so it can't overflow
    // its card/heading, and 'minItems'/'maxItems' cap the collection size so the grid
    // it renders into can't be thrown out of alignment. Where the layout is a fixed
    // grid built for an exact count (Service Cards' 4-column grid, the Workspace
    // Gallery's hand-placed 6-tile masonry), minItems equals maxItems — adding or
    // removing an entry there isn't just "one more card", it breaks the layout, so
    // it's locked rather than merely capped.
    public static function itemSchemas() {
        return [
            'hero' => [
                'label' => 'Hero Slides',
                'minItems' => 1,
                'maxItems' => 5,
                'fields' => [
                    ['key' => 'bg_image', 'label' => 'Background Image', 'type' => 'image'],
                    ['key' => 'heading_line1', 'label' => 'Heading Line 1', 'type' => 'text', 'maxlength' => 45],
                    ['key' => 'heading_highlight', 'label' => 'Heading Highlight (green)', 'type' => 'text', 'maxlength' => 35],
                    ['key' => 'paragraph', 'label' => 'Paragraph', 'type' => 'textarea', 'maxlength' => 200],
                    ['key' => 'btn1_text', 'label' => 'Button 1 Text', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'btn1_link', 'label' => 'Button 1 Link', 'type' => 'text', 'maxlength' => 80],
                    ['key' => 'btn2_text', 'label' => 'Button 2 Text (optional, WhatsApp style)', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'btn2_link', 'label' => 'Button 2 Link (optional)', 'type' => 'text', 'maxlength' => 150],
                ],
            ],
            'counters' => [
                'label' => 'Hero Counters',
                'minItems' => 4,
                'maxItems' => 4,
                'fields' => [
                    ['key' => 'value', 'label' => 'Number Value', 'type' => 'text', 'maxlength' => 8],
                    ['key' => 'suffix', 'label' => 'Suffix (e.g. /7, sq ft)', 'type' => 'text', 'maxlength' => 12],
                    ['key' => 'label', 'label' => 'Label', 'type' => 'text', 'maxlength' => 30],
                ],
            ],
            'services' => [
                'label' => 'Service Cards',
                'minItems' => 4,
                'maxItems' => 4,
                'fields' => [
                    ['key' => 'title', 'label' => 'Title', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'textarea', 'maxlength' => 130],
                    ['key' => 'image', 'label' => 'Image', 'type' => 'image'],
                    ['key' => 'link', 'label' => 'Link URL (e.g. /services/hot-desk)', 'type' => 'text', 'maxlength' => 60],
                ],
            ],
            'amenities' => [
                'label' => 'Amenity Items',
                'minItems' => 6,
                'maxItems' => 16,
                'fields' => [
                    ['key' => 'icon', 'label' => 'Icon (FontAwesome class, e.g. fa-wifi)', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'label', 'label' => 'Label', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'context', 'label' => 'Context (small hover text)', 'type' => 'text', 'maxlength' => 30],
                ],
            ],
            'why_choose_points' => [
                'label' => 'Why Choose Us — Bullet Points',
                'minItems' => 2,
                'maxItems' => 6,
                'fields' => [
                    ['key' => 'text', 'label' => 'Point Text', 'type' => 'text', 'maxlength' => 80],
                ],
            ],
            'audience' => [
                'label' => "Who It's For — Tiles",
                'minItems' => 2,
                'maxItems' => 6,
                'fields' => [
                    ['key' => 'icon', 'label' => 'Icon (FontAwesome class, e.g. fa-rocket)', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'title', 'label' => 'Title', 'type' => 'text', 'maxlength' => 40],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'textarea', 'maxlength' => 100],
                ],
            ],
            'testimonials' => [
                'label' => 'Testimonials',
                'minItems' => 2,
                'maxItems' => 6,
                'fields' => [
                    ['key' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'maxlength' => 220],
                    ['key' => 'author', 'label' => 'Author', 'type' => 'text', 'maxlength' => 50],
                ],
            ],
            'gallery' => [
                'label' => 'Workspace Gallery Images',
                'minItems' => 6,
                'maxItems' => 6,
                'fields' => [
                    ['key' => 'image', 'label' => 'Image', 'type' => 'image'],
                    ['key' => 'alt', 'label' => 'Alt Text / Caption', 'type' => 'text', 'maxlength' => 40],
                ],
            ],
        ];
    }

    // Single source of truth for the admin sidebar: one row per logical content
    // section, pointing at its field-group key and/or its repeatable-items key.
    public static function navSections() {
        return [
            ['key' => 'hero-slides', 'label' => 'Hero Slides', 'items' => 'hero'],
            ['key' => 'hero-counters', 'label' => 'Hero Counters', 'items' => 'counters'],
            ['key' => 'our-services', 'label' => 'Our Services', 'fields' => 'services', 'items' => 'services'],
            ['key' => 'amenities', 'label' => 'Amenities', 'fields' => 'amenities', 'items' => 'amenities'],
            ['key' => 'welcome', 'label' => 'Welcome Section', 'fields' => 'welcome'],
            ['key' => 'why-choose-us', 'label' => 'Why Choose Us', 'fields' => 'whychoose', 'items' => 'why_choose_points'],
            ['key' => 'who-its-for', 'label' => "Who It's For", 'fields' => 'audience', 'items' => 'audience'],
            ['key' => 'testimonials', 'label' => 'Testimonials', 'fields' => 'testimonials', 'items' => 'testimonials'],
            ['key' => 'gallery', 'label' => 'Workspace Gallery', 'fields' => 'gallery', 'items' => 'gallery'],
            ['key' => 'get-in-touch', 'label' => 'Get In Touch', 'fields' => 'contact'],
        ];
    }

    // Reverse lookup: given a repeatable-items key (e.g. 'services'), find which
    // nav section it belongs to, so item add/edit/delete can redirect back to it.
    public static function navKeyForItems($itemsKey) {
        foreach (self::navSections() as $section) {
            if (($section['items'] ?? null) === $itemsKey) {
                return $section['key'];
            }
        }
        return '';
    }

    public function getField($key, $default = '') {
        $this->db->query('SELECT field_value FROM home_fields WHERE field_key = :k');
        $this->db->bind(':k', $key);
        $row = $this->db->single();
        return $row ? $row->field_value : $default;
    }

    public function getFields(array $keys) {
        $out = [];
        foreach ($keys as $k) {
            $out[$k] = $this->getField($k, '');
        }
        return $out;
    }

    public function setField($key, $value) {
        $this->db->query('INSERT INTO home_fields (field_key, field_value) VALUES (:k, :v)
                           ON DUPLICATE KEY UPDATE field_value = :v2');
        $this->db->bind(':k', $key);
        $this->db->bind(':v', $value);
        $this->db->bind(':v2', $value);
        return $this->db->execute();
    }

    public function getItems($section) {
        $this->db->query('SELECT * FROM home_items WHERE section_key = :s ORDER BY sort_order ASC, id ASC');
        $this->db->bind(':s', $section);
        $rows = $this->db->resultSet();
        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'id' => $row->id,
                'sort_order' => $row->sort_order,
                'data' => json_decode($row->data, true) ?: [],
            ];
        }
        return $items;
    }

    public function getItem($id) {
        $this->db->query('SELECT * FROM home_items WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        if (!$row) return null;
        return [
            'id' => $row->id,
            'section_key' => $row->section_key,
            'sort_order' => $row->sort_order,
            'data' => json_decode($row->data, true) ?: [],
        ];
    }

    public function addItem($section, array $data) {
        $this->db->query('SELECT COALESCE(MAX(sort_order), -1) + 1 AS next_order FROM home_items WHERE section_key = :s');
        $this->db->bind(':s', $section);
        $next = $this->db->single()->next_order;

        $this->db->query('INSERT INTO home_items (section_key, sort_order, data) VALUES (:s, :o, :d)');
        $this->db->bind(':s', $section);
        $this->db->bind(':o', $next);
        $this->db->bind(':d', json_encode($data));
        return $this->db->execute();
    }

    public function updateItem($id, array $data) {
        $this->db->query('UPDATE home_items SET data = :d WHERE id = :id');
        $this->db->bind(':d', json_encode($data));
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteItem($id) {
        $this->db->query('DELETE FROM home_items WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
