-- ─────────────────────────────────────────────────────────────
-- PLOOM - MySQL Initialization Script
-- Runs automatically on first container startup
-- ─────────────────────────────────────────────────────────────

-- Ensure proper character set
ALTER DATABASE fintrack_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- (Optional) Grant additional privileges if needed
-- GRANT ALL PRIVILEGES ON fintrack_db.* TO 'ploom'@'%';
-- FLUSH PRIVILEGES;
