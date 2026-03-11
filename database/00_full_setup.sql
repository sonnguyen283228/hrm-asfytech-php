-- ==============================================
-- HRM APP - FULL ONE SHOT SQL SETUP
-- Import 1 lần trên phpMyAdmin (DB đã chọn sẵn)
-- ==============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS positions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_positions_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) NULL,
  address_ward VARCHAR(120) NULL,
  address_city VARCHAR(120) NULL,
  start_date DATE NULL,
  birth_date DATE NULL,
  base_salary BIGINT UNSIGNED NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','manager','staff') NOT NULL DEFAULT 'staff',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  department_id INT UNSIGNED NULL,
  position_id INT UNSIGNED NULL,
  position VARCHAR(120) NULL,
  avatar_url VARCHAR(500) NULL,
  last_seen_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  CONSTRAINT fk_users_position FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE SET NULL,
  KEY idx_users_is_active (is_active),
  KEY idx_users_last_seen (last_seen_at),
  KEY idx_users_role (role),
  KEY idx_users_department (department_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS attendance_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  work_date DATE NOT NULL,
  check_in DATETIME NULL,
  check_out DATETIME NULL,
  check_in_ip VARCHAR(45) NULL,
  check_out_ip VARCHAR(45) NULL,
  note VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_attendance_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY uq_user_work_date (user_id, work_date),
  KEY idx_attendance_user_date (user_id, work_date),
  KEY idx_attendance_date (work_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS leave_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  leave_type ENUM('annual','sick','unpaid','late','early') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  reason TEXT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  approved_by INT UNSIGNED NULL,
  approved_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_leave_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_leave_approver FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  start_date DATE NOT NULL,
  duration_months INT UNSIGNED NULL,
  description TEXT NULL,
  status ENUM('planning','in_progress','paused','done') NOT NULL DEFAULT 'planning',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_projects_start_date (start_date),
  KEY idx_projects_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_members (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  project_role VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_member_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_member_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY uq_project_user (project_id, user_id),
  KEY idx_project_members_project (project_id),
  KEY idx_project_members_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_modules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(200) NOT NULL,
  planned_months DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  status ENUM('pending','in_progress','done') NOT NULL DEFAULT 'pending',
  progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_module_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  KEY idx_project_modules_project (project_id, status, progress_percent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_details (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  content VARCHAR(255) NOT NULL,
  assignee_user_id INT UNSIGNED NULL,
  start_date DATE NOT NULL,
  duration_days INT UNSIGNED NOT NULL,
  expected_finish_date DATE NOT NULL,
  progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_details_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_details_assignee FOREIGN KEY (assignee_user_id) REFERENCES users(id) ON DELETE SET NULL,
  KEY idx_project_details_project (project_id, duration_days, progress_percent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS approval_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type ENUM('user','department','position','project') NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  action_type ENUM('create','update','lock') NOT NULL,
  requested_by INT UNSIGNED NOT NULL,
  reviewed_by INT UNSIGNED NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  payload_before JSON NULL,
  payload_after JSON NULL,
  note VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  reviewed_at DATETIME NULL,
  CONSTRAINT fk_approval_requested_by FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_approval_reviewed_by FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(120) NOT NULL UNIQUE,
  `value` LONGTEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO departments(name, description, is_active)
VALUES
('Ban điều hành','Điều hành chung',1),
('Kỹ thuật','Phát triển sản phẩm',1),
('Kinh doanh','Kinh doanh & chăm sóc khách hàng',1)
ON DUPLICATE KEY UPDATE description=VALUES(description), is_active=VALUES(is_active);

INSERT INTO positions(name, description, is_active)
VALUES
('Admin','Quản trị hệ thống',1),
('HR','Nhân sự',1),
('Developer','Lập trình viên',1),
('QA','Kiểm thử',1)
ON DUPLICATE KEY UPDATE description=VALUES(description), is_active=VALUES(is_active);

INSERT INTO users(full_name,email,password,role,is_active,department_id,position_id,position,avatar_url,last_seen_at)
VALUES
('Nguyen Son Admin','nguyensonmbt@gmail.com','$2y$10$wjLtOjk.Xh83utPQx1x6Cuj9L7CGfGhYfQf2Q6Pq8ExPji2gA4m1K','admin',1,1,1,'Admin',NULL,NOW()),
('Nhân sự mẫu','staff@company.local','$2y$10$wjLtOjk.Xh83utPQx1x6Cuj9L7CGfGhYfQf2Q6Pq8ExPji2gA4m1K','staff',1,2,3,'Developer',NULL,NOW())
ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), role=VALUES(role), is_active=VALUES(is_active);

INSERT INTO site_settings(`key`,`value`) VALUES
('site_name','HRM APP'),
('site_logo_url',''),
('site_favicon_url',''),
('header_html',''),
('footer_html',''),
('footer_text','© HRM APP')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

SET FOREIGN_KEY_CHECKS = 1;
