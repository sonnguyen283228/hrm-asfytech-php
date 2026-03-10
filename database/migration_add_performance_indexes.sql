-- Performance indexes
ALTER TABLE attendance_logs
  ADD INDEX idx_attendance_user_date (user_id, work_date),
  ADD INDEX idx_attendance_date (work_date);

ALTER TABLE users
  ADD INDEX idx_users_role (role),
  ADD INDEX idx_users_department (department_id);

ALTER TABLE projects
  ADD INDEX idx_projects_start_date (start_date),
  ADD INDEX idx_projects_status (status);

ALTER TABLE project_modules
  ADD INDEX idx_project_modules_project (project_id, status, progress_percent);

ALTER TABLE project_members
  ADD INDEX idx_project_members_project (project_id),
  ADD INDEX idx_project_members_user (user_id);
