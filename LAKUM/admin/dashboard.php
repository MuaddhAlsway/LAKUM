<?php
require_once 'auth_check.php';
require_once '../config/database.php';

$conn = getDBConnection();

// Get statistics
$total_events = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$upcoming_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE()")->fetch_assoc()['count'];
$past_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE event_date < CURDATE()")->fetch_assoc()['count'];

// Get recent events
$recent_events = $conn->query("SELECT * FROM events ORDER BY created_at DESC LIMIT 5");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LAKUM</title>
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
                <a href="dashboard.php" class="active"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a href="events.php"><i class="ri-calendar-event-line"></i> Events</a>
                <a href="logout.php"><i class="ri-logout-box-line"></i> Logout</a>
            </nav>
        </aside>
        
        <main class="main-content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </header>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-calendar-line"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_events; ?></h3>
                        <p>Total Events</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-calendar-check-line"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $upcoming_events; ?></h3>
                        <p>Upcoming Events</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-calendar-close-line"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $past_events; ?></h3>
                        <p>Past Events</p>
                    </div>
                </div>
            </div>
            
            <div class="recent-events">
                <h2>Recent Events</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($event = $recent_events->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                            <td>
                                <span class="badge <?php echo $event['event_date'] >= date('Y-m-d') ? 'upcoming' : 'past'; ?>">
                                    <?php echo $event['event_date'] >= date('Y-m-d') ? 'Upcoming' : 'Past'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn-small">Edit</a>
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
