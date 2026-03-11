-- Run this for existing databases
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS position VARCHAR(120) NULL AFTER department_id,
  ADD COLUMN IF NOT EXISTS avatar_url VARCHAR(500) NULL AFTER position;
