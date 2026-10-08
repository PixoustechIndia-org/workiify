-- Workiify CMS schema (Home page content management)

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Singular, one-off content fields (headings, subtitles, static paragraphs, stats)
CREATE TABLE IF NOT EXISTS home_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field_key VARCHAR(100) NOT NULL UNIQUE,
    field_value LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Repeatable collections (hero slides, service cards, amenities, why-choose points,
-- audience tiles, testimonials, gallery tiles) -- each row is one item in one of
-- these collections, with a flexible JSON payload so each section_key can have its
-- own shape of fields without needing a dedicated table per collection.
CREATE TABLE IF NOT EXISTS home_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(50) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    data LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_section (section_key, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Shared media library: every uploaded image (and every image already on disk under
-- public/images) is registered here so it can be picked and reused across any field
-- instead of being uploaded separately each time.
CREATE TABLE IF NOT EXISTS media_assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(255) NOT NULL UNIQUE,
    original_name VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
