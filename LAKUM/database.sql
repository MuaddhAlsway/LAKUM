-- =====================================================
-- LAKUM Artspace - Complete Database Schema
-- =====================================================
-- Import this file to create/recreate the entire database
-- This includes all tables, indexes, and sample data
-- =====================================================

-- Drop existing database if you want a fresh start (CAUTION: This deletes all data!)
-- DROP DATABASE IF EXISTS lakum;

-- Create database
CREATE DATABASE IF NOT EXISTS lakum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lakum;

-- Drop existing tables if recreating
DROP TABLE IF EXISTS event_images;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS admin;

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time VARCHAR(50),
    end_date DATE,
    end_time VARCHAR(50),
    location VARCHAR(255),
    video_link VARCHAR(500),
    cover_image VARCHAR(255),
    status ENUM('upcoming', 'past') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Event images table
CREATE TABLE IF NOT EXISTS event_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Username: admin
-- Password: admin123
INSERT INTO admin (username, password) VALUES 
('admin', '$2a$12$P90/pF/eOL2DxuillHl.xuvFy0Qfk3k451dGz675vbiefbsumc0HC')
ON DUPLICATE KEY UPDATE username=username;

-- Sample events (optional - remove if not needed)
INSERT INTO events (title, description, event_date, event_time, end_date, end_time, location, video_link, cover_image) VALUES
('Contemporary Art Exhibition', 'Discover the latest works from emerging Saudi artists showcasing contemporary art and design.', '2025-11-15', '17:00', '2025-11-15', '22:00', 'LAKUM Hall 1', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'assest/img-3.JPG'),
('Photography Workshop', 'Learn professional photography techniques from renowned photographers in an interactive workshop.', '2025-11-20', '14:00', '2025-11-20', '18:00', 'LAKUM Hall 2', 'https://vimeo.com/148751763', 'assest/img-3.JPG'),
('Cultural Heritage Exhibition', 'Explore the rich cultural heritage of Saudi Arabia through art, artifacts, and multimedia presentations.', '2025-12-01', '10:00', '2025-12-05', '20:00', 'LAKUM Hall 1', NULL, 'assest/img-3.JPG');

-- =====================================================
-- Verification Queries
-- =====================================================

-- Show all tables
SHOW TABLES;

-- Show events count
SELECT COUNT(*) as total_events FROM events;

-- Show upcoming events
SELECT id, title, event_date, status FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC;

-- =====================================================
-- Admin Login Credentials
-- =====================================================
-- Username: admin
-- Password: admin123
-- =====================================================

SELECT 'Database setup complete!' as Status;
SELECT 'Login with username: admin, password: admin123' as Info;
