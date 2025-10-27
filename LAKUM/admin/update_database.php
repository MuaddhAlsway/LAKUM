<?php
// Run this file once to update the database structure
require_once '../config/database.php';

$conn = getDBConnection();

// Add end_date and end_time columns to events table
$sql = "ALTER TABLE events 
        ADD COLUMN IF NOT EXISTS end_date DATE NULL AFTER event_date,
        ADD COLUMN IF NOT EXISTS end_time VARCHAR(50) NULL AFTER event_time";

if ($conn->query($sql)) {
    echo "✓ Database updated successfully!<br>";
    echo "Added columns: end_date, end_time<br>";
} else {
    echo "Note: " . $conn->error . "<br>";
}

$conn->close();

echo "<br><a href='events.php'>Go to Events</a>";
?>
