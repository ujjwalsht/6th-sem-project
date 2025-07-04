<?php
require_once 'admin_auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'error' => 'Invalid request method']));
}

$appointmentId = $_POST['id'] ?? null;
$newStatus = $_POST['status'] ?? null;

if (!$appointmentId || !$newStatus) {
    die(json_encode(['success' => false, 'error' => 'Missing parameters']));
}

// Validate status
$allowedStatuses = ['Pending', 'Confirmed', 'Cancelled'];
if (!in_array($newStatus, $allowedStatuses)) {
    die(json_encode(['success' => false, 'error' => 'Invalid status']));
}

// Update in database
$query = "UPDATE appointments SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $newStatus, $appointmentId);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>