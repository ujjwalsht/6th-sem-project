<?php
session_start();
include '../db.php';

// Debugging: Check session ID and variables
error_log("Session ID: " . session_id());
error_log("Session Variables: " . print_r($_SESSION, true));

// if (!isset($_SESSION['id'])) {
//     // Instead of redirecting, return an error message
//     echo "<p>You are not logged in. Please <a href='../login/login.php'>login</a> to continue.</p>";
//     exit;
// }

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];

    // Prepare and bind
    if ($stmt = $conn->prepare("INSERT INTO vehicles (user_id, make, model, year, license_plate) VALUES (?, ?, ?, ?, ?)")) {
        $stmt->bind_param("issss", $user_id, $make, $model, $year, $license_plate);

        try {
            if ($stmt->execute()) {
                echo "<script>
                 alert('Vehicle added successfully');
                 window.location.href = 'user_dashboard.php';
                </script>";

            } else {
                throw new Exception("Error executing the SQL statement.");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                echo "<script> alert('Duplicate entry for license plate. Please use a different license plate.');</script>";
            } else {
                echo "<script> alert('Error adding vehicle. Please try again!');</script>";
            }
        }
        $stmt->close();
    } else {
        echo "<script> alert('Failed to prepare the SQL statement.');</script>";
    }

    $conn->close();
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #007BFF;
        }
        form {
            justify-content: center;
            background-color: #fff; /* Optional: Add a background color for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            width: 300px; /* Optional: Set a fixed width for the form */
        }
        label, input {
            display: block;
            margin: 5px 0;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<form method="POST" action="add-vehicle.php">
<h2>Add Vehicle</h2>
    <label for="make">Vehicle Brand:</label>
    <input type="text" id="make" name="make" required>
    <br>
    <label for="model">Vehicle Model:</label>
    <input type="text" id="model" name="model" required>
    <br>
    <label for="year">Vehicle Year:</label>
    <input type="number" id="year" name="year" min="1990" max="2025" required>
    <br>
    <label for="license_plate">License Plate:</label>
    <input type="text" id="license_plate" name="license_plate" required>
    <br>
    <input type="submit" value="Add Vehicle">
</form>
</body>
</html>
<?php
}
?>
