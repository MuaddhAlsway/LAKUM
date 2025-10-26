<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$conn = getDBConnection();

$type = $_GET['type'] ?? 'all';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;

$query = "SELECT e.*, 
          (SELECT GROUP_CONCAT(image_path) FROM event_images WHERE event_id = e.id) as gallery_images
          FROM events e";

switch ($type) {
    case 'upcoming':
        $query .= " WHERE e.event_date >= CURDATE() ORDER BY e.event_date ASC";
        break;
    case 'past':
        $query .= " WHERE e.event_date < CURDATE() ORDER BY e.event_date DESC";
        break;
    case 'latest':
        $query .= " ORDER BY e.created_at DESC";
        break;
    case 'closest':
        $query .= " WHERE e.event_date >= CURDATE() ORDER BY e.event_date ASC";
        break;
    default:
        $query .= " ORDER BY e.event_date DESC";
}

if ($limit) {
    $query .= " LIMIT $limit";
}

$result = $conn->query($query);
$events = [];

while ($row = $result->fetch_assoc()) {
    $row['gallery_images'] = $row['gallery_images'] ? explode(',', $row['gallery_images']) : [];
    $row['month'] = date('F', strtotime($row['event_date']));
    $row['month_short'] = date('M', strtotime($row['event_date']));
    $row['day'] = date('d', strtotime($row['event_date']));
    $row['year'] = date('Y', strtotime($row['event_date']));
    $row['is_upcoming'] = $row['event_date'] >= date('Y-m-d');
    $events[] = $row;
}

$conn->close();

echo json_encode($events);
?>
