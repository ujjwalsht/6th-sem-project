<?php
session_start();
require_once "../db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the appointment belongs to the user before deleting
    $verify_query = "SELECT id FROM appointments WHERE id = '$delete_id' AND user_id = '$user_id'";
    $verify_result = mysqli_query($conn, $verify_query);
    
    if (mysqli_num_rows($verify_result) > 0) {
        $delete_query = "DELETE FROM appointments WHERE id = '$delete_id'";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: http://localhost/BCA%206th%20sem%20final/userdashboard/user_dashboard.php");
            exit;
        }
    }
}   

// Fetch appointments for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, vehicles.license_plate, vehicles.brand AS brand, appointments.status, appointments.service_type 
          FROM appointments 
          INNER JOIN vehicles ON appointments.vehicle_id = vehicles.id
          WHERE appointments.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Appointments</title>
    <link rel="stylesheet" href="CSS/appointment_list.css">
</head>
<body>
    <div class="container">
        <h2>Your Appointments</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Date</th>';
            echo '<th>Time</th>';
            echo '<th>License Plate</th>';
            echo '<th>Brand</th>';
            echo '<th>Service Type</th>';
            echo '<th>Status</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['appointment_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['appointment_time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['license_plate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
                echo '<td>' . htmlspecialchars($row['service_type']) . '</td>';
                echo '<td><span class="status-' . strtolower($row['status']) . '">' . htmlspecialchars($row['status']) . '</span></td>';
                echo '<td><a href="appointment-list.php?delete_id=' . $row['id'] . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this appointment?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p class="no-appointments">No appointments found.</p>';
        }
        ?>
    </div>
        <script rel='JS/appointment-list.js'></script>
</body>
</html>