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
    $end_date = $_POST['end_date'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
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
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, end_date, end_time, location, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $title, $description, $event_date, $event_time, $end_date, $end_time, $location, $cover_image);
        
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
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'events.php';
                }, 1500);
            </script>";
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
                <h1><i class="ri-add-circle-line"></i> Create New Event</h1>
                <a href="events.php" class="btn-secondary"><i class="ri-arrow-left-line"></i> Back to Events</a>
            </header>
            
            <div class="modern-form-container">
                <form method="POST" enctype="multipart/form-data" id="eventForm">
                    
                    <!-- Cover Image Section -->
                    <div class="cover-upload-section">
                        <div class="cover-preview" id="coverPreview">
                            <div class="cover-placeholder">
                                <i class="ri-image-add-line"></i>
                                <p>Click to upload cover image</p>
                                <span>Recommended: 1200x800px</span>
                            </div>
                            <img id="coverImg" style="display: none;">
                            <button type="button" class="remove-cover" id="removeCover" style="display: none;">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*" style="display: none;" required>
                    </div>
                    
                    <!-- Event Details -->
                    <div class="form-section">
                        <h3><i class="ri-information-line"></i> Event Details</h3>
                        
                        <div class="form-group">
                            <label for="title">Event Title *</label>
                            <input type="text" id="title" name="title" placeholder="Enter event title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="6" placeholder="Describe your event..."></textarea>
                            <div class="char-count"><span id="charCount">0</span> characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" placeholder="e.g., LAKUM Hall 1">
                        </div>
                    </div>
                    
                    <!-- Date & Time Section -->
                    <div class="form-section">
                        <h3><i class="ri-calendar-line"></i> Date & Time</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date">Start Date *</label>
                                <input type="date" id="event_date" name="event_date" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="event_time">Start Time</label>
                                <input type="time" id="event_time" name="event_time">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" id="end_time" name="end_time">
                            </div>
                        </div>
                        
                        <div class="date-validation-msg" id="dateValidation"></div>
                    </div>
                    
                    <!-- Gallery Images Section -->
                    <div class="form-section">
                        <h3><i class="ri-gallery-line"></i> Event Gallery</h3>
                        
                        <div class="gallery-upload-area" id="galleryUploadArea">
                            <i class="ri-image-add-line"></i>
                            <p>Click or drag images here</p>
                            <span>You can upload multiple images</span>
                        </div>
                        <input type="file" id="event_images" name="event_images[]" accept="image/*" multiple style="display: none;">
                        
                        <div class="gallery-preview" id="galleryPreview"></div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions-modern">
                        <button type="submit" class="btn-primary-modern" id="submitBtn">
                            <i class="ri-check-line"></i> Create Event
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
            <?php if ($success): ?>
                <p class="redirect-msg">Redirecting to events...</p>
            <?php else: ?>
                <button onclick="document.getElementById('messageModal').style.display='none'" class="btn-primary-modern">OK</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <script src="event-form.js"></script>
</body>
</html>
