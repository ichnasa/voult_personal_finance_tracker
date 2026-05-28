-- SQL Migration: Add Avatar Field to Users Table
-- Target Database: fintrack_db
-- Table: users

-- 1. Add field: avatar (profile picture)
ALTER TABLE `users` 
  ADD COLUMN `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `password`;
