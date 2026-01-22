<?php
// Start output buffering to prevent any warnings from corrupting JSON
ob_start();

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Session.php';

// Clean output buffer
ob_end_clean();

if (!Session::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$userId = Session::get('user_id');
$hotelId = $data['hotel_id'];
$roomId = $data['room_id'];
$checkIn = $data['check_in'];
$checkOut = $data['check_out'];
$guests = $data['guests'];
$totalAmount = $data['total_amount'];
$paymentMethod = $data['payment_method'];

$db = Database::getInstance();
$conn = $db->getConnection();

// Generate unique reference
$reference = 'BK-' . strtoupper(substr(uniqid(), -8));

$conn->begin_transaction();

try {
    // 1. Create Booking
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, hotel_id, room_id, check_in, check_out, total_amount, status, booking_reference) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)
    ");
    $stmt->bind_param("iiissds", $userId, $hotelId, $roomId, $checkIn, $checkOut, $totalAmount, $reference);
    $stmt->execute();
    $bookingId = $conn->insert_id;

    // 2. Create Payment Record (Placeholder)
    $stmt = $conn->prepare("
        INSERT INTO payments (booking_id, amount, payment_method, status, transaction_id) 
        VALUES (?, ?, ?, 'pending', ?)
    ");
    $transId = 'TRX-' . time();
    $stmt->bind_param("idss", $bookingId, $totalAmount, $paymentMethod, $transId);
    $stmt->execute();

    // 3. Update Room Availability
    $conn->query("UPDATE rooms SET available_rooms = available_rooms - 1 WHERE id = $roomId");

    $conn->commit();
    echo json_encode(['success' => true, 'booking_id' => $bookingId]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
