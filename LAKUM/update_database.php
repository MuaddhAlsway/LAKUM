<?php
/**
 * Database Update Script
 * Run this file once to ensure your database is properly configured
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>LAKUM Database Update</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .success { color: green; padding: 10px; background: #e8f5e9; border-left: 4px solid green; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #ffebee; border-left: 4px solid red; margin: 10px 0; }
        .info { color: #333; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196F3; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .btn { display: inline-block; padding: 10px 20px; background: #000; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #333; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>LAKUM Database Update</h1>";

$conn = getDBConnection();

// Check if connection is successful
if (!$conn) {
    echo "<div class='error'>❌ Database connection failed!</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='success'>✓ Database connection successful</div>";

// Update events table structure
echo "<h2>Updating Events Table...</h2>";

$queries = [
    "ALTER TABLE events MODIFY COLUMN status ENUM('upcoming', 'past') DEFAULT 'upcoming'",
    "ALTER TABLE events ADD COLUMN IF NOT EXISTS end_date DATE AFTER event_time",
    "ALTER TABLE events ADD COLUMN IF NOT EXISTS end_time VARCHAR(50) AFTER end_date",
    "ALTER TABLE events ADD COLUMN IF NOT EXISTS video_link VARCHAR(500) AFTER location",
    "CREATE INDEX IF NOT EXISTS idx_event_date ON events(event_date)",
    "CREATE INDEX IF NOT EXISTS idx_status ON events(status)",
    "CREATE INDEX IF NOT EXISTS idx_created_at ON events(created_at)"
];

foreach ($queries as $query) {
    if ($conn->query($query)) {
        echo "<div class='success'>✓ Query executed successfully</div>";
    } else {
        // Some queries might fail if already exists, that's okay
        if (strpos($conn->error, 'Duplicate') === false && strpos($conn->error, 'exists') === false) {
            echo "<div class='info'>ℹ " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}

// Update event statuses based on dates
echo "<h2>Updating Event Statuses...</h2>";

$updatePast = "UPDATE events SET status = 'past' WHERE event_date < CURDATE() AND status = 'upcoming'";
$updateUpcoming = "UPDATE events SET status = 'upcoming' WHERE event_date >= CURDATE() AND status = 'past'";

if ($conn->query($updatePast)) {
    $pastCount = $conn->affected_rows;
    echo "<div class='success'>✓ Updated $pastCount past events</div>";
}

if ($conn->query($updateUpcoming)) {
    $upcomingCount = $conn->affected_rows;
    echo "<div class='success'>✓ Updated $upcomingCount upcoming events</div>";
}

// Display current events
echo "<h2>Current Events in Database</h2>";

$result = $conn->query("SELECT id, title, event_date, event_time, location, status, 
                        CASE WHEN event_date >= CURDATE() THEN 'UPCOMING' ELSE 'PAST' END as actual_status 
                        FROM events ORDER BY event_date ASC");

if ($result && $result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actual Status</th>
            </tr>";
    
    while ($row = $result->fetch_assoc()) {
        $statusColor = $row['actual_status'] == 'UPCOMING' ? 'green' : 'gray';
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>{$row['event_date']}</td>
                <td>{$row['event_time']}</td>
                <td>{$row['status']}</td>
                <td style='color: $statusColor; font-weight: bold;'>{$row['actual_status']}</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<div class='info'>ℹ No events found in database. Add events through the admin panel.</div>";
}

// Display event images count
$imageResult = $conn->query("SELECT COUNT(*) as count FROM event_images");
if ($imageResult) {
    $imageCount = $imageResult->fetch_assoc()['count'];
    echo "<div class='info'>ℹ Total event images: $imageCount</div>";
}

$conn->close();

echo "<div class='success' style='margin-top: 30px;'>
        <strong>✓ Database update completed successfully!</strong>
      </div>";

echo "<a href='Calender.php' class='btn'>View Calendar</a> ";
echo "<a href='admin/login.php' class='btn'>Admin Panel</a>";

echo "</div></body></html>";
?>
