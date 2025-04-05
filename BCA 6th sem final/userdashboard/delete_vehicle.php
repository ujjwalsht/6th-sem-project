<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Include database connection
include '../db.php';

// Check if the id is set
if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];
    
    // Prepare and execute delete query
    $query = "DELETE FROM vehicles WHERE id = '$vehicle_id' AND user_id = '{$_SESSION['user_id']}'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Vehicle deleted successfully.'); window.location.href='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting vehicle.'); window.location.href='user_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='user_dashboard.php';</script>";
}
?>
