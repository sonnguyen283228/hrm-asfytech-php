-- Admin site customization + project duration derived from details
CREATE TABLE IF NOT EXISTS site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(120) NOT NULL UNIQUE,
  `value` LONGTEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO site_settings(`key`,`value`) VALUES
('site_name','HRM APP'),
('site_logo_url',''),
('site_favicon_url',''),
('header_html',''),
('footer_html',''),
('footer_text','© HRM APP')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

ALTER TABLE projects MODIFY COLUMN duration_months INT UNSIGNED NULL;
