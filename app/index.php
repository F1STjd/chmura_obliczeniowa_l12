<?php
/**
 * LEMP Stack Main Page
 * Author: Konrad Nowak
 * Date: June 10, 2025
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMP Stack - Konrad Nowak</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; }
        .header { background: #007acc; color: white; padding: 40px; text-align: center; position: relative; }
        .header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>') repeat-x; }
        .header h1 { margin: 0; font-size: 3em; position: relative; z-index: 1; }
        .header p { margin: 10px 0 0 0; font-size: 1.2em; opacity: 0.9; position: relative; z-index: 1; }
        .content { padding: 40px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin: 30px 0; }
        .card { background: #f8f9fa; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { color: #007acc; margin-top: 0; font-size: 1.5em; }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; }
        .error { color: #dc3545; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545; }
        .info { color: #17a2b8; background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #17a2b8; }
        .btn { display: inline-block; padding: 12px 25px; background: #007acc; color: white; text-decoration: none; border-radius: 6px; margin: 5px; transition: background 0.3s ease; font-weight: 500; }
        .btn:hover { background: #0056b3; transform: translateY(-2px); }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-info { background: #17a2b8; }
        .btn-info:hover { background: #138496; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-item { text-align: center; }
        .stat-value { font-size: 2em; font-weight: bold; color: #007acc; }
        .stat-label { color: #666; font-size: 0.9em; margin-top: 5px; }
        .footer { background: #2c3e50; color: white; padding: 30px; text-align: center; }
        .logo { font-size: 4em; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚀</div>
            <h1>LEMP Stack</h1>
            <p>Linux + Nginx + MySQL + PHP | Author: Konrad Nowak</p>
        </div>
        
        <div class="content">
            <h2>📋 System Information</h2>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-value"><?php echo phpversion(); ?></div>
                    <div class="stat-label">PHP Version</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo gethostname(); ?></div>
                    <div class="stat-label">Container</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo date('H:i:s'); ?></div>
                    <div class="stat-label">Current Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx'; ?></div>
                    <div class="stat-label">Web Server</div>
                </div>
            </div>

            <h2>🗄️ Database Connection Test</h2>
            <?php
            // MySQL connection test
            $mysql_host = 'mysql';
            $mysql_user = 'lemp_user';
            $mysql_pass = 'lemp_pass';
            $mysql_db = 'lemp_db';

            try {
                $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8mb4", $mysql_user, $mysql_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                echo '<div class="success">✅ <strong>MySQL Connection:</strong> SUCCESS</div>';
                
                // Test query
                $stmt = $pdo->query("SELECT VERSION() as mysql_version, COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '$mysql_db'");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo '<div class="info">';
                echo '<strong>Database:</strong> ' . $mysql_db . '<br>';
                echo '<strong>MySQL Version:</strong> ' . $result['mysql_version'] . '<br>';
                echo '<strong>Tables:</strong> ' . $result['table_count'] . '<br>';
                echo '</div>';
                
            } catch (PDOException $e) {
                echo '<div class="error">❌ <strong>MySQL Connection:</strong> FAILED<br>';
                echo '<strong>Error:</strong> ' . $e->getMessage() . '</div>';
            }
            ?>

            <div class="grid">
                <div class="card">
                    <h3>🌐 Web Application</h3>
                    <p>Complete LEMP stack running on Docker containers with Nginx reverse proxy, PHP-FPM, and MySQL database.</p>
                    <a href="database.php" class="btn btn-success">📊 Database Test</a>
                    <a href="phpinfo.php" class="btn btn-info">ℹ️ PHP Info</a>
                </div>
                
                <div class="card">
                    <h3>🗄️ Database Management</h3>
                    <p>Access phpMyAdmin for complete database administration and management interface.</p>
                    <a href="http://localhost:6001" target="_blank" class="btn">🔧 phpMyAdmin</a>
                </div>
                
                <div class="card">
                    <h3>🐳 Container Stack</h3>
                    <p>Four Docker containers working together: Nginx, PHP-FPM, MySQL, and phpMyAdmin.</p>
                    <div class="info" style="margin-top: 15px;">
                        <strong>Services:</strong><br>
                        • Nginx: :4001<br>
                        • phpMyAdmin: :6001<br>
                        • MySQL: :3306<br>
                        • PHP-FPM: :9000
                    </div>
                </div>
            </div>

            <h2>🔧 Loaded PHP Extensions</h2>
            <div class="info">
                <?php
                $extensions = get_loaded_extensions();
                sort($extensions);
                $chunks = array_chunk($extensions, 8);
                foreach ($chunks as $chunk) {
                    echo implode(' • ', $chunk) . '<br>';
                }
                ?>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>LEMP Stack Successfully Deployed!</strong></p>
            <p>Powered by Docker Compose | Created by Konrad Nowak</p>
            <p><em><?php echo date('Y-m-d H:i:s'); ?></em></p>
        </div>
    </div>
</body>
</html>
