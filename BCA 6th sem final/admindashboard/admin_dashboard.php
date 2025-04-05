<?php
session_start(); // Start the session
if (!isset($_SESSION['logged_in'])) {  // Check if the user is logged in
    header("Location: ../login/login.php");  // Redirect to login page if session is not set
    exit();
}
include '../db.php';

// Fetch all users
$user_query = "SELECT * FROM users";
$user_result = mysqli_query($conn, $user_query);

// Function to delete a user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $delete_query);
    header("Location: admin_dashboard.php");
}

// Function to update user details
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $update_query = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', address='$address', phone='$phone', role='$role' WHERE id = $user_id";
    mysqli_query($conn, $update_query);
    header("Location: admin_dashboard.php");
}

// Fetch all vehicles
$vehicle_query = "SELECT * FROM Vehicles";
$vehicle_result = mysqli_query($conn, $vehicle_query);

// Fetch all appointments
$appointment_query = "SELECT appointments.*, vehicles.license_plate, vehicles.make AS brand FROM Appointments 
                      INNER JOIN vehicles ON appointments.vehicle_id = vehicles.id";
$appointment_result = mysqli_query($conn, $appointment_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css_admin.css">
</head>
<body>
    <div id="container">
        <h1>Admin Dashboard</h1>
        <div id="logout">
        <a href="../logout.php">Logout</a>
        </div>
        <h2>Manage Users</h2>
<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>
    <?php
    if (mysqli_num_rows($user_result) > 0) {
        while ($row = mysqli_fetch_assoc($user_result)) {
            echo "<tr>";
            echo "<td>" . (isset($row['first_name']) ? $row['first_name'] : 'N/A') . "</td>";
            echo "<td>" . (isset($row['last_name']) ? $row['last_name'] : 'N/A') . "</td>";
            echo "<td>" . (isset($row['email']) ? $row['email'] : 'N/A') . "</td>";
            echo "<td>" . (isset($row['address']) ? $row['address'] : 'N/A') . "</td>";
            echo "<td>" . (isset($row['phone']) ? $row['phone'] : 'N/A') . "</td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='delete_user'>Delete</button>";
            echo "</form>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
            echo "<input type='text' name='first_name' value='" . $row['first_name'] . "'>";
            echo "<input type='text' name='last_name' value='" . $row['last_name'] . "'>";
            echo "<input type='text' name='email' value='" . $row['email'] . "'>";
            echo "<input type='text' name='address' value='" . $row['address'] . "'>";
            echo "<input type='text' name='phone' value='" . $row['phone'] . "'>";
            echo "<button type='submit' name='update_user'>Update</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No users found.</td></tr>";
    }
    ?>
</table>

    <h2>Manage Appointments</h2>
<div id="admin-appointment-list">
    <?php
    if (mysqli_num_rows($appointment_result) > 0) {
        echo '<table border=2>';
        echo '<tr>';
        echo '<th>S. No.</th>'; // Add S. No. column header
        echo '<th>Date</th>';
        echo '<th>Time</th>';
        echo '<th>User Name</th>'; // User Name column header
        echo '<th>License Plate</th>';
        echo '<th>Brand</th>';
        echo '<th>Status</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        
        $serial_number = 1; // Initialize serial number
        
        while ($row = mysqli_fetch_assoc($appointment_result)) {
            echo '<tr>';
            echo '<td>' . $serial_number . '</td>'; // Display serial number
            echo '<td>' . $row['appointment_date'] . '</td>';
            echo '<td>' . $row['appointment_time'] . '</td>';
            echo '<td>' . htmlspecialchars($row['user_first_name']) . '</td>'; // Display user's first name
            echo '<td>' . htmlspecialchars($row['license_plate']) . '</td>';
            echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
            echo '<td>';
            echo '<form method="post" action="update_appointment.php">';
            echo '<input type="hidden" name="appointment_id" value="' . $row['id'] . '">';
            echo '<select name="status">';
            echo '<option value="Pending" ' . ($row['status'] == 'Pending' ? 'selected' : '') . '>Pending</option>';
            echo '<option value="Approved" ' . ($row['status'] == 'Approved' ? 'selected' : '') . '>Approved</option>';
            echo '<option value="Canceled" ' . ($row['status'] == 'Canceled' ? 'selected' : '') . '>Canceled</option>';
            echo '</select>';
            echo '<button type="submit" name="update_status">Update</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
            
            $serial_number++; // Increment serial number for the next row
        }
        
        echo '</table>';
    } else {
        echo '<p>No appointments found.</p>';
    }
    ?>
</div>

</body>
</html>
