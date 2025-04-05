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

// Fetch vehicles for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM vehicles WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Vehicles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table th, table td {
            padding: 12px 15px;
        }

        table th {
            background-color: #007BFF;
            color: #ffffff;
            text-align: center;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table td {
            border: 1px solid #dddddd;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php
if (mysqli_num_rows($result) > 0) {
    echo '<h2>Your Vehicles</h2>';
    echo '<table>';
    echo '<tr><th>S.No.</th><th>Brand</th><th>Model</th><th>Year</th><th>License Plate</th><th>Book Appointment</th><th>Update</th><th>Delete</th></tr>';
    
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $count++ . '</td>';
        echo '<td>' . htmlspecialchars($row['make']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['license_plate']) . '</td>';
        echo '<td><a href="appointment_form.php">Book Appointment</a></td>';
        echo '<td><a href="update_vehicle.php?id=' . $row['id'] . '">Update</a></td>';
        echo '<td><a href="delete_vehicle.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this vehicle?\');">Delete</a></td>';
        echo '</tr>';
    }
    
    echo '</table>';
} else {
    echo "<script>alert('Vehicles not found.')</script>";
}
?>

</body>
</html>
