<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'Invalid event ID']);
    exit();
}

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($event) {
    // Get gallery images
    $images = $conn->query("SELECT image_path FROM event_images WHERE event_id = $id");
    $gallery = [];
    while ($img = $images->fetch_assoc()) {
        $gallery[] = $img['image_path'];
    }
    
    $event['gallery_images'] = $gallery;
    $event['month'] = date('F', strtotime($event['event_date']));
    $event['month_short'] = date('M', strtotime($event['event_date']));
    $event['day'] = date('d', strtotime($event['event_date']));
    $event['year'] = date('Y', strtotime($event['event_date']));
    $event['is_upcoming'] = $event['event_date'] >= date('Y-m-d');
    
    echo json_encode($event);
} else {
    echo json_encode(['error' => 'Event not found']);
}

$stmt->close();
$conn->close();
?>
