<?php
/**
 * PHP Information Page
 * LEMP Stack - Detailed PHP Configuration
 * Author: Konrad Nowak
 */

// Custom styling for phpinfo
echo '<style>
    body { font-family: "Segoe UI", Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
    .header { background: #007acc; color: white; padding: 20px; margin: -20px -20px 20px -20px; text-align: center; }
    .header h1 { margin: 0; font-size: 2.5em; }
    .navigation { text-align: center; margin: 20px 0; }
    .btn { display: inline-block; padding: 10px 20px; background: #007acc; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
    .btn:hover { background: #0056b3; }
    .phpinfo-container { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
</style>';

echo '<div class="header">';
echo '<h1>🐘 PHP Configuration Information</h1>';
echo '<p>LEMP Stack - Complete PHP Environment Details</p>';
echo '</div>';

echo '<div class="navigation">';
echo '<a href="index.php" class="btn">🏠 Home</a>';
echo '<a href="database.php" class="btn">🗄️ Database Test</a>';
echo '<a href="http://localhost:6001" target="_blank" class="btn">📊 phpMyAdmin</a>';
echo '</div>';

echo '<div class="phpinfo-container">';

// Display PHP info
phpinfo();

echo '</div>';

echo '<div style="text-align: center; margin: 20px 0; color: #666;">';
echo '<p><em>Generated on: ' . date('Y-m-d H:i:s') . ' | Author: Konrad Nowak</em></p>';
echo '</div>';
?>
