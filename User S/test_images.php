<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$conn = $db->getConnection();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Diagnostic Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        h2 {
            color: #764ba2;
            margin-top: 30px;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 10px;
        }

        .status.ok {
            background: #d4edda;
            color: #155724;
        }

        .status.error {
            background: #f8d7da;
            color: #721c24;
        }

        .image-test {
            border: 2px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 20px;
            align-items: center;
        }

        .image-test img {
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .image-info {
            font-size: 14px;
        }

        .image-info strong {
            color: #667eea;
        }

        .path {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            margin: 5px 0;
        }

        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .grid-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }

        .grid-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .grid-item p {
            margin: 10px 0 0 0;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔍 Image Diagnostic Tool</h1>
        <p>This tool checks all images in your hotel booking system to identify rendering issues.</p>

        <!-- Configuration Check -->
        <div class="section">
            <h2>⚙️ Configuration Status</h2>
            <p><strong>SITE_URL:</strong> <code class="path"><?php echo SITE_URL; ?></code>
                <span class="status ok">✓ SET</span>
            </p>
            <p><strong>Document Root:</strong> <code class="path"><?php echo __DIR__; ?></code></p>
            <p><strong>Assets Directory:</strong>
                <?php
                $assetsDir = __DIR__ . '/assets/images';
                if (is_dir($assetsDir)) {
                    echo '<span class="status ok">✓ EXISTS</span>';
                } else {
                    echo '<span class="status error">✗ NOT FOUND</span>';
                }
                ?>
            </p>
        </div>

        <!-- Payment Icons Test -->
        <div class="section">
            <h2>💳 Payment Icons Test</h2>
            <div class="grid">
                <?php
                $paymentIcons = ['card.png', 'bkash.png', 'nagad.png'];
                foreach ($paymentIcons as $icon) {
                    $localPath = __DIR__ . '/assets/images/' . $icon;
                    $webPath = SITE_URL . '/assets/images/' . $icon;
                    $fixedPath = fixImagePath($webPath);
                    $exists = file_exists($localPath);
                    ?>
                    <div class="grid-item">
                        <img src="<?php echo $fixedPath; ?>" alt="<?php echo $icon; ?>"
                            onerror="this.src='https://via.placeholder.com/100?text=ERROR'">
                        <p><strong>
                                <?php echo $icon; ?>
                            </strong></p>
                        <p>
                            <?php echo $exists ? '<span class="status ok">✓ Found</span>' : '<span class="status error">✗ Missing</span>'; ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Hotel Images Test -->
        <div class="section">
            <h2>🏨 Hotel Images Test</h2>
            <?php
            $hotels = $conn->query("SELECT id, name, image FROM hotels LIMIT 5");
            if ($hotels->num_rows > 0) {
                while ($hotel = $hotels->fetch_assoc()) {
                    $rawPath = $hotel['image'];
                    $fixedPath = fixImagePath($rawPath);
                    $localPath = str_replace(SITE_URL, __DIR__, $fixedPath);
                    $exists = @getimagesize($fixedPath);
                    ?>
                    <div class="image-test">
                        <img src="<?php echo $fixedPath; ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>"
                            onerror="this.src='https://via.placeholder.com/200x150?text=Image+Not+Found'">
                        <div class="image-info">
                            <p><strong>Hotel:</strong>
                                <?php echo htmlspecialchars($hotel['name']); ?>
                            </p>
                            <p><strong>Database Path:</strong></p>
                            <div class="path">
                                <?php echo htmlspecialchars($rawPath); ?>
                            </div>
                            <p><strong>Fixed Path:</strong></p>
                            <div class="path">
                                <?php echo htmlspecialchars($fixedPath); ?>
                            </div>
                            <p><strong>Status:</strong>
                                <?php echo $exists ? '<span class="status ok">✓ Loads</span>' : '<span class="status error">✗ Cannot Load</span>'; ?>
                            </p>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo '<p>No hotels found in database.</p>';
            }
            ?>
        </div>

        <!-- Destination Images Test -->
        <div class="section">
            <h2>🌍 Destination Images Test</h2>
            <div class="grid">
                <?php
                $destinations = $conn->query("SELECT id, name, image FROM destinations LIMIT 6");
                if ($destinations->num_rows > 0) {
                    while ($dest = $destinations->fetch_assoc()) {
                        $fixedPath = fixImagePath($dest['image']);
                        ?>
                        <div class="grid-item">
                            <img src="<?php echo $fixedPath; ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>"
                                onerror="this.src='https://via.placeholder.com/150?text=Not+Found'">
                            <p><strong>
                                    <?php echo htmlspecialchars($dest['name']); ?>
                                </strong></p>
                        </div>
                    <?php
                    }
                } else {
                    echo '<p>No destinations found in database.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Room Images Test -->
        <div class="section">
            <h2>🛏️ Room Images Test</h2>
            <div class="grid">
                <?php
                $rooms = $conn->query("SELECT id, room_type, image FROM rooms LIMIT 6");
                if ($rooms->num_rows > 0) {
                    while ($room = $rooms->fetch_assoc()) {
                        $fixedPath = fixImagePath($room['image']);
                        ?>
                        <div class="grid-item">
                            <img src="<?php echo $fixedPath; ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>"
                                onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                            <p><strong>
                                    <?php echo htmlspecialchars($room['room_type']); ?>
                                </strong></p>
                        </div>
                    <?php
                    }
                } else {
                    echo '<p>No rooms found in database.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="section">
            <h2>💡 Next Steps</h2>
            <ol>
                <li>If images show "Image Not Found" placeholder, the image files are missing from the server</li>
                <li>Check if the "Database Path" contains old paths like "/WBT/hotel_booking"</li>
                <li>If so, run <code>fix_image_paths.php</code> to update the database</li>
                <li>Make sure all image files exist in the <code>/assets/images/</code> directory</li>
                <li>The "Fixed Path" shows what path is being used after the fixImagePath() function</li>
            </ol>
        </div>

        <p style="margin-top: 30px; text-align: center; color: #666;">
            <a href="<?php echo SITE_URL; ?>/index.php" style="color: #667eea;">← Back to Home</a> |
            <a href="<?php echo SITE_URL; ?>/fix_image_paths.php" style="color: #667eea;">Run Database Fix →</a>
        </p>
    </div>
</body>

</html>