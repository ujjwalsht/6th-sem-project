<?php
require_once 'admin_auth.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
        throw new Exception('Invalid request');
    }

    $id = (int)$_GET['id'];
    if ($id <= 0) {
        throw new Exception('Invalid appointment ID');
    }

    // Check if appointment exists
    $check_stmt = $conn->prepare("SELECT id FROM appointments WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows === 0) {
        throw new Exception('Appointment not found');
    }
    
    // Delete the appointment
    $delete_stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $delete_stmt->close();
    $check_stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>