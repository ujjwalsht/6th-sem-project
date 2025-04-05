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
    
    // Fetch the vehicle data
    $query = "SELECT * FROM vehicles WHERE id = '$vehicle_id' AND user_id = '{$_SESSION['user_id']}'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $vehicle = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Vehicle not found.'); window.location.href='user_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='user_dashboard.php';</script>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];
    
    // Update the vehicle data
    $query = "UPDATE vehicles SET make = '$make', model = '$model', year = '$year', license_plate = '$license_plate' WHERE id = '$vehicle_id' AND user_id = '{$_SESSION['user_id']}'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Vehicle updated successfully.'); window.location.href='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating vehicle.'); window.location.href='user_dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Vehicle</title>
    <style>
    body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 700px;
            margin: 0;
        }
       
        h2 {
            color: #007bff;
        }
        form {
            background-color: #fff; /* Optional: Add a background color for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 300px; /* Optional: Set a fixed width for the form */
            text-align: left; /* Align form elements to the left within the container */
        }
        label, input {
            display: block;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        button[type="submit"] {
    background-color: #007bff; 
    color: #ffffff; 
    padding: 10px 20px;
    border-radius: 5px; 
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #0056b3; 
}
    </style>
</head>
<body>
        <form method="post">
        <h2>Update Vehicle</h2>
        <label for="make">Brand:</label>
        <input type="text" id="make" name="make" value="<?php echo htmlspecialchars($vehicle['make']); ?>" required><br><br>
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required><br><br>
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($vehicle['year']); ?>" required><br><br>
        <label for="license_plate">License Plate:</label>
        <input type="text" id="license_plate" name="license_plate" value="<?php echo htmlspecialchars($vehicle['license_plate']); ?>" required><br><br>
        <button type="submit">Update Vehicle</button>
    </form>
</body>
</html>
