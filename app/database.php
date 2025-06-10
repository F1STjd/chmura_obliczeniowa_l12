<?php
/**
 * Database Test & Management Page
 * LEMP Stack - Advanced Database Testing
 * Author: Konrad Nowak
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMP Stack - Database Test</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #007acc; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #17a2b8; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007acc; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .btn { display: inline-block; padding: 10px 20px; background: #007acc; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007acc; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ LEMP Stack - Database Management</h1>
        
        <?php
        // Database connection parameters
        $mysql_host = 'mysql';
        $mysql_user = 'lemp_user';
        $mysql_pass = 'lemp_pass';
        $mysql_db = 'lemp_db';
        
        try {
            $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8mb4", $mysql_user, $mysql_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            echo '<div class="success">✅ <strong>Database Connection Successful!</strong></div>';
            
            // Get MySQL version and info
            $stmt = $pdo->query("SELECT VERSION() as mysql_version, NOW() as current_time, DATABASE() as current_db");
            $info = $stmt->fetch();
            
            echo '<div class="info">';
            echo '<strong>MySQL Version:</strong> ' . $info['mysql_version'] . '<br>';
            echo '<strong>Current Database:</strong> ' . $info['current_db'] . '<br>';
            echo '<strong>Server Time:</strong> ' . $info['current_time'] . '<br>';
            echo '</div>';
            
            // Database statistics
            echo '<h2>📊 Database Statistics</h2>';
            
            $tables = ['users', 'products', 'user_permissions', 'app_settings'];
            $stats = [];
            
            foreach ($tables as $table) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                    $result = $stmt->fetch();
                    $stats[$table] = $result['count'];
                } catch (Exception $e) {
                    $stats[$table] = 'N/A';
                }
            }
            
            echo '<div class="stats">';
            foreach ($stats as $table => $count) {
                echo '<div class="stat-card">';
                echo '<div class="stat-number">' . $count . '</div>';
                echo '<div>' . ucfirst(str_replace('_', ' ', $table)) . '</div>';
                echo '</div>';
            }
            echo '</div>';
            
            // Display Users Table
            echo '<h2>👥 Users Table</h2>';
            $stmt = $pdo->query("SELECT id, username, email, first_name, last_name, created_at, is_active FROM users ORDER BY created_at DESC LIMIT 10");
            $users = $stmt->fetchAll();
            
            if ($users) {
                echo '<table>';
                echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Name</th><th>Created</th><th>Status</th></tr>';
                foreach ($users as $user) {
                    $status = $user['is_active'] ? '✅ Active' : '❌ Inactive';
                    $name = trim($user['first_name'] . ' ' . $user['last_name']);
                    echo '<tr>';
                    echo '<td>' . $user['id'] . '</td>';
                    echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($name) . '</td>';
                    echo '<td>' . date('Y-m-d H:i', strtotime($user['created_at'])) . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<div class="info">No users found in database.</div>';
            }
            
            // Display Products Table
            echo '<h2>🛍️ Products Table</h2>';
            $stmt = $pdo->query("SELECT id, name, price, category, stock_quantity FROM products ORDER BY created_at DESC LIMIT 10");
            $products = $stmt->fetchAll();
            
            if ($products) {
                echo '<table>';
                echo '<tr><th>ID</th><th>Product Name</th><th>Price</th><th>Category</th><th>Stock</th></tr>';
                foreach ($products as $product) {
                    echo '<tr>';
                    echo '<td>' . $product['id'] . '</td>';
                    echo '<td>' . htmlspecialchars($product['name']) . '</td>';
                    echo '<td>$' . number_format($product['price'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($product['category']) . '</td>';
                    echo '<td>' . $product['stock_quantity'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<div class="info">No products found in database.</div>';
            }
            
            // Show table structure
            echo '<h2>🏗️ Database Structure</h2>';
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll();
            
            echo '<div class="info"><strong>Tables in database:</strong><br>';
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                echo '• ' . $tableName . '<br>';
            }
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="error">❌ <strong>Database Connection Failed!</strong><br>';
            echo 'Error: ' . $e->getMessage() . '</div>';
        }
        ?>
        
        <h2>🔗 Navigation</h2>
        <a href="index.php" class="btn">🏠 Home Page</a>
        <a href="http://localhost:6001" target="_blank" class="btn">🗄️ phpMyAdmin</a>
        <a href="phpinfo.php" class="btn">ℹ️ PHP Info</a>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center; color: #666;">
            <em>LEMP Stack Database Test Page - Powered by Docker Compose</em><br>
            <small>Author: Konrad Nowak | Date: <?php echo date('Y-m-d H:i:s'); ?></small>
        </p>
    </div>
</body>
</html>
