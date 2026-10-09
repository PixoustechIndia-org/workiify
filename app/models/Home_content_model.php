<?php
// Despite the class name (kept for backward compatibility with the Home
// controller), this model now drives the CMS for any page, not just Home --
// every row in home_fields/home_items is scoped by a `page` column, and
// fieldGroups()/itemSchemas()/navSections() all take a $page argument.
class Home_content_model {
    private $db;
    private $page = 'home';

    public function __construct() {
        $this->db = new Database();
    }

    public function setPage($page) {
        $this->page = $page;
    }

    // Curated FontAwesome icon choices for the admin's visual icon picker --
    // covers every icon already used in seeded/live content plus common
    // extras, so admins pick an icon by sight instead of typing a class name.
    public static function iconChoices() {
        return [
            'fa-wifi', 'fa-parking', 'fa-square-parking', 'fa-utensils', 'fa-mug-hot', 'fa-couch',
            'fa-snowflake', 'fa-broom', 'fa-concierge-bell', 'fa-print', 'fa-video', 'fa-lock',
            'fa-shield-halved', 'fa-clock', 'fa-bolt', 'fa-building', 'fa-briefcase', 'fa-handshake',
            'fa-rocket', 'fa-graduation-cap', 'fa-users', 'fa-user', 'fa-people-group', 'fa-chair',
            'fa-desktop', 'fa-laptop', 'fa-door-closed', 'fa-door-open', 'fa-calendar-days', 'fa-phone',
            'fa-envelope', 'fa-star', 'fa-gamepad', 'fa-champagne-glasses', 'fa-map-marker-alt',
            'fa-map-location-dot', 'fa-list-check', 'fa-chart-line', 'fa-leaf', 'fa-lightbulb',
            'fa-gear', 'fa-headset', 'fa-book', 'fa-camera', 'fa-globe', 'fa-key', 'fa-plug',
            'fa-thumbs-up', 'fa-sliders',
        ];
    }

    // Every CMS-managed page, for the admin sidebar.
    public static function pages() {
        return [
            'home' => 'Home Page',
            'about' => 'About Us',
            'contact' => 'Contact Us',
            'gallery' => 'Gallery',
        ];
    }

