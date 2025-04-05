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

// Check if vehicle ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: userdashboard/user_dashboard.php.php");
    exit;
}

$vehicle_id = $_GET['id'];

// Fetch vehicle details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM vehicles WHERE id = '$vehicle_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the vehicle exists and belongs to the logged-in user
if (mysqli_num_rows($result) == 1) {
    $vehicle = mysqli_fetch_assoc($result);

    // Handle form submission for updating vehicle details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $license_plate = $_POST['license_plate'];

        // Update vehicle details in the database
        $update_query = "UPDATE vehicles SET make = '$make', model = '$model', year = '$year', license_plate = '$license_plate' WHERE id = '$vehicle_id'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            // Redirect back to the vehicle list page after successful update
            header("Location: userdashboard/user_dashboard.php.php");
            exit;
        } else {
            // Handle update failure
            $error_message = "Failed to update vehicle. Please try again.";
        }
    }
} else {
    // Redirect if the vehicle does not exist or does not belong to the user
    header("Location: your_vehicles.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vehicle</title>
    <style>
        /* Your CSS styles for the edit vehicle page */
    </style>
</head>
<body>

<h2>Edit Vehicle</h2>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="make">Brand</label><br>
    <input type="text" id="make" name="make" value="<?php echo $vehicle['make']; ?>"><br>

    <label for="model">Model:</label><br>
    <input type="text" id="model" name="model" value="<?php echo $vehicle['model']; ?>"><br>

    <label for="year">Year:</label><br>
    <input type="text" id="year" name="year" value="<?php echo $vehicle['year']; ?>"><br>

    <label for="license_plate">License Plate:</label><br>
    <input type="text" id="license_plate" name="license_plate" value="<?php echo $vehicle['license_plate']; ?>"><br>

    <input type="submit" value="Update">
</form>

<?php
// Display error message if update failed
if (isset($error_message)) {
    echo "<p>$error_message</p>";
}
?>

</body>
</html>
