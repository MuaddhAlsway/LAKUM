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
    $end_date = $_POST['end_date'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
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
        $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, event_time=?, end_date=?, end_time=?, location=?, cover_image=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $title, $description, $event_date, $event_time, $end_date, $end_time, $location, $cover_image, $event_id);
        
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

// Handle image deletion via AJAX
if (isset($_POST['delete_image_ajax'])) {
    $image_id = intval($_POST['delete_image_ajax']);
    $img = $conn->query("SELECT image_path FROM event_images WHERE id = $image_id AND event_id = $event_id")->fetch_assoc();
    if ($img) {
        if (file_exists('../' . $img['image_path'])) {
            unlink('../' . $img['image_path']);
        }
        $conn->query("DELETE FROM event_images WHERE id = $image_id");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

// Handle image deletion via GET (fallback)
if (isset($_GET['delete_image'])) {
    $image_id = intval($_GET['delete_image']);
    $img = $conn->query("SELECT image_path FROM event_images WHERE id = $image_id AND event_id = $event_id")->fetch_assoc();
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
    <link rel="stylesheet" href="event-form-style.css">
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
                <h1><i class="ri-edit-line"></i> Edit Event</h1>
                <a href="events.php" class="btn-secondary"><i class="ri-arrow-left-line"></i> Back to Events</a>
            </header>
            
            <div class="modern-form-container">
                <form method="POST" enctype="multipart/form-data" id="eventForm">
                    
                    <!-- Cover Image Section -->
                    <div class="cover-upload-section">
                        <div class="cover-preview" id="coverPreview">
                            <?php if ($event['cover_image']): ?>
                                <img id="coverImg" src="../<?php echo htmlspecialchars($event['cover_image']); ?>" style="display: block;">
                                <button type="button" class="remove-cover" id="removeCover" style="display: block;">
                                    <i class="ri-close-line"></i>
                                </button>
                            <?php else: ?>
                                <div class="cover-placeholder">
                                    <i class="ri-image-add-line"></i>
                                    <p>Click to upload cover image</p>
                                    <span>Recommended: 1200x800px</span>
                                </div>
                                <img id="coverImg" style="display: none;">
                                <button type="button" class="remove-cover" id="removeCover" style="display: none;">
                                    <i class="ri-close-line"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*" style="display: none;">
                    </div>
                    
                    <!-- Event Details -->
                    <div class="form-section">
                        <h3><i class="ri-information-line"></i> Event Details</h3>
                        
                        <div class="form-group">
                            <label for="title">Event Title *</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" placeholder="Enter event title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="6" placeholder="Describe your event..."><?php echo htmlspecialchars($event['description']); ?></textarea>
                            <div class="char-count"><span id="charCount"><?php echo strlen($event['description']); ?></span> characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" placeholder="e.g., LAKUM Hall 1">
                        </div>
                    </div>
                    
                    <!-- Date & Time Section -->
                    <div class="form-section">
                        <h3><i class="ri-calendar-line"></i> Date & Time</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date">Start Date *</label>
                                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="event_time">Start Time</label>
                                <input type="time" id="event_time" name="event_time" value="<?php echo !empty($event['event_time']) ? date('H:i', strtotime($event['event_time'])) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($event['end_date'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" id="end_time" name="end_time" value="<?php echo !empty($event['end_time']) ? date('H:i', strtotime($event['end_time'])) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="date-validation-msg" id="dateValidation"></div>
                    </div>
                    
                    <!-- Gallery Images Section -->
                    <div class="form-section">
                        <h3><i class="ri-gallery-line"></i> Event Gallery</h3>
                        
                        <!-- Existing Images -->
                        <?php 
                        $conn_temp = getDBConnection();
                        $imgs = $conn_temp->query("SELECT * FROM event_images WHERE event_id = $event_id");
                        if ($imgs->num_rows > 0):
                        ?>
                        <div class="existing-gallery">
                            <h4>Current Images</h4>
                            <div class="gallery-preview" id="existingGallery">
                                <?php while ($img = $imgs->fetch_assoc()): ?>
                                    <div class="gallery-item" data-image-id="<?php echo $img['id']; ?>">
                                        <img src="../<?php echo htmlspecialchars($img['image_path']); ?>" alt="Gallery">
                                        <button type="button" class="remove-gallery-img" onclick="deleteGalleryImage(<?php echo $img['id']; ?>, this)">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php 
                        endif;
                        $conn_temp->close();
                        ?>
                        
                        <!-- Add New Images -->
                        <div class="gallery-upload-area" id="galleryUploadArea">
                            <i class="ri-image-add-line"></i>
                            <p>Click or drag images here</p>
                            <span>Add more images to gallery</span>
                        </div>
                        <input type="file" id="event_images" name="event_images[]" accept="image/*" multiple style="display: none;">
                        
                        <div class="gallery-preview" id="galleryPreview"></div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions-modern">
                        <button type="submit" class="btn-primary-modern" id="submitBtn">
                            <i class="ri-check-line"></i> Update Event
                        </button>
                        <a href="events.php" class="btn-secondary-modern">
                            <i class="ri-close-line"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <!-- Success/Error Modal -->
    <?php if ($error || $success): ?>
    <div class="modal-overlay" id="messageModal">
        <div class="modal-content <?php echo $success ? 'success' : 'error'; ?>">
            <div class="modal-icon">
                <i class="<?php echo $success ? 'ri-checkbox-circle-line' : 'ri-error-warning-line'; ?>"></i>
            </div>
            <h3><?php echo $success ? 'Success!' : 'Error'; ?></h3>
            <p><?php echo htmlspecialchars($error ?: $success); ?></p>
            <button onclick="document.getElementById('messageModal').style.display='none'" class="btn-primary-modern">OK</button>
        </div>
    </div>
    <?php endif; ?>

    <script src="event-form.js"></script>
    <script>
        // Function to delete gallery images
        function deleteGalleryImage(imageId, button) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }
            
            // Send AJAX request to delete image
            fetch('edit_event.php?id=<?php echo $event_id; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'delete_image_ajax=' + imageId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the gallery item from DOM
                    const galleryItem = button.closest('.gallery-item');
                    galleryItem.style.opacity = '0';
                    setTimeout(() => {
                        galleryItem.remove();
                        
                        // Check if gallery is empty
                        const existingGallery = document.getElementById('existingGallery');
                        if (existingGallery && existingGallery.children.length === 0) {
                            existingGallery.parentElement.remove();
                        }
                    }, 300);
                } else {
                    alert('Failed to delete image. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>