    private static function allFieldGroups() {
        return [
            'home' => [
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
            ],
            'about' => [
                'about_banner' => [
                    'label' => 'Page Banner',
                    'fields' => [
                        ['key' => 'about_banner_image', 'label' => 'Background Image', 'type' => 'image'],
                        ['key' => 'about_banner_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'about_banner_tagline', 'label' => 'Tagline', 'type' => 'text', 'maxlength' => 40],
                    ],
                ],
                'about_story' => [
                    'label' => 'Introducing Workiify Section',
                    'fields' => [
                        ['key' => 'about_story_eyebrow', 'label' => 'Small Eyebrow Text', 'type' => 'text', 'maxlength' => 50],
                        ['key' => 'about_story_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'about_story_text', 'label' => 'Paragraph', 'type' => 'textarea', 'maxlength' => 420],
                        ['key' => 'about_story_image', 'label' => 'Image', 'type' => 'image'],
                    ],
                ],
                'about_purpose' => [
                    'label' => 'Mission & Vision Section',
                    'fields' => [
                        ['key' => 'purpose_mission_heading', 'label' => 'Mission Heading', 'type' => 'text', 'maxlength' => 30],
                        ['key' => 'purpose_mission_text', 'label' => 'Mission Text', 'type' => 'textarea', 'maxlength' => 200],
                        ['key' => 'purpose_vision_heading', 'label' => 'Vision Heading', 'type' => 'text', 'maxlength' => 30],
                        ['key' => 'purpose_vision_text', 'label' => 'Vision Text', 'type' => 'textarea', 'maxlength' => 200],
                    ],
                ],
                'about_workspace_solution' => [
                    'label' => 'Workspace Solution Section',
                    'fields' => [
                        ['key' => 'workspace_eyebrow', 'label' => 'Small Eyebrow Text', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'workspace_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 50],
                        ['key' => 'workspace_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 160],
                    ],
                ],
                'about_why_choose' => [
                    'label' => 'Why Choose Workiify Section',
                    'fields' => [
                        ['key' => 'whychoose_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                    ],
                ],
                'about_location' => [
                    'label' => 'Our Location Section',
                    'fields' => [
                        ['key' => 'location_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 30],
                        ['key' => 'location_text', 'label' => 'Paragraph', 'type' => 'textarea', 'maxlength' => 200],
                        ['key' => 'location_image', 'label' => 'Image', 'type' => 'image'],
                    ],
                ],
                'about_community' => [
                    'label' => 'Community Section',
                    'fields' => [
                        ['key' => 'community_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'community_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 160],
                        ['key' => 'community_quote', 'label' => 'Quote', 'type' => 'textarea', 'maxlength' => 260],
                        ['key' => 'community_note', 'label' => 'Small Note Below Photos', 'type' => 'text', 'maxlength' => 80],
                    ],
                ],
                'about_testimonials' => [
                    'label' => 'Testimonials Section',
                    'fields' => [
                        ['key' => 'about_testimonials_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'about_google_rating', 'label' => 'Google Rating', 'type' => 'text', 'maxlength' => 5],
                    ],
                ],
                'about_client_logos' => [
                    'label' => 'Client Logos Section',
                    'fields' => [
                        ['key' => 'client_logos_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 30],
                    ],
                ],
            ],
            'contact' => [
                'contact_banner' => [
                    'label' => 'Page Banner',
                    'fields' => [
                        ['key' => 'contact_banner_image', 'label' => 'Background Image', 'type' => 'image'],
                        ['key' => 'contact_banner_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'contact_banner_tagline', 'label' => 'Tagline', 'type' => 'text', 'maxlength' => 100],
                    ],
                ],
                'contact_details' => [
                    'label' => 'Contact Details',
                    'fields' => [
                        ['key' => 'contact_address', 'label' => 'Address (HTML allowed, e.g. <br> for a line break)', 'type' => 'textarea', 'maxlength' => 250],
                        ['key' => 'contact_phone_display', 'label' => 'Phone (display text)', 'type' => 'text', 'maxlength' => 20],
                        ['key' => 'contact_phone_link', 'label' => 'Phone (tel: link, e.g. +919655500001)', 'type' => 'text', 'maxlength' => 20],
                        ['key' => 'contact_email_1', 'label' => 'Email 1', 'type' => 'text', 'maxlength' => 60],
                        ['key' => 'contact_email_2', 'label' => 'Email 2 (optional)', 'type' => 'text', 'maxlength' => 60],
                        ['key' => 'contact_access', 'label' => 'Access Text', 'type' => 'text', 'maxlength' => 60],
                        ['key' => 'contact_map_query', 'label' => 'Map Location (search text used for the embedded map and directions link)', 'type' => 'text', 'maxlength' => 150],
                    ],
                ],
                'contact_form' => [
                    'label' => 'Enquiry Form Header',
                    'fields' => [
                        ['key' => 'contact_form_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 50],
                        ['key' => 'contact_form_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'maxlength' => 150],
                    ],
                ],
            ],
            'gallery' => [
                'gallery_banner' => [
                    'label' => 'Page Banner',
                    'fields' => [
                        ['key' => 'gallery_banner_image', 'label' => 'Background Image', 'type' => 'image'],
                        ['key' => 'gallery_banner_eyebrow', 'label' => 'Small Eyebrow Text', 'type' => 'text', 'maxlength' => 30],
                        ['key' => 'gallery_banner_heading', 'label' => 'Heading', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'gallery_banner_tagline', 'label' => 'Tagline', 'type' => 'textarea', 'maxlength' => 150],
                    ],
                ],
            ],
        ];
    }

    private static function allItemSchemas() {
        return [
            'home' => [
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
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'maxlength' => 30],
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
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'maxlength' => 30],
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
            ],
            'about' => [
                'workspace_tiles' => [
                    'label' => 'Workspace Solution Tiles',
                    'minItems' => 4,
                    'maxItems' => 4,
                    'fields' => [
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'maxlength' => 30],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text', 'maxlength' => 40],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea', 'maxlength' => 160],
                        ['key' => 'link', 'label' => 'Link URL', 'type' => 'text', 'maxlength' => 60],
                    ],
                ],
                'why_choose_cards' => [
                    'label' => 'Why Choose — Feature Cards',
                    'minItems' => 2,
                    'maxItems' => 2,
                    'fields' => [
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'maxlength' => 30],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text', 'maxlength' => 50],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea', 'maxlength' => 300],
                    ],
                ],
                'why_choose_icons' => [
                    'label' => 'Why Choose — Highlight Icons',
                    'minItems' => 4,
                    'maxItems' => 4,
                    'fields' => [
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'maxlength' => 30],
                        ['key' => 'text', 'label' => 'Label', 'type' => 'text', 'maxlength' => 60],
                    ],
                ],
                'community_photos' => [
                    'label' => 'Community Photo Strip',
                    'minItems' => 4,
                    'maxItems' => 4,
                    'fields' => [
                        ['key' => 'image', 'label' => 'Image', 'type' => 'image'],
                        ['key' => 'alt', 'label' => 'Alt Text / Caption', 'type' => 'text', 'maxlength' => 50],
                    ],
                ],
                'about_testimonials_items' => [
                    'label' => 'Testimonials',
                    'minItems' => 2,
                    'maxItems' => 6,
                    'fields' => [
                        ['key' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'maxlength' => 220],
                        ['key' => 'author', 'label' => 'Author', 'type' => 'text', 'maxlength' => 50],
                    ],
                ],
                'client_logos' => [
                    'label' => 'Client Logos',
                    'minItems' => 2,
                    'maxItems' => 10,
                    'fields' => [
                        ['key' => 'image', 'label' => 'Logo Image', 'type' => 'image'],
                        ['key' => 'alt', 'label' => 'Alt Text (client name)', 'type' => 'text', 'maxlength' => 50],
                    ],
                ],
            ],
            'gallery' => [
                'gallery_photos' => [
                    'label' => 'Gallery Photos',
                    'minItems' => 1,
                    'maxItems' => 60,
                    'fields' => [
                        ['key' => 'image', 'label' => 'Image', 'type' => 'image'],
                        ['key' => 'caption', 'label' => 'Caption / Alt Text', 'type' => 'text', 'maxlength' => 60],
                        ['key' => 'category', 'label' => 'Category (workspaces / meeting-rooms / common-areas / events)', 'type' => 'text', 'maxlength' => 30],
                    ],
                ],
            ],
        ];
    }

    private static function allNavSections() {
        return [
            'home' => [
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
            ],
            'about' => [
                ['key' => 'banner', 'label' => 'Page Banner', 'fields' => 'about_banner'],
                ['key' => 'our-story', 'label' => 'Introducing Workiify', 'fields' => 'about_story'],
                ['key' => 'mission-vision', 'label' => 'Mission & Vision', 'fields' => 'about_purpose'],
                ['key' => 'workspace-solution', 'label' => 'Workspace Solution', 'fields' => 'about_workspace_solution', 'items' => 'workspace_tiles'],
                ['key' => 'why-choose', 'label' => 'Why Choose Workiify', 'fields' => 'about_why_choose', 'items' => 'why_choose_cards'],
                ['key' => 'why-choose-icons', 'label' => 'Why Choose — Icons', 'items' => 'why_choose_icons'],
                ['key' => 'location', 'label' => 'Our Location', 'fields' => 'about_location'],
                ['key' => 'community', 'label' => 'Community', 'fields' => 'about_community', 'items' => 'community_photos'],
                ['key' => 'about-testimonials', 'label' => 'Testimonials', 'fields' => 'about_testimonials', 'items' => 'about_testimonials_items'],
                ['key' => 'client-logos', 'label' => 'Client Logos', 'fields' => 'about_client_logos', 'items' => 'client_logos'],
            ],
            'contact' => [
                ['key' => 'banner', 'label' => 'Page Banner', 'fields' => 'contact_banner'],
                ['key' => 'details', 'label' => 'Contact Details', 'fields' => 'contact_details'],
                ['key' => 'form', 'label' => 'Enquiry Form Header', 'fields' => 'contact_form'],
            ],
            'gallery' => [
                ['key' => 'banner', 'label' => 'Page Banner', 'fields' => 'gallery_banner'],
                ['key' => 'photos', 'label' => 'Gallery Photos', 'items' => 'gallery_photos'],
            ],
        ];
    }

    public static function fieldGroups($page = 'home') {
        return self::allFieldGroups()[$page] ?? [];
    }

    public static function itemSchemas($page = 'home') {
        return self::allItemSchemas()[$page] ?? [];
    }

    public static function navSections($page = 'home') {
        return self::allNavSections()[$page] ?? [];
    }

    // Reverse lookup: given a repeatable-items key (e.g. 'services') on a given
    // page, find which nav section it belongs to, so item add/edit/delete can
    // redirect back to it.
    public static function navKeyForItems($itemsKey, $page = 'home') {
        foreach (self::navSections($page) as $section) {
            if (($section['items'] ?? null) === $itemsKey) {
                return $section['key'];
            }
        }
        return '';
    }

    public function getField($key, $default = '') {
        $this->db->query('SELECT field_value FROM home_fields WHERE page = :p AND field_key = :k');
        $this->db->bind(':p', $this->page);
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
        $this->db->query('INSERT INTO home_fields (page, field_key, field_value) VALUES (:p, :k, :v)
                           ON DUPLICATE KEY UPDATE field_value = :v2');
        $this->db->bind(':p', $this->page);
        $this->db->bind(':k', $key);
        $this->db->bind(':v', $value);
        $this->db->bind(':v2', $value);
        return $this->db->execute();
    }

    public function getItems($section) {
        $this->db->query('SELECT * FROM home_items WHERE page = :p AND section_key = :s ORDER BY sort_order ASC, id ASC');
        $this->db->bind(':p', $this->page);
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
            'page' => $row->page,
            'section_key' => $row->section_key,
            'sort_order' => $row->sort_order,
            'data' => json_decode($row->data, true) ?: [],
        ];
    }

    public function addItem($section, array $data) {
        $this->db->query('SELECT COALESCE(MAX(sort_order), -1) + 1 AS next_order FROM home_items WHERE page = :p AND section_key = :s');
        $this->db->bind(':p', $this->page);
        $this->db->bind(':s', $section);
        $next = $this->db->single()->next_order;

        $this->db->query('INSERT INTO home_items (page, section_key, sort_order, data) VALUES (:p, :s, :o, :d)');
        $this->db->bind(':p', $this->page);
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

    // ---- Undo history -------------------------------------------------
    // A snapshot of what something looked like right before it was changed,
    // so a bad save can be reverted from the admin's History screen. Kept
    // deliberately simple: one row per save/delete, pruned to the most
    // recent 50 per page so the table doesn't grow without bound.
    private const MAX_REVISIONS_PER_PAGE = 50;

    public function saveRevision($entityType, $sectionKey, $label, array $snapshot, $itemId = null) {
        $this->db->query('INSERT INTO content_revisions (page, entity_type, section_key, item_id, label, snapshot)
                           VALUES (:p, :t, :s, :iid, :l, :sn)');
        $this->db->bind(':p', $this->page);
        $this->db->bind(':t', $entityType);
        $this->db->bind(':s', $sectionKey);
        $this->db->bind(':iid', $itemId);
        $this->db->bind(':l', $label);
        $this->db->bind(':sn', json_encode($snapshot));
        $this->db->execute();

        $this->db->query('DELETE FROM content_revisions WHERE page = :p AND id NOT IN (
            SELECT id FROM (SELECT id FROM content_revisions WHERE page = :p2 ORDER BY created_at DESC, id DESC LIMIT ' . self::MAX_REVISIONS_PER_PAGE . ') AS keep
        )');
        $this->db->bind(':p', $this->page);
        $this->db->bind(':p2', $this->page);
        $this->db->execute();
    }

    // Across every page, newest first -- powers one combined History screen.
    public static function getRecentRevisions($limit = 50) {
        $db = new Database();
        $db->query('SELECT * FROM content_revisions ORDER BY created_at DESC, id DESC LIMIT ' . (int)$limit);
        $rows = $db->resultSet();
        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'id' => $row->id,
                'page' => $row->page,
                'entity_type' => $row->entity_type,
                'section_key' => $row->section_key,
                'item_id' => $row->item_id,
                'label' => $row->label,
                'created_at' => $row->created_at,
            ];
        }
        return $out;
    }

    public static function getRevision($id) {
        $db = new Database();
        $db->query('SELECT * FROM content_revisions WHERE id = :id');
        $db->bind(':id', $id);
        $row = $db->single();
        if (!$row) return null;
        return [
            'id' => $row->id,
            'page' => $row->page,
            'entity_type' => $row->entity_type,
            'section_key' => $row->section_key,
            'item_id' => $row->item_id,
            'label' => $row->label,
            'snapshot' => json_decode($row->snapshot, true) ?: [],
            'created_at' => $row->created_at,
        ];
    }

    private function deleteRevision($id) {
        $this->db->query('DELETE FROM content_revisions WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    // Applies a revision's snapshot back onto the live content, recording
    // the pre-restore state as a new revision first so restoring is itself
    // undoable, then removes the just-applied revision from the list.
    public function restoreRevision(array $revision) {
        if ($revision['entity_type'] === 'fields') {
            $current = $this->getFields(array_keys($revision['snapshot']));
            $this->saveRevision('fields', $revision['section_key'], $revision['label'], $current);
            foreach ($revision['snapshot'] as $key => $value) {
                $this->setField($key, $value);
            }
        } elseif ($revision['entity_type'] === 'item') {
            $existing = $this->getItem((int)$revision['item_id']);
            if ($existing) {
                $this->saveRevision('item', $revision['section_key'], $revision['label'], $existing['data'], $existing['id']);
                $this->updateItem((int)$revision['item_id'], $revision['snapshot']);
            } else {
                // The item was deleted since this revision was taken -- recreate it.
                $this->addItem($revision['section_key'], $revision['snapshot']);
            }
        } elseif ($revision['entity_type'] === 'item_deleted') {
            $this->addItem($revision['section_key'], $revision['snapshot']);
        }
        $this->deleteRevision($revision['id']);
    }
}
