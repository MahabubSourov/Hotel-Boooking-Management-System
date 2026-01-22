<?php
/**
 * Quick User Creation Script
 * Use this to quickly add a new user to the system
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/Database.php';

// ========================================
// CONFIGURE YOUR NEW USER HERE
// ========================================
$newUser = [
    'name' => 'John Doe',              // Change this to the user's name
    'email' => 'john@example.com',     // Change this to the user's email
    'phone' => '01712345678',          // Change this to the user's phone
    'password' => 'password123',       // Change this to the desired password
    'role' => 'user'                   // 'user' or 'admin'
];
// ========================================

$db = Database::getInstance();
$conn = $db->getConnection();

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $newUser['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("❌ Error: A user with email '{$newUser['email']}' already exists!");
}

// Hash the password
$hashedPassword = password_hash($newUser['password'], PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("
    INSERT INTO users (name, email, phone, password, role, created_at) 
    VALUES (?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param(
    "sssss",
    $newUser['name'],
    $newUser['email'],
    $newUser['phone'],
    $hashedPassword,
    $newUser['role']
);

if ($stmt->execute()) {
    $userId = $conn->insert_id;
    echo "✅ <strong>User created successfully!</strong><br><br>";
    echo "<div style='background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; font-family: Arial, sans-serif;'>";
    echo "<h3 style='margin-top: 0; color: #667eea;'>User Details:</h3>";
    echo "<table style='width: 100%;'>";
    echo "<tr><td><strong>User ID:</strong></td><td>{$userId}</td></tr>";
    echo "<tr><td><strong>Name:</strong></td><td>{$newUser['name']}</td></tr>";
    echo "<tr><td><strong>Email:</strong></td><td>{$newUser['email']}</td></tr>";
    echo "<tr><td><strong>Phone:</strong></td><td>{$newUser['phone']}</td></tr>";
    echo "<tr><td><strong>Role:</strong></td><td>" . ucfirst($newUser['role']) . "</td></tr>";
    echo "<tr><td><strong>Password:</strong></td><td>{$newUser['password']}</td></tr>";
    echo "</table>";
    echo "</div><br>";
    echo "<p>You can now login with this email and password at: <a href='" . SITE_URL . "/auth/login.php'>" . SITE_URL . "/auth/login.php</a></p>";
    echo "<br><p style='color: #666;'><em>Note: For security, delete this file (add_user.php) after creating your users.</em></p>";
} else {
    echo "❌ Error creating user: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .info {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #ffc107;
        }

        table {
            border-collapse: collapse;
            margin: 10px 0;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📝 How to Add More Users</h2>
        <ol>
            <li>Open this file: <code>add_user.php</code></li>
            <li>Edit the user details at the top (lines 11-17)</li>
            <li>Save the file</li>
            <li>Refresh this page in your browser</li>
            <li>Each refresh will create a new user with the details you specified</li>
        </ol>

        <div class="info">
            <strong>⚠️ Security Tip:</strong> Delete this file after you're done creating users, or move it outside your
            web directory.
        </div>
    </div>
</body>

</html>