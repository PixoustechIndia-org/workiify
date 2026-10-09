<?php
/**
 * One-time seed script: populates home_fields / home_items with the CURRENT
 * hardcoded home.php content, and creates the initial admin user.
 * Run once from CLI: php app/database/seed.php
 * Safe to re-run: fields are upserted, items are only inserted if the
 * section is currently empty, and the admin user is only created if missing.
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'workiify';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

function setField(PDO $pdo, $key, $value, $page = 'home') {
    $stmt = $pdo->prepare('INSERT INTO home_fields (page, field_key, field_value) VALUES (:p, :k, :v)
                            ON DUPLICATE KEY UPDATE field_value = :v2');
    $stmt->execute(['p' => $page, 'k' => $key, 'v' => $value, 'v2' => $value]);
}

function seedItems(PDO $pdo, $section, array $rows, $page = 'home') {
    $check = $pdo->prepare('SELECT COUNT(*) FROM home_items WHERE page = :p AND section_key = :s');
    $check->execute(['p' => $page, 's' => $section]);
    if ($check->fetchColumn() > 0) {
        echo "  - $section: already has data, skipped\n";
        return;
    }
    $stmt = $pdo->prepare('INSERT INTO home_items (page, section_key, sort_order, data) VALUES (:p, :s, :o, :d)');
    foreach ($rows as $i => $row) {
        $stmt->execute(['p' => $page, 's' => $section, 'o' => $i, 'd' => json_encode($row)]);
    }
    echo "  - $section: inserted " . count($rows) . " item(s)\n";
}

echo "Seeding home_fields...\n";

$fields = [
    'services_heading' => 'Our Services',
    'services_subtitle' => 'Flexible workspace solutions crafted for productivity, comfort, and business growth.',
    'services_cta_text' => 'View All Services',
    'services_cta_link' => '/services',

    'amenities_heading' => 'AMENITIES',
    'amenities_subtitle' => 'Everything your workspace needs to keep moving.',

    'welcome_heading' => 'Welcome to Workiify',
    'welcome_text' => "Workiify is designed to deliver a premium coworking experience with elegant interiors, flexible membership options, and a professional business atmosphere. Located near the KGISL campus in Coimbatore, our space supports individuals and teams looking for a smart and inspiring environment to work and grow.",
    'welcome_image' => '/images/welcome-office.png',
    'welcome_btn_text' => 'Read More',
    'welcome_btn_link' => '/about-us',

    'whychoose_heading' => 'Why Choose Us',
    'whychoose_image' => '/images/gallery-shared-workspace-floor.png',

    'audience_heading' => "Who It's For",
    'audience_subtitle' => 'Built for freelancers, startups, consultants, and modern businesses.',

    'testimonials_heading' => 'What Our Members Say',
    'google_rating' => '5.0',

    'gallery_heading' => 'Workspace Gallery',
    'gallery_subtitle' => 'A glimpse into the premium Workiify experience.',
    'gallery_cta_text' => 'View More Photos',
    'gallery_cta_link' => '/gallery',

    'contact_badge' => 'GET IN TOUCH',
    'contact_heading_line1' => "We're Here to Help You",
    'contact_heading_highlight' => 'Find Your Perfect Workspace',
    'contact_subtitle' => 'Have questions or want to book a workspace? Send us a message and our team will get back to you shortly.',
    'contact_address' => 'AV Info Tech Park,<br>Saravanampatti, Coimbatore.',
    'contact_phone' => '96555 00001',
    'contact_phone_link' => '+919655500001',
    'contact_email' => 'info@workiify.com',
];

foreach ($fields as $k => $v) {
    setField($pdo, $k, $v);
}
echo '  - upserted ' . count($fields) . " field(s)\n";

echo "Seeding home_items...\n";

seedItems($pdo, 'hero', [
    [
        'bg_image' => '/images/coworking_main_1791350864870.jpg',
        'heading_line1' => 'Premium Coworking Space',
        'heading_highlight' => 'Modern Professionals',
        'paragraph' => 'Experience stylish, flexible, and productive workspaces in Saravanampatti, Coimbatore. Designed for startups, freelancers, remote teams, and growing businesses.',
        'btn1_text' => 'Book an Enquiry',
        'btn1_link' => '/contact-us#enquiry',
        'btn2_text' => 'WhatsApp Us',
        'btn2_link' => 'https://wa.me/919655500001?text=Hi%20Workiify',
    ],
    [
        'bg_image' => '/images/coworking_private_1791350889843.jpg',
        'heading_line1' => 'Private Offices for',
        'heading_highlight' => 'Growing Teams',
        'paragraph' => "Focus and scale your business with fully serviced private suites tailored to your team's needs.",
        'btn1_text' => 'View Private Offices',
        'btn1_link' => '/services',
        'btn2_text' => '',
        'btn2_link' => '',
    ],
    [
        'bg_image' => '/images/coworking_lounge_1791350901804.jpg',
        'heading_line1' => 'Creative Lounges to',
        'heading_highlight' => 'Connect & Collaborate',
        'paragraph' => 'Network with like-minded professionals in our vibrant, high-end community spaces.',
        'btn1_text' => 'Book a Tour',
        'btn1_link' => '/contact-us#enquiry',
        'btn2_text' => '',
        'btn2_link' => '',
    ],
]);

seedItems($pdo, 'counters', [
    ['value' => '24', 'suffix' => '/7', 'label' => 'Access available'],
    ['value' => '5', 'suffix' => '', 'label' => 'Floors'],
    ['value' => '10000', 'suffix' => ' sq ft', 'label' => 'Per floor'],
    ['value' => '120', 'suffix' => '', 'label' => 'Seats per floor'],
]);

seedItems($pdo, 'services', [
    ['title' => 'Hot Desk', 'description' => 'Perfect for freelancers and remote workers who need a vibrant and flexible workspace every day.', 'image' => '/images/hot_desk_enhanced_1791353156473.jpg', 'link' => '/services/hot-desk'],
    ['title' => 'Dedicated Desk', 'description' => 'Your own reserved workstation in a collaborative environment with professional amenities.', 'image' => '/images/dedicated_desk_enhanced_1791353327423.jpg', 'link' => '/services/dedicated-desk'],
    ['title' => 'Private Office', 'description' => 'Secure and fully furnished office spaces ideal for startups, teams, and established businesses.', 'image' => '/images/private_office_enhanced_1791353356099.jpg', 'link' => '/services/private-office'],
    ['title' => 'Meeting Room', 'description' => 'Host client meetings, team discussions, and presentations in a polished and well-equipped setting.', 'image' => '/images/meeting_room_enhanced_1791353342929.jpg', 'link' => '/services/meeting-room'],
]);

seedItems($pdo, 'amenities', [
    ['icon' => 'fa-clock', 'label' => '24/7 Access & Security', 'context' => 'Always active'],
    ['icon' => 'fa-wifi', 'label' => 'High-speed Fibre Wi-Fi', 'context' => '1 Gbps Fiber'],
    ['icon' => 'fa-parking', 'label' => 'Parking (limited)', 'context' => 'Limited spots'],
    ['icon' => 'fa-utensils', 'label' => 'Cafeteria', 'context' => 'Fresh daily'],
    ['icon' => 'fa-mug-hot', 'label' => 'Kitchen with Tea & Coffee', 'context' => 'On tap all day'],
    ['icon' => 'fa-snowflake', 'label' => 'AC', 'context' => 'Auto-regulated'],
    ['icon' => 'fa-bolt', 'label' => 'Power Backup', 'context' => '24 hr coverage'],
    ['icon' => 'fa-broom', 'label' => 'Housekeeping', 'context' => 'Sanitized daily'],
    ['icon' => 'fa-lock', 'label' => 'Lockers', 'context' => 'Secure access'],
    ['icon' => 'fa-print', 'label' => 'Printing & Copying', 'context' => 'Wireless active'],
    ['icon' => 'fa-video', 'label' => 'CCTV', 'context' => '24/7 Monitored'],
    ['icon' => 'fa-concierge-bell', 'label' => 'Reception', 'context' => 'Front desk support'],
]);

seedItems($pdo, 'why_choose_points', [
    ['text' => 'Prime business location'],
    ['text' => 'Premium interior ambiance'],
    ['text' => 'Flexible workspace options'],
    ['text' => 'Ideal for startups, consultants, and remote teams'],
]);

seedItems($pdo, 'audience', [
    ['icon' => 'fa-rocket', 'title' => 'SMEs & Entrepreneurs', 'description' => 'Growing businesses that need flexible, scalable space.'],
    ['icon' => 'fa-building', 'title' => 'Corporates', 'description' => 'Established companies looking for a professional satellite office.'],
    ['icon' => 'fa-briefcase', 'title' => 'Freelancers & Self-Employed', 'description' => 'Independent professionals who need a productive, inspiring base.'],
    ['icon' => 'fa-graduation-cap', 'title' => 'Students', 'description' => 'Students who need a focused space to study and collaborate.'],
]);

seedItems($pdo, 'testimonials', [
    ['quote' => 'Workiify gives our team the right mix of professionalism, comfort, and convenience. The location and ambiance are excellent.', 'author' => 'Ravi K., Founder, TechStart'],
    ['quote' => 'A very polished coworking environment for meetings and focused work. Perfect for consultants and hybrid teams.', 'author' => 'Priya S., Consultant'],
    ['quote' => 'Flexible options, premium look, and a strong business vibe. It feels like a modern office, not just a desk rental.', 'author' => 'Arun M., Startup Founder'],
]);

seedItems($pdo, 'gallery', [
    ['image' => '/images/gallery-front-office-lounge.png', 'alt' => 'Lounge'],
    ['image' => '/images/gallery-open-desk-area.png', 'alt' => 'Open Desks'],
    ['image' => '/images/gallery-boardroom.png', 'alt' => 'Meeting Room'],
    ['image' => '/images/gallery-dining-hall-1.png', 'alt' => 'Cafeteria'],
    ['image' => '/images/private_office_enhanced_1791353356099.jpg', 'alt' => 'Private Office'],
    ['image' => '/images/gallery-front-office-reception.png', 'alt' => 'Reception'],
]);

echo "Seeding about_us fields...\n";

$aboutFields = [
    'about_banner_image' => '/images/coworking_main_1791350864870.jpg',
    'about_banner_heading' => 'About Workiify',
    'about_banner_tagline' => 'Workspace Simplified',

    'about_story_eyebrow' => 'Fully Serviced Offices & Coworking',
    'about_story_heading' => 'Introducing Workiify',
    'about_story_text' => "Workiify offers professional shared workspaces that are connected and completely flexible. Move in today and have the freedom to upsize or downsize according to your needs. What's more, you get instant access to meeting facilities and excellent support services – everything you need to grow your business.",
    'about_story_image' => '/images/coworking_lounge_1791350901804.jpg',

    'purpose_mission_heading' => 'Our Mission',
    'purpose_mission_text' => 'To provide flexible, fully serviced workspaces with everything businesses need to work productively and grow.',
    'purpose_vision_heading' => 'Our Vision',
    'purpose_vision_text' => "To be Coimbatore's most trusted coworking community, where individuals and businesses of every size can work, connect and grow.",

    'workspace_eyebrow' => 'Find Your Workspace',
    'workspace_heading' => 'Your Ideal & Flexible Workspace Solution',
    'workspace_subtitle' => 'Communal Workiify workspaces are beneficial to both individuals and businesses who need a productive and professional work environment.',

    'whychoose_heading' => 'Why Choose Workiify',

    'location_heading' => 'Our Location',
    'location_text' => 'AV Info Tech Park, near KGISL campus, Saravanampatti – 5 floors, 24/7 member access, parking (limited)',
    'location_image' => '/images/gallery-building-exterior.png',

    'community_heading' => 'Our Commitment to Community',
    'community_subtitle' => 'We genuinely care about the communities in which we work and operate, and make it easy for our members to get involved.',
    'community_quote' => "We believe that a vibrant community is a powerful determinant of people's ability to achieve and grow at work – and the joy they experience as a result of them.",
    'community_note' => 'All packages include community and networking events.',

    'about_testimonials_heading' => 'What Our Members Say',
    'about_google_rating' => '5.0',

    'client_logos_heading' => 'Trusted By',
];

foreach ($aboutFields as $k => $v) {
    setField($pdo, $k, $v, 'about');
}
echo '  - upserted ' . count($aboutFields) . " field(s)\n";

echo "Seeding about_us items...\n";

seedItems($pdo, 'workspace_tiles', [
    ['icon' => 'fa-rocket', 'title' => 'SMEs & Entrepreneurs', 'description' => 'Flexible packages that let you downsize or grow without long-term commitments.', 'link' => '/services'],
    ['icon' => 'fa-building', 'title' => 'Corporates', 'description' => 'Coworking and serviced office packages; place up to 160 employees in coworking spaces with managers in private offices.', 'link' => '/services'],
    ['icon' => 'fa-briefcase', 'title' => 'Freelancers & Self-Employed', 'description' => 'Affordable coworking or serviced offices without long-term leases: a full-time office, a business address, or a desk a few days a week.', 'link' => '/services'],
    ['icon' => 'fa-graduation-cap', 'title' => 'Students', 'description' => 'A peaceful place to study and work on assignments, occasionally or for a 3-month exam period.', 'link' => '/services'],
], 'about');

seedItems($pdo, 'why_choose_cards', [
    ['icon' => 'fa-handshake', 'title' => 'Supportive & Attractive Environment', 'text' => 'Workiify is known for friendly, efficient staff and exceptionally attractive, comfortable premises. Whether you need a boardroom booked, printing to be done or packages received from couriers – we are there to provide all the office support you need.'],
    ['icon' => 'fa-map-marker-alt', 'title' => 'Perfect Location', 'text' => 'Located near KGISL, Saravanampatti, in a prime business area with on-site business services and attractive relaxation areas.'],
], 'about');

seedItems($pdo, 'why_choose_icons', [
    ['icon' => 'fa-map-marker-alt', 'text' => 'Prime business location'],
    ['icon' => 'fa-couch', 'text' => 'Premium interior ambiance'],
    ['icon' => 'fa-briefcase', 'text' => 'Flexible workspace options'],
    ['icon' => 'fa-rocket', 'text' => 'Ideal for startups, consultants, and remote teams'],
], 'about');

seedItems($pdo, 'community_photos', [
    ['image' => '/images/gallery-pongal-celebration-2026.png', 'alt' => 'Pongal Celebration 2026'],
    ['image' => '/images/gallery-office-inauguration.png', 'alt' => 'Office Inauguration'],
    ['image' => '/images/gallery-dining-hall-1.png', 'alt' => 'Community dining & networking'],
    ['image' => '/images/gallery-reception-workstations.png', 'alt' => 'Team collaboration space'],
], 'about');

seedItems($pdo, 'about_testimonials_items', [
    ['quote' => 'Workiify gives our team the right mix of professionalism, comfort, and convenience. The location and ambiance are excellent.', 'author' => 'Ravi K., Founder, TechStart'],
    ['quote' => 'A very polished coworking environment for meetings and focused work. Perfect for consultants and hybrid teams.', 'author' => 'Priya S., Consultant'],
    ['quote' => 'Flexible options, premium look, and a strong business vibe. It feels like a modern office, not just a desk rental.', 'author' => 'Arun M., Startup Founder'],
], 'about');

seedItems($pdo, 'client_logos', [
    ['image' => '/images/logo-dark.svg', 'alt' => 'Workiify'],
    ['image' => '/images/logo-dark.svg', 'alt' => 'Workiify'],
    ['image' => '/images/logo-dark.svg', 'alt' => 'Workiify'],
    ['image' => '/images/logo-dark.svg', 'alt' => 'Workiify'],
    ['image' => '/images/logo-dark.svg', 'alt' => 'Workiify'],
], 'about');

echo "Seeding contact_us fields...\n";
$contactFields = [
    'contact_banner_image' => '/images/contact_bg.jpg',
    'contact_banner_heading' => 'Get In Touch',
    'contact_banner_tagline' => 'Have questions or want to book a workspace? Send us a message.',
    'contact_address' => 'AV Info Tech Park, S.F.No:360/4 &amp; 360/5, Keeranatham Road,<br>Near KGISL campus, Saravanampatti, Coimbatore – 641 035, Tamil Nadu, India',
    'contact_phone_display' => '96555 00001',
    'contact_phone_link' => '+919655500001',
    'contact_email_1' => 'info@workiify.com',
    'contact_email_2' => 'spaces@workiify.com',
    'contact_access' => '24/7 for members',
    'contact_map_query' => 'AV Info Tech Park, Saravanampatti, Coimbatore',
    'contact_form_heading' => 'Send Us a Message',
    'contact_form_subtitle' => "Fill in your details and we'll get back to you with the right plan.",
];
foreach ($contactFields as $k => $v) {
    setField($pdo, $k, $v, 'contact');
}
echo '  - upserted ' . count($contactFields) . " field(s)\n";

echo "Seeding gallery fields...\n";
$galleryFields = [
    'gallery_banner_image' => '/images/coworking_lounge_1791350901804.jpg',
    'gallery_banner_eyebrow' => 'Photo Gallery',
    'gallery_banner_heading' => 'Workspace Gallery',
    'gallery_banner_tagline' => 'Explore our thoughtfully designed workspaces, where comfort, creativity, and productivity come together.',
];
foreach ($galleryFields as $k => $v) {
    setField($pdo, $k, $v, 'gallery');
}
echo '  - upserted ' . count($galleryFields) . " field(s)\n";

echo "Seeding gallery items...\n";

seedItems($pdo, 'gallery_photos', [
    ['image' => '/images/gallery-building-exterior.png', 'caption' => 'Workiify Building Exterior', 'category' => 'common-areas'],
    ['image' => '/images/gallery-pongal-celebration-2026.png', 'caption' => 'Pongal Celebration 2026', 'category' => 'events'],
    ['image' => '/images/gallery-office-inauguration.png', 'caption' => 'Office Inauguration Ceremony', 'category' => 'events'],
    ['image' => '/images/gallery-dining-hall-1.png', 'caption' => 'Dining Hall – View 1', 'category' => 'common-areas'],
    ['image' => '/images/gallery-dining-hall-2.png', 'caption' => 'Dining Hall – View 2', 'category' => 'common-areas'],
    ['image' => '/images/gallery-front-office-lounge.png', 'caption' => 'Front Office Lounge', 'category' => 'common-areas'],
    ['image' => '/images/gallery-front-office-reception.png', 'caption' => 'Front Office Reception', 'category' => 'common-areas'],
    ['image' => '/images/coworking_lounge_1791350901804.jpg', 'caption' => 'Lounge Seating Area', 'category' => 'common-areas'],
    ['image' => '/images/gallery-locker-corridor.png', 'caption' => 'Locker Area & Corridor', 'category' => 'common-areas'],
    ['image' => '/images/gallery-main-corridor.png', 'caption' => 'Main Corridor', 'category' => 'common-areas'],
    ['image' => '/images/coworking_private_1791350889843.jpg', 'caption' => 'Community Coworking', 'category' => 'common-areas'],
    ['image' => '/images/gallery-boardroom.png', 'caption' => 'Executive Boardroom', 'category' => 'meeting-rooms'],
    ['image' => '/images/meeting_room_enhanced_1791353342929.jpg', 'caption' => 'Main Conference Room', 'category' => 'meeting-rooms'],
    ['image' => '/images/gallery-open-desk-area.png', 'caption' => 'Open Desk Area', 'category' => 'workspaces'],
    ['image' => '/images/gallery-reception-workstations.png', 'caption' => 'Reception Workstations', 'category' => 'workspaces'],
    ['image' => '/images/gallery-shared-workspace-floor.png', 'caption' => 'Shared Workspace Floor', 'category' => 'workspaces'],
    ['image' => '/images/gallery-open-workstation-area.png', 'caption' => 'Open Workstation Area', 'category' => 'workspaces'],
    ['image' => '/images/coworking_main_1791350864870.jpg', 'caption' => 'Collaborative Space', 'category' => 'workspaces'],
    ['image' => '/images/hot_desk_enhanced_1791353156473.jpg', 'caption' => 'Hot Desk Zone', 'category' => 'workspaces'],
    ['image' => '/images/dedicated_desk_enhanced_1791353327423.jpg', 'caption' => 'Dedicated Desk Area', 'category' => 'workspaces'],
    ['image' => '/images/private_office_enhanced_1791353356099.jpg', 'caption' => 'Private Office', 'category' => 'workspaces'],
], 'gallery');

echo "Seeding admin user...\n";
$checkUser = $pdo->prepare('SELECT COUNT(*) FROM admin_users WHERE username = :u');
$checkUser->execute(['u' => 'admin']);
if ($checkUser->fetchColumn() > 0) {
    echo "  - admin user already exists, skipped\n";
} else {
    $defaultPassword = 'Workiify@2026';
    $hash = password_hash($defaultPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (:u, :p)');
    $stmt->execute(['u' => 'admin', 'p' => $hash]);
    echo "  - created admin user -> username: admin / password: $defaultPassword\n";
    echo "    (please log in and consider this the initial credential; change it if needed)\n";
}

echo "Done.\n";
