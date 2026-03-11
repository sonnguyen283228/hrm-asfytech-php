-- Requirement v2 updates
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER role,
  ADD COLUMN IF NOT EXISTS phone VARCHAR(20) NULL AFTER email,
  ADD COLUMN IF NOT EXISTS address_ward VARCHAR(120) NULL AFTER phone,
  ADD COLUMN IF NOT EXISTS address_city VARCHAR(120) NULL AFTER address_ward,
  ADD COLUMN IF NOT EXISTS start_date DATE NULL AFTER address_city,
  ADD COLUMN IF NOT EXISTS birth_date DATE NULL AFTER start_date,
  ADD COLUMN IF NOT EXISTS base_salary BIGINT UNSIGNED NULL AFTER birth_date,
  ADD COLUMN IF NOT EXISTS last_seen_at DATETIME NULL AFTER avatar_url,
  ADD COLUMN IF NOT EXISTS position_id INT UNSIGNED NULL AFTER department_id;

ALTER TABLE users
  ADD CONSTRAINT fk_users_position FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE SET NULL;

ALTER TABLE departments
  ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER name,
  ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER description;

CREATE TABLE IF NOT EXISTS positions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_positions_name (name)
);

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
);

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
  CONSTRAINT fk_project_details_assignee FOREIGN KEY (assignee_user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_users_is_active ON users(is_active);
CREATE INDEX idx_users_last_seen ON users(last_seen_at);
CREATE INDEX idx_project_details_project ON project_details(project_id, duration_days, progress_percent);
