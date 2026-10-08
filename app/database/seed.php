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

function setField(PDO $pdo, $key, $value) {
    $stmt = $pdo->prepare('INSERT INTO home_fields (field_key, field_value) VALUES (:k, :v)
                            ON DUPLICATE KEY UPDATE field_value = :v2');
    $stmt->execute(['k' => $key, 'v' => $value, 'v2' => $value]);
}

function seedItems(PDO $pdo, $section, array $rows) {
    $check = $pdo->prepare('SELECT COUNT(*) FROM home_items WHERE section_key = :s');
    $check->execute(['s' => $section]);
    if ($check->fetchColumn() > 0) {
        echo "  - $section: already has data, skipped\n";
        return;
    }
    $stmt = $pdo->prepare('INSERT INTO home_items (section_key, sort_order, data) VALUES (:s, :o, :d)');
    foreach ($rows as $i => $row) {
        $stmt->execute(['s' => $section, 'o' => $i, 'd' => json_encode($row)]);
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
