<?php
require_once 'auth_check.php';
require_once '../config/database.php';

$conn = getDBConnection();

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM events WHERE id = $id");
    header('Location: events.php');
    exit();
}

// Get all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - LAKUM</title>
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
                <h1>Manage Events</h1>
                <a href="add_event.php" class="btn-primary"><i class="ri-add-line"></i> Add New Event</a>
            </header>
            
            <div class="events-table">
                <table>
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($event = $events->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if ($event['cover_image']): ?>
                                    <img src="../<?php echo htmlspecialchars($event['cover_image']); ?>" alt="Cover" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #ddd; border-radius: 5px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                            <td><?php echo htmlspecialchars($event['event_time']); ?></td>
                            <td>
                                <span class="badge <?php echo $event['event_date'] >= date('Y-m-d') ? 'upcoming' : 'past'; ?>">
                                    <?php echo $event['event_date'] >= date('Y-m-d') ? 'Upcoming' : 'Past'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn-small btn-edit"><i class="ri-edit-line"></i></a>
                                <a href="events.php?delete=<?php echo $event['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure you want to delete this event?')"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
