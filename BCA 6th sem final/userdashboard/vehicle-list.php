<?php
session_start();
require_once "../db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='login-warning'>You are not logged in. Please <a href='../login/login.php'>login</a> to continue.</p>";
    exit;
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $user_id = $_SESSION['user_id'];    
    
    // Verify the vehicle belongs to the user before deleting
    $verify_query = "SELECT id FROM vehicles WHERE id = '$delete_id' AND user_id = '$user_id'";
    $verify_result = mysqli_query($conn, $verify_query);
    
    if (mysqli_num_rows($verify_result) > 0) {
        $delete_query = "DELETE FROM vehicles WHERE id = '$delete_id'";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: http://localhost/BCA%206th%20sem%20final/userdashboard/user_dashboard.php");
            exit;
        }
    }
}

// Fetch vehicles for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM vehicles WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Vehicles</title>
    <link rel="stylesheet" href="CSS/vehicle-list.css">
</head>
<body>
    <div class="container">
        <h2>Your Vehicles</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<table id="vehicleTable">';
            echo '<tr>';
            echo '<th>S.No.</th>';
            echo '<th>Brand</th>';
            echo '<th>Model</th>';
            echo '<th>Year</th>';
            echo '<th>License Plate</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $count . '</td>';
                echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
                echo '<td>' . htmlspecialchars($row['model']) . '</td>';
                echo '<td>' . htmlspecialchars($row['year']) . '</td>';
                echo '<td>' . htmlspecialchars($row['license_plate']) . '</td>';
                echo '<td>';
                echo '<button class="button-link" onclick="window.location.href=\'http://localhost/BCA%206th%20sem%20final/userdashboard/appointment_form.php?vehicle_id=' . $row['id'] . '\'">Book</button>';

                //echo '<button class="button-link" onclick="window.parent.loadContent(\'appointment_form.php?vehicle_id=' . $row['id'] . '\')">Book</button>';
                echo '<button class="button-link" onclick="window.parent.loadContent(\'update_vehicle.php?id=' . $row['id'] . '\')">Update</button>';
                echo '<a href="vehicle-list.php?delete_id=' . $row['id'] . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this vehicle?\')">Delete</a>';
                echo '</td>';
                echo '</tr>';
                $count++;
            }
            echo '</table>';
        } else {
            echo '<p class="no-vehicles">No vehicles found.</p>';
            echo '<button onclick="window.parent.loadContent(\'add-vehicle.php\')" class="add-vehicle-btn">Add Vehicle</button>';
        }
        ?>
    </div>
        <script rel='JS/vehicle-list.js'></script>
</body>
</html>
