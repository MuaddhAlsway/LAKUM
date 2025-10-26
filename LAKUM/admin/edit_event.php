<?php
require_once 'auth_check.php';
require_once '../config/database.php';

$error = '';
$success = '';
$event_id = intval($_GET['id'] ?? 0);

$conn = getDBConnection();

// Get event data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header('Location: events.php');
    exit();
}

// Get event images
$images = $conn->query("SELECT * FROM event_images WHERE event_id = $event_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = $_POST['location'] ?? '';
    
    if (!empty($title) && !empty($event_date)) {
        $cover_image = $event['cover_image'];
        
        // Handle new cover image
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $upload_dir = '../uploads/covers/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $file_name = 'cover_' . time() . '_' . uniqid() . '.' . $file_ext;
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                // Delete old cover
                if ($cover_image && file_exists('../' . $cover_image)) {
                    unlink('../' . $cover_image);
                }
                $cover_image = 'uploads/covers/' . $file_name;
            }
        }
        
        // Update event
        $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, event_time=?, location=?, cover_image=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $description, $event_date, $event_time, $location, $cover_image, $event_id);
        
        if ($stmt->execute()) {
            // Handle new images
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
            
            $success = 'Event updated successfully!';
            // Refresh event data
            $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $event = $result->fetch_assoc();
        } else {
            $error = 'Failed to update event';
        }
        
        $stmt->close();
    } else {
        $error = 'Please fill in required fields';
    }
}

// Handle image deletion
if (isset($_GET['delete_image'])) {
    $image_id = intval($_GET['delete_image']);
    $img = $conn->query("SELECT image_path FROM event_images WHERE id = $image_id")->fetch_assoc();
    if ($img && file_exists('../' . $img['image_path'])) {
        unlink('../' . $img['image_path']);
    }
    $conn->query("DELETE FROM event_images WHERE id = $image_id");
    header("Location: edit_event.php?id=$event_id");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - LAKUM</title>
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
                <h1>Edit Event</h1>
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
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date">Event Date *</label>
                            <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time">Event Time</label>
                            <input type="text" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time']); ?>" placeholder="e.g., 17:00 - 22:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" placeholder="e.g., LAKUM Hall 1">
                    </div>
                    
                    <div class="form-group">
                        <label>Current Cover Image</label>
                        <?php if ($event['cover_image']): ?>
                            <img src="../<?php echo htmlspecialchars($event['cover_image']); ?>" alt="Cover" style="max-width: 300px; border-radius: 5px; margin-top: 10px;">
                        <?php else: ?>
                            <p>No cover image</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="cover_image">Change Cover Image</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label>Current Gallery Images</label>
                        <div class="image-gallery">
                            <?php 
                            $images = $conn = getDBConnection();
                            $imgs = $conn->query("SELECT * FROM event_images WHERE event_id = $event_id");
                            while ($img = $imgs->fetch_assoc()): 
                            ?>
                                <div class="gallery-item">
                                    <img src="../<?php echo htmlspecialchars($img['image_path']); ?>" alt="Gallery">
                                    <a href="edit_event.php?id=<?php echo $event_id; ?>&delete_image=<?php echo $img['id']; ?>" class="delete-img" onclick="return confirm('Delete this image?')">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            <?php 
                            endwhile;
                            $conn->close();
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_images">Add More Gallery Images</label>
                        <input type="file" id="event_images" name="event_images[]" accept="image/*" multiple>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Update Event</button>
                        <a href="events.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
