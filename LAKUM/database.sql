-- LAKUM Artspace Database Schema
-- Run this SQL if you prefer manual database setup

-- Create database
CREATE DATABASE IF NOT EXISTS lakum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lakum;

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
    location VARCHAR(255),
    cover_image VARCHAR(255),
    status ENUM('upcoming', 'past') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status)
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
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username=username;

-- Sample events (optional - remove if not needed)
INSERT INTO events (title, description, event_date, event_time, location, cover_image) VALUES
('Contemporary Art Exhibition', 'Discover the latest works from emerging Saudi artists showcasing contemporary art and design.', '2025-11-15', '17:00 - 22:00', 'LAKUM Hall 1', 'assest/img-3.JPG'),
('Photography Workshop', 'Learn professional photography techniques from renowned photographers in an interactive workshop.', '2025-11-20', '14:00 - 18:00', 'LAKUM Hall 2', 'assest/img-3.JPG'),
('Cultural Heritage Exhibition', 'Explore the rich cultural heritage of Saudi Arabia through art, artifacts, and multimedia presentations.', '2025-12-01', '10:00 - 20:00', 'LAKUM Hall 1', 'assest/img-3.JPG');

-- Verify tables
SHOW TABLES;

-- Display admin credentials
SELECT 'Admin Login Credentials:' as Info;
SELECT username, 'admin123' as password FROM admin WHERE username = 'admin';
