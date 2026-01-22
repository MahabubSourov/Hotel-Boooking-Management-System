<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

echo "<!DOCTYPE html><html><head><title>Quick Image Fix</title>";
echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo ".container{background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo "h2{color:#667eea;border-bottom:2px solid #667eea;padding-bottom:10px;}";
echo ".success{background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin:10px 0;}";
echo ".error{background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin:10px 0;}";
echo ".info{background:#d1ecf1;color:#0c5460;padding:10px;border-radius:5px;margin:10px 0;}";
echo "code{background:#f0f0f0;padding:2px 5px;border-radius:3px;}</style></head><body><div class='container'>";

echo "<h2>🔧 Quick Image Fix Tool</h2>";

$db = Database::getInstance();
$conn = $db->getConnection();

// Step 1: Update hotels table
echo "<h3>Step 1: Checking Hotels Table</h3>";
$result = $conn->query("SELECT COUNT(*) as total FROM hotels WHERE image LIKE '%WBT%' OR image LIKE '%localhost%'");
$row = $result->fetch_assoc();
$needsUpdate = $row['total'];

if ($needsUpdate > 0) {
    echo "<div class='info'>Found {$needsUpdate} hotels with incorrect image paths. Fixing now...</div>";

    // Update hotels
    $conn->query("UPDATE hotels SET image = REPLACE(image, '/WBT/hotel_booking/', '/hotel_booking/')");
    $conn->query("UPDATE hotels SET image = REPLACE(image, 'http://localhost/WBT/hotel_booking', '" . SITE_URL . "')");
    $conn->query("UPDATE hotels SET image = REPLACE(image, 'http://localhost/hotel_booking', '" . SITE_URL . "')");

    echo "<div class='success'>✓ Fixed hotel image paths!</div>";
} else {
    echo "<div class='success'>✓ Hotel images already have correct paths</div>";
}

// Step 2: Update destinations table
echo "<h3>Step 2: Checking Destinations Table</h3>";
$result = $conn->query("SELECT COUNT(*) as total FROM destinations WHERE image LIKE '%WBT%' OR image LIKE '%localhost%'");
$row = $result->fetch_assoc();
$needsUpdate = $row['total'];

if ($needsUpdate > 0) {
    echo "<div class='info'>Found {$needsUpdate} destinations with incorrect image paths. Fixing now...</div>";

    $conn->query("UPDATE destinations SET image = REPLACE(image, '/WBT/hotel_booking/', '/hotel_booking/')");
    $conn->query("UPDATE destinations SET image = REPLACE(image, 'http://localhost/WBT/hotel_booking', '" . SITE_URL . "')");
    $conn->query("UPDATE destinations SET image = REPLACE(image, 'http://localhost/hotel_booking', '" . SITE_URL . "')");

    echo "<div class='success'>✓ Fixed destination image paths!</div>";
} else {
    echo "<div class='success'>✓ Destination images already have correct paths</div>";
}

// Step 3: Update rooms table
echo "<h3>Step 3: Checking Rooms Table</h3>";
$result = $conn->query("SELECT COUNT(*) as total FROM rooms WHERE image LIKE '%WBT%' OR image LIKE '%localhost%'");
$row = $result->fetch_assoc();
$needsUpdate = $row['total'];

if ($needsUpdate > 0) {
    echo "<div class='info'>Found {$needsUpdate} rooms with incorrect image paths. Fixing now...</div>";

    $conn->query("UPDATE rooms SET image = REPLACE(image, '/WBT/hotel_booking/', '/hotel_booking/')");
    $conn->query("UPDATE rooms SET image = REPLACE(image, 'http://localhost/WBT/hotel_booking', '" . SITE_URL . "')");
    $conn->query("UPDATE rooms SET image = REPLACE(image, 'http://localhost/hotel_booking', '" . SITE_URL . "')");

    echo "<div class='success'>✓ Fixed room image paths!</div>";
} else {
    echo "<div class='success'>✓ Room images already have correct paths</div>";
}

// Step 4: Show sample paths
echo "<h3>Step 4: Current Image Paths</h3>";
echo "<p>Here are some sample image paths from your database:</p>";

$hotels = $conn->query("SELECT name, image FROM hotels LIMIT 3");
if ($hotels->num_rows > 0) {
    echo "<strong>Hotels:</strong><ul>";
    while ($hotel = $hotels->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($hotel['name']) . ": <code>" . htmlspecialchars($hotel['image']) . "</code></li>";
    }
    echo "</ul>";
}

echo "<h3>✅ All Done!</h3>";
echo "<div class='success'><strong>Next Steps:</strong><ol>";
echo "<li>Clear your browser cache (Ctrl + Shift + Delete or Ctrl + F5)</li>";
echo "<li>Go to your website: <a href='" . SITE_URL . "'>" . SITE_URL . "</a></li>";
echo "<li>Images should now display correctly!</li>";
echo "</ol></div>";

echo "<p style='text-align:center;margin-top:30px;'>";
echo "<a href='" . SITE_URL . "/test_images.php' style='background:#667eea;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Test Images →</a> ";
echo "<a href='" . SITE_URL . "' style='background:#764ba2;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin-left:10px;'>Go to Home →</a>";
echo "</p>";

echo "</div></body></html>";
?>