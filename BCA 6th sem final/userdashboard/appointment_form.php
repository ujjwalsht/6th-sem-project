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

// Fetch user's vehicles
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM vehicles WHERE user_id = '$user_id'";
$vehicles = mysqli_query($conn, $query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $service_type = $_POST['service_type']; // Added service type
    
    // Fetch user's first name
    $user_query = "SELECT first_name FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    $first_name = $user_data['first_name'];

    // Insert the appointment into the database with first_name
    $query = "INSERT INTO appointments (user_id, vehicle_id, appointment_date, appointment_time, service_type,user_first_name) 
              VALUES ('$user_id', '$vehicle_id', '$appointment_date', '$appointment_time', '$service_type','$first_name')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Appointment booked successfully.'); window.location.href='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #007BFF;
        }

        form {
            margin: 20px 0;
            font-size: 18px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Book an Appointment</h2>
    <form method="post">
        <label for="vehicle_id">Select Vehicle:</label>
        <select id="vehicle_id" name="vehicle_id" required>
            <?php
            if (mysqli_num_rows($vehicles) > 0) {
                while ($vehicle = mysqli_fetch_assoc($vehicles)) {
                    echo '<option value="' . $vehicle['id'] . '">' . htmlspecialchars($vehicle['make']) . ' ' . htmlspecialchars($vehicle['model']) . ' (' . htmlspecialchars($vehicle['license_plate']) . ')</option>';
                }
            } else {
                echo '<option value="">No vehicles found</option>';
            }
            ?>
        </select>

        <label for="appointment_date">Appointment Date:</label>
        <input type="date" id="appointment_date" name="appointment_date" required>

        <label for="appointment_time">Appointment Time:</label>
        <input type="time" id="appointment_time" name="appointment_time" required>

        <!-- Added service type -->
        <div class="input-group">
            <label for="service_type">Service Type:</label>
            <select id="service_type" name="service_type" required>
                <option value="">Select Service Type</option>
                <option value="Oil Change">Oil Change</option>
                <option value="Brake Service">Brake Service</option>
                <option value="Tire Rotation">Tire Rotation</option>
                <option value="Engine Tune-up">Engine Tune-up</option>
            </select>
        </div>
        
    
        <button type="submit">Book Appointment</button>
    </form>
</body>
</html>
