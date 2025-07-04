<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

function isValidLicensePlate($plate) {
    return preg_match("/^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i", $plate);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = trim($_POST['license_plate']);

    // Server-side license plate validation
    if (!isValidLicensePlate($license_plate)) {
        echo "<script>
            alert('Invalid license plate format. Use format like: BA 01 CHA 1234');
            window.location.href = '/BCA 6th sem final/userdashboard/add-vehicle.php';
        </script>";
        exit;
    }

    if ($stmt = $conn->prepare("INSERT INTO vehicles (user_id, brand, model, year, license_plate) VALUES (?, ?, ?, ?, ?)")) {
        $stmt->bind_param("issss", $user_id, $brand, $model, $year, $license_plate);

        try {
            if ($stmt->execute()) {
                echo "<script>
                    alert('Vehicle added successfully');
                    window.location.href = '/BCA 6th sem final/userdashboard/user_dashboard.php';
                </script>";
            } else {
                throw new Exception("Execution failed");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                echo "<script>
                    alert('Duplicate entry for license plate.');
                    window.location.href = '/BCA 6th sem final/userdashboard/user_dashboard.php';
                </script>";
            } else {
                echo "<script>
                    alert('Error adding vehicle.');
                    window.location.href = '/BCA 6th sem final/userdashboard/user_dashboard.php';
                </script>";
            }
        }

        $stmt->close();
    } else {
        echo "<script>
            alert('SQL preparation failed.');
            window.location.href = '/BCA 6th sem final/userdashboard/user_dashboard.php';
        </script>";
    }

    $conn->close();
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="CSS/add-vehicle.css">
</head>
<body>
    <form method="POST" action="add-vehicle.php">
        <h2>Add Vehicle</h2>

        <label for="brand">Vehicle Brand:</label>
        <input type="text" id="brand" name="brand" required>

        <label for="model">Vehicle Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="year">Vehicle Year:</label>
        <input type="number" id="year" name="year" min="2005" max="2025" required>

        <label for="license_plate">License Plate:</label>
        <input type="text" id="license_plate" name="license_plate" required>
        <small id="error-message" style="color:red;"></small>

        <input type="submit" value="Add Vehicle">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const licensePlate = document.getElementById('license_plate');
            const errorMessage = document.getElementById('error-message');

            licensePlate.addEventListener('input', function () {
                const plate = this.value.trim();
                const isValid = /^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i.test(plate);
                errorMessage.textContent = isValid ? '' : 'Invalid format (Example: BA 01 CHA 1234)';
            });

            form.addEventListener('submit', function (e) {
                const plate = licensePlate.value.trim();
                if (!/^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i.test(plate)) {
                    errorMessage.textContent = 'Invalid format (Example: BA 01 CHA 1234)';
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>
