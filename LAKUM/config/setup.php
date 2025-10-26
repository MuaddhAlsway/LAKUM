<?php
require_once 'database.php';

// Create database and tables
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
$conn->query($sql);

$conn->select_db(DB_NAME);

// Create admin table
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Create events table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time VARCHAR(50),
    location VARCHAR(255),
    cover_image VARCHAR(255),
    status ENUM('upcoming', 'past') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Create event_images table for multiple images
$sql = "CREATE TABLE IF NOT EXISTS event_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)";
$conn->query($sql);

// Insert default admin (username: admin, password: admin123)
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$check = $conn->query("SELECT id FROM admin WHERE username = '$username'");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO admin (username, password) VALUES ('$username', '$password')");
}

echo "Database setup completed successfully!<br>";
echo "Default admin credentials:<br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";
echo "<a href='../admin/login.php'>Go to Admin Login</a>";

$conn->close();
?>
