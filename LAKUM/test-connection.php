<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKUM - Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            border-left: 4px solid #28a745;
        }
        .error {
            border-left: 4px solid #dc3545;
        }
        .warning {
            border-left: 4px solid #ffc107;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
            font-size: 18px;
        }
        pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .status {
            font-weight: bold;
            font-size: 16px;
        }
        .success .status { color: #28a745; }
        .error .status { color: #dc3545; }
        .warning .status { color: #ffc107; }
    </style>
</head>
<body>
    <h1>🔧 LAKUM System Test</h1>
    
    <?php
    // Test 1: PHP Version
    echo '<div class="test-box success">';
    echo '<h2>✅ PHP Version</h2>';
    echo '<p class="status">PHP ' . phpversion() . ' is running</p>';
    echo '</div>';
    
    // Test 2: Required Extensions
    echo '<div class="test-box ' . (extension_loaded('mysqli') ? 'success' : 'error') . '">';
    echo '<h2>' . (extension_loaded('mysqli') ? '✅' : '❌') . ' MySQL Extension</h2>';
    echo '<p class="status">' . (extension_loaded('mysqli') ? 'MySQLi extension is loaded' : 'MySQLi extension is NOT loaded') . '</p>';
    echo '</div>';
    
    // Test 3: Database Connection
    require_once 'config/database.php';
    
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            echo '<div class="test-box error">';
            echo '<h2>❌ Database Connection</h2>';
            echo '<p class="status">Failed to connect to MySQL</p>';
            echo '<p>Error: ' . $conn->connect_error . '</p>';
            echo '</div>';
        } else {
            echo '<div class="test-box success">';
            echo '<h2>✅ Database Connection</h2>';
            echo '<p class="status">Successfully connected to MySQL</p>';
            echo '</div>';
            
            // Test 4: Database Exists
            $db_check = $conn->select_db(DB_NAME);
            if ($db_check) {
                echo '<div class="test-box success">';
                echo '<h2>✅ Database "' . DB_NAME . '"</h2>';
                echo '<p class="status">Database exists and is accessible</p>';
                
                // Test 5: Tables Exist
                $tables = ['admin', 'events', 'event_images'];
                $missing_tables = [];
                
                foreach ($tables as $table) {
                    $result = $conn->query("SHOW TABLES LIKE '$table'");
                    if ($result->num_rows == 0) {
                        $missing_tables[] = $table;
                    }
                }
                
                if (empty($missing_tables)) {
                    echo '<h2>✅ Database Tables</h2>';
                    echo '<p class="status">All required tables exist</p>';
                    echo '<ul>';
                    foreach ($tables as $table) {
                        echo '<li>' . $table . '</li>';
                    }
                    echo '</ul>';
                    
                    // Test 6: Admin User
                    $admin_check = $conn->query("SELECT COUNT(*) as count FROM admin");
                    $admin_count = $admin_check->fetch_assoc()['count'];
                    
                    if ($admin_count > 0) {
                        echo '<h2>✅ Admin User</h2>';
                        echo '<p class="status">Admin user exists</p>';
                    } else {
                        echo '<h2>⚠️ Admin User</h2>';
                        echo '<p class="status">No admin user found</p>';
                    }
                    
                    // Test 7: Events Count
                    $events_check = $conn->query("SELECT COUNT(*) as count FROM events");
                    $events_count = $events_check->fetch_assoc()['count'];
                    
                    echo '<h2>📊 Events</h2>';
                    echo '<p class="status">' . $events_count . ' event(s) in database</p>';
                    
                } else {
                    echo '<h2>❌ Database Tables</h2>';
                    echo '<p class="status">Missing tables: ' . implode(', ', $missing_tables) . '</p>';
                }
                
                echo '</div>';
            } else {
                echo '<div class="test-box warning">';
                echo '<h2>⚠️ Database "' . DB_NAME . '"</h2>';
                echo '<p class="status">Database does not exist yet</p>';
                echo '<p>Please run the setup script: <a href="config/setup.php">config/setup.php</a></p>';
                echo '</div>';
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        echo '<div class="test-box error">';
        echo '<h2>❌ Error</h2>';
        echo '<p class="status">An error occurred</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
    }
    
    // Test 8: Upload Directories
    $upload_dirs = ['uploads/covers', 'uploads/events'];
    $all_writable = true;
    
    echo '<div class="test-box">';
    echo '<h2>📁 Upload Directories</h2>';
    
    foreach ($upload_dirs as $dir) {
        $exists = is_dir($dir);
        $writable = $exists && is_writable($dir);
        
        if (!$exists) {
            echo '<p>❌ ' . $dir . ' - Does not exist</p>';
            $all_writable = false;
        } elseif (!$writable) {
            echo '<p>⚠️ ' . $dir . ' - Not writable</p>';
            $all_writable = false;
        } else {
            echo '<p>✅ ' . $dir . ' - OK</p>';
        }
    }
    
    if ($all_writable) {
        echo '<p class="status" style="color: #28a745;">All upload directories are ready</p>';
    }
    
    echo '</div>';
    
    // Test 9: API Endpoints
    echo '<div class="test-box">';
    echo '<h2>🔌 API Endpoints</h2>';
    echo '<p>✅ api/get_events.php - ' . (file_exists('api/get_events.php') ? 'Exists' : 'Missing') . '</p>';
    echo '<p>✅ api/get_event.php - ' . (file_exists('api/get_event.php') ? 'Exists' : 'Missing') . '</p>';
    echo '</div>';
    
    // Summary
    echo '<div class="test-box success">';
    echo '<h2>🎉 Next Steps</h2>';
    echo '<ol>';
    echo '<li>If database doesn\'t exist: <a href="config/setup.php">Run Setup Script</a></li>';
    echo '<li>Login to admin panel: <a href="admin/login.php">Admin Login</a></li>';
    echo '<li>View your website: <a href="index.php">Homepage</a></li>';
    echo '</ol>';
    echo '</div>';
    ?>
    
</body>
</html>
