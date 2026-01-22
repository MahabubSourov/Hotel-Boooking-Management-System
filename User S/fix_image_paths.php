<?php
/**
 * Database Image Path Fix Script
 * This script updates all image paths in the database to use the correct SITE_URL
 * Run this once to permanently fix image paths in the database
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "Starting database image path update...\n\n";

// Fix hotel images
echo "Updating hotel images...\n";
$result = $conn->query("SELECT id, image FROM hotels WHERE image LIKE '%WBT/hotel_booking%' OR image LIKE '%localhost/hotel_booking%'");
$count = 0;

while ($row = $result->fetch_assoc()) {
    $oldPath = $row['image'];
    // Replace old WBT path
    $newPath = str_replace('/WBT/hotel_booking/', '/hotel_booking/', $oldPath);
    // Replace localhost URLs with current SITE_URL
    $newPath = preg_replace('/https?:\/\/localhost\/hotel_booking/', SITE_URL, $newPath);

    if ($oldPath !== $newPath) {
        $stmt = $conn->prepare("UPDATE hotels SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $newPath, $row['id']);
        $stmt->execute();
        $count++;
        echo "  Updated hotel ID {$row['id']}: $oldPath -> $newPath\n";
    }
}
echo "Updated $count hotel images.\n\n";

// Fix destination images
echo "Updating destination images...\n";
$result = $conn->query("SELECT id, image FROM destinations WHERE image LIKE '%WBT/hotel_booking%' OR image LIKE '%localhost/hotel_booking%'");
$count = 0;

while ($row = $result->fetch_assoc()) {
    $oldPath = $row['image'];
    $newPath = str_replace('/WBT/hotel_booking/', '/hotel_booking/', $oldPath);
    $newPath = preg_replace('/https?:\/\/localhost\/hotel_booking/', SITE_URL, $newPath);

    if ($oldPath !== $newPath) {
        $stmt = $conn->prepare("UPDATE destinations SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $newPath, $row['id']);
        $stmt->execute();
        $count++;
        echo "  Updated destination ID {$row['id']}: $oldPath -> $newPath\n";
    }
}
echo "Updated $count destination images.\n\n";

// Fix room images
echo "Updating room images...\n";
$result = $conn->query("SELECT id, image FROM rooms WHERE image LIKE '%WBT/hotel_booking%' OR image LIKE '%localhost/hotel_booking%'");
$count = 0;

while ($row = $result->fetch_assoc()) {
    $oldPath = $row['image'];
    $newPath = str_replace('/WBT/hotel_booking/', '/hotel_booking/', $oldPath);
    $newPath = preg_replace('/https?:\/\/localhost\/hotel_booking/', SITE_URL, $newPath);

    if ($oldPath !== $newPath) {
        $stmt = $conn->prepare("UPDATE rooms SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $newPath, $row['id']);
        $stmt->execute();
        $count++;
        echo "  Updated room ID {$row['id']}: $oldPath -> $newPath\n";
    }
}
echo "Updated $count room images.\n\n";

echo "Database update complete!\n";
echo "All image paths have been updated to use the correct SITE_URL.\n";
?>