CREATE DATABASE IF NOT EXISTS hrm_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hrm_php;

CREATE TABLE IF NOT EXISTS departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','manager','staff') NOT NULL DEFAULT 'staff',
  department_id INT UNSIGNED NULL,
  position VARCHAR(120) NULL,
  avatar_url VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

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
  UNIQUE KEY uq_user_work_date (user_id, work_date)
);

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
);

INSERT INTO departments(name) VALUES ('Ban điều hành'), ('Kỹ thuật'), ('Kinh doanh')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO users(full_name,email,password,role,department_id)
VALUES
('System Admin','admin@company.local', '$2y$10$wjLtOjk.Xh83utPQx1x6Cuj9L7CGfGhYfQf2Q6Pq8ExPji2gA4m1K', 'admin', 1),
('Nhân viên mẫu','staff@company.local', '$2y$10$wjLtOjk.Xh83utPQx1x6Cuj9L7CGfGhYfQf2Q6Pq8ExPji2gA4m1K', 'staff', 2)
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- password mặc định: 123456


CREATE TABLE IF NOT EXISTS projects (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  start_date DATE NOT NULL,
  duration_months INT UNSIGNED NOT NULL,
  description TEXT NULL,
  status ENUM('planning','in_progress','paused','done') NOT NULL DEFAULT 'planning',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS project_members (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  project_role VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_member_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_member_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY uq_project_user (project_id, user_id)
);

CREATE TABLE IF NOT EXISTS project_modules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(200) NOT NULL,
  planned_months DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  status ENUM('pending','in_progress','done') NOT NULL DEFAULT 'pending',
  progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_module_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
