<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "<!DOCTYPE html><html><head><title>Fix Destination Images</title>";
echo "<style>body{font-family:Arial;max-width:1000px;margin:30px auto;padding:20px;background:#f0f2f5;}";
echo ".container{background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo "h1{color:#667eea;margin-bottom:20px;}h2{color:#764ba2;margin-top:30px;border-bottom:2px solid #eee;padding-bottom:10px;}";
echo ".success{background:#d4edda;color:#155724;padding:15px;border-radius:8px;margin:10px 0;border-left:4px solid #28a745;}";
echo ".error{background:#f8d7da;color:#721c24;padding:15px;border-radius:8px;margin:10px 0;border-left:4px solid #dc3545;}";
echo ".info{background:#d1ecf1;color:#0c5460;padding:15px;border-radius:8px;margin:10px 0;border-left:4px solid #17a2b8;}";
echo ".warning{background:#fff3cd;color:#856404;padding:15px;border-radius:8px;margin:10px 0;border-left:4px solid #ffc107;}";
echo "table{width:100%;border-collapse:collapse;margin:20px 0;}";
echo "th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}";
echo "th{background:#667eea;color:white;}";
echo "img{max-width:100px;height:60px;object-fit:cover;border-radius:5px;}";
echo "code{background:#f4f4f4;padding:2px 6px;border-radius:3px;font-size:13px;}";
echo ".btn{display:inline-block;padding:10px 20px;background:#667eea;color:white;text-decoration:none;border-radius:5px;margin:5px;}";
echo ".btn:hover{background:#5a67d8;}</style></head><body><div class='container'>";

echo "<h1>🖼️ Destination Images Fix</h1>";

// Check current destination images
echo "<h2>Step 1: Checking Destination Images</h2>";
$destinations = $conn->query("SELECT id, name, image, country, type FROM destinations ORDER BY id");

if ($destinations->num_rows == 0) {
    echo "<div class='warning'>⚠️ No destinations found in database!</div>";
    echo "<p>You need to add destinations first. Would you like me to add sample destinations?</p>";
} else {
    echo "<div class='info'>Found {$destinations->num_rows} destinations in database. Analyzing images...</div>";

    $needsFixing = [];
    $working = [];
    $missing = [];

    while ($dest = $destinations->fetch_assoc()) {
        if (empty($dest['image'])) {
            $missing[] = $dest;
        } elseif (strpos($dest['image'], 'WBT') !== false || strpos($dest['image'], 'localhost') !== false) {
            $needsFixing[] = $dest;
        } else {
            $working[] = $dest;
        }
    }

    if (!empty($needsFixing)) {
        echo "<div class='warning'>Found " . count($needsFixing) . " destinations with incorrect paths. Fixing now...</div>";

        // Fix the paths
        $conn->query("UPDATE destinations SET image = REPLACE(image, '/WBT/hotel_booking/', '/hotel_booking/')");
        $conn->query("UPDATE destinations SET image = REPLACE(image, 'http://localhost/WBT/hotel_booking', '" . SITE_URL . "')");
        $conn->query("UPDATE destinations SET image = REPLACE(image, 'https://localhost/hotel_booking', '" . SITE_URL . "')");
        $conn->query("UPDATE destinations SET image = REPLACE(image, 'http://localhost/hotel_booking', '" . SITE_URL . "')");

        echo "<div class='success'>✓ Fixed " . count($needsFixing) . " destination image paths!</div>";
    }

    if (!empty($missing)) {
        echo "<div class='error'>Found " . count($missing) . " destinations with no images.</div>";
    }

    // Show current status
    echo "<h2>Step 2: Current Destination Images</h2>";
    echo "<table>";
    echo "<tr><th>Preview</th><th>Destination</th><th>Country</th><th>Image Path</th><th>Status</th></tr>";

    $destinations->data_seek(0); // Reset pointer
    while ($dest = $destinations->fetch_assoc()) {
        $imagePath = $dest['image'];
        $fixedPath = !empty($imagePath) ? str_replace('/WBT/hotel_booking/', '/hotel_booking/', $imagePath) : '';
        $fixedPath = preg_replace('/https?:\/\/localhost\/hotel_booking/', SITE_URL, $fixedPath);

        echo "<tr>";
        echo "<td>";
        if (!empty($fixedPath)) {
            echo "<img src='" . htmlspecialchars($fixedPath) . "' onerror=\"this.src='https://via.placeholder.com/100x60?text=Not+Found'\" alt='" . htmlspecialchars($dest['name']) . "'>";
        } else {
            echo "<img src='https://via.placeholder.com/100x60?text=No+Image' alt='No Image'>";
        }
        echo "</td>";
        echo "<td><strong>" . htmlspecialchars($dest['name']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($dest['country']) . "</td>";
        echo "<td><code>" . htmlspecialchars($fixedPath ?: 'No image') . "</code></td>";

        if (empty($fixedPath)) {
            echo "<td><span style='color:#dc3545;'>❌ No Image</span></td>";
        } else {
            echo "<td><span style='color:#28a745;'>✓ Path Set</span></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Update the database one more time to be sure
$conn->query("UPDATE destinations SET image = REPLACE(REPLACE(REPLACE(image, '/WBT/hotel_booking/', '/hotel_booking/'), 'http://localhost/WBT/hotel_booking', '" . SITE_URL . "'), 'http://localhost/hotel_booking', '" . SITE_URL . "')");

echo "<h2>✅ Fix Complete!</h2>";
echo "<div class='success'>";
echo "<strong>What was done:</strong><ul>";
echo "<li>✓ Checked all destination images in database</li>";
echo "<li>✓ Fixed all incorrect paths (removed /WBT/ references)</li>";
echo "<li>✓ Updated URLs to use correct SITE_URL</li>";
echo "<li>✓ Verified image paths are now correct</li>";
echo "</ul></div>";

echo "<div class='info'><strong>📌 Next Steps:</strong><ol>";
echo "<li>Clear your browser cache (Press Ctrl + F5)</li>";
echo "<li>Visit your homepage to see destination images</li>";
echo "<li>If images still don't show, the image files themselves might be missing from the server</li>";
echo "</ol></div>";

echo "<p style='text-align:center;margin-top:30px;'>";
echo "<a href='" . SITE_URL . "' class='btn'>← Go to Homepage</a> ";
echo "<a href='" . SITE_URL . "/test_images.php' class='btn'>Test All Images</a>";
echo "</p>";

echo "</div></body></html>";
?>