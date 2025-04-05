<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-appointments {
            margin-top: 20px;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-completed {
            background-color: #28a745;
            color: #fff;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Appointments</h2>
        <?php
        session_start();
        require_once "../db.php";

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../login/login.php");
            exit;
        }

        // Fetch appointments for the logged-in user
        $user_id = $_SESSION['user_id'];
        $query = "SELECT appointments.appointment_date, appointments.appointment_time, vehicles.license_plate, vehicles.make AS brand, appointments.status, appointments.service_type 
                  FROM appointments 
                  INNER JOIN vehicles ON appointments.vehicle_id = vehicles.id
                  WHERE appointments.user_id = '$user_id'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Date</th>';
            echo '<th>Time</th>';
            echo '<th>License Plate</th>';
            echo '<th>Brand</th>';
            echo '<th>Service Type</th>'; // Added service type column header
            echo '<th>Status</th>';
            echo '</tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['appointment_date'] . '</td>';
                echo '<td>' . $row['appointment_time'] . '</td>';
                echo '<td>' . htmlspecialchars($row['license_plate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
                echo '<td>' . htmlspecialchars($row['service_type']) . '</td>'; // Display service type
                echo '<td>';
                echo '<span class="status-' . strtolower($row['status']) . '">' . htmlspecialchars($row['status']) . '</span>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p class="no-appointments">No appointments found.</p>';
        }
        ?>
    </div>
</body>
</html>
