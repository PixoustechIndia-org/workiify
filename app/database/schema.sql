-- Workiify CMS schema (multi-page content management -- table names kept as
-- "home_*" for historical reasons, but a `page` column scopes every row to
-- whichever page it belongs to: 'home', 'about', etc.)

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Singular, one-off content fields (headings, subtitles, static paragraphs, stats)
CREATE TABLE IF NOT EXISTS home_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(30) NOT NULL DEFAULT 'home',
    field_key VARCHAR(100) NOT NULL,
    field_value LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY page_field (page, field_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Repeatable collections (hero slides, service cards, amenities, why-choose points,
-- audience tiles, testimonials, gallery tiles, workspace-solution tiles, etc.) --
-- each row is one item in one of these collections, with a flexible JSON payload
-- so each section_key can have its own shape of fields without needing a
-- dedicated table per collection.
CREATE TABLE IF NOT EXISTS home_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(30) NOT NULL DEFAULT 'home',
    section_key VARCHAR(50) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    data LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_section (page, section_key, sort_order)
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

-- Undo history: a snapshot of what a field-section or an item looked like
-- immediately before it was overwritten or deleted, so a bad save can be
-- reverted from the admin's History screen instead of being permanent.
-- entity_type: 'fields' (a whole field-group save), 'item' (an item update),
-- or 'item_deleted' (an item right before deletion, so restoring re-inserts it).
CREATE TABLE IF NOT EXISTS content_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(30) NOT NULL,
    entity_type ENUM('fields','item','item_deleted') NOT NULL,
    section_key VARCHAR(50) NOT NULL,
    item_id INT NULL,
    label VARCHAR(150) NOT NULL,
    snapshot LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_page_created (page, created_at),
    INDEX idx_section (page, section_key, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Enquiry form submissions: shared by the Home page inline form, the Contact
-- page inline form, and the site-wide enquiry popup (distinguished by
-- `source`). Saved regardless of whether the notification email succeeds, so
-- a lead is never lost to a mail delivery problem.
CREATE TABLE IF NOT EXISTS enquiries (
    id INT(11) NOT NULL AUTO_INCREMENT,
    source VARCHAR(20) NOT NULL DEFAULT 'contact',
    company_name VARCHAR(150) NOT NULL,
    company_address VARCHAR(250) NOT NULL,
    contact_no VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    req_type VARCHAR(20) NOT NULL,
    req_value INT(11) NOT NULL,
    additional TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'New',
    email_sent TINYINT(1) NOT NULL DEFAULT 0,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
