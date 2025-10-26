<?php
require_once 'auth_check.php';
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = $_POST['location'] ?? '';
    
    if (!empty($title) && !empty($event_date)) {
        $conn = getDBConnection();
        
        // Handle cover image upload
        $cover_image = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $upload_dir = '../uploads/covers/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $file_name = 'cover_' . time() . '_' . uniqid() . '.' . $file_ext;
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                $cover_image = 'uploads/covers/' . $file_name;
            }
        }
        
        // Insert event
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, location, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title, $description, $event_date, $event_time, $location, $cover_image);
        
        if ($stmt->execute()) {
            $event_id = $conn->insert_id;
            
            // Handle multiple images
            if (isset($_FILES['event_images']) && !empty($_FILES['event_images']['name'][0])) {
                $upload_dir = '../uploads/events/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                foreach ($_FILES['event_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['event_images']['error'][$key] == 0) {
                        $file_ext = pathinfo($_FILES['event_images']['name'][$key], PATHINFO_EXTENSION);
                        $file_name = 'event_' . time() . '_' . uniqid() . '.' . $file_ext;
                        $target_file = $upload_dir . $file_name;
                        
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $image_path = 'uploads/events/' . $file_name;
                            $conn->query("INSERT INTO event_images (event_id, image_path) VALUES ($event_id, '$image_path')");
                        }
                    }
                }
            }
            
            $success = 'Event created successfully!';
            header('Location: events.php');
            exit();
        } else {
            $error = 'Failed to create event';
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please fill in required fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - LAKUM</title>
    <link rel="icon" href="../assest/logo-lakum- (1).png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">
                <img src="../assest/logo-lakum- (1).png" alt="LAKUM">
            </div>
            <nav>
                <a href="dashboard.php"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a href="events.php" class="active"><i class="ri-calendar-event-line"></i> Events</a>
                <a href="logout.php"><i class="ri-logout-box-line"></i> Logout</a>
            </nav>
        </aside>
        
        <main class="main-content">
            <header>
                <h1>Add New Event</h1>
                <a href="events.php" class="btn-secondary"><i class="ri-arrow-left-line"></i> Back</a>
            </header>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Event Title *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date">Event Date *</label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time">Event Time</label>
                            <input type="text" id="event_time" name="event_time" placeholder="e.g., 17:00 - 22:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" placeholder="e.g., LAKUM Hall 1">
                    </div>
                    
                    <div class="form-group">
                        <label for="cover_image">Cover Image</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*">
                        <small>Recommended size: 1200x800px</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_images">Event Gallery Images</label>
                        <input type="file" id="event_images" name="event_images[]" accept="image/*" multiple>
                        <small>You can select multiple images</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Create Event</button>
                        <a href="events.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
