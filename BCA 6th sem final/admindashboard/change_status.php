<?php
session_start();
include '../db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect or handle the case when admin is not logged in
    // For example, redirect to the admin login page
    header("Location: ../login/login.php");
    exit; // Stop further execution
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Prepare and execute SQL query to update appointment status
    $sql = "UPDATE appointments SET status = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $appointment_id);
    $stmt->execute();

    // Redirect back to the previous page after updating status
    header("Location: admin_dashboard.php");
    exit;
} else {
    // Handle the case when form is not submitted or required parameters are missing
    // For example, display an error message or redirect to a different page
    echo "Error: Form submission failed.";
    exit; // Stop further execution
}
?>
