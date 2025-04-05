<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $update_query = "UPDATE Appointments SET status='$status' WHERE id=$appointment_id";
    if (mysqli_query($conn, $update_query)) {
        // Redirect back to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
} else {
    // Redirect to admin dashboard if accessed directly without POST data
    header("Location: admin_dashboard.php");
    exit();
}
?>
