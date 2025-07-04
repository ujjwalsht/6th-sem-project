<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$vehicle_id = $_GET['id'] ?? null;

// Fetch existing vehicle data
if ($vehicle_id) {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $vehicle_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    
    if (!$vehicle) {
        $_SESSION['error_message'] = 'Vehicle not found';
        header('Location: user_dashboard.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = 'Invalid vehicle ID';
    header('Location: user_dashboard.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $license_plate = trim($_POST['license_plate']);

    // Basic validation
    if (empty($brand)) $errors[] = "Brand is required";
    if (empty($model)) $errors[] = "Model is required";
    if ($year < 1990 || $year > 2025) $errors[] = "Year must be between 1990 and 2025";
    
    // Server-side license plate validation
    if (empty($license_plate)) {
        $errors[] = "License plate is required";
    } elseif (!preg_match('/^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i', $license_plate)) {
        $errors[] = "License plate format is invalid (Example: BA 01 CHA 1234)";
    }

    if (empty($errors)) {
        // Check for duplicate license plate (excluding current vehicle)
        $check_stmt = $conn->prepare("SELECT id FROM vehicles WHERE license_plate = ? AND id != ? AND user_id = ?");
        $check_stmt->bind_param("sii", $license_plate, $vehicle_id, $user_id);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows > 0) {
            $_SESSION['duplicate_error'] = true;
            $_SESSION['form_data'] = [
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'license_plate' => $license_plate
            ];
            header("Location: update_vehicle.php?id=$vehicle_id");
            exit;
        }

        // Update vehicle
        $update_stmt = $conn->prepare("UPDATE vehicles SET brand = ?, model = ?, year = ?, license_plate = ? WHERE id = ? AND user_id = ?");
        $update_stmt->bind_param("ssssii", $brand, $model, $year, $license_plate, $vehicle_id, $user_id);

        try {
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = 'Vehicle updated successfully';
                header('Location: user_dashboard.php');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $_SESSION['duplicate_error'] = true;
                $_SESSION['form_data'] = [
                    'brand' => $brand,
                    'model' => $model,
                    'year' => $year,
                    'license_plate' => $license_plate
                ];
                header("Location: update_vehicle.php?id=$vehicle_id");
                exit;
            } else {
                $errors[] = 'Error updating vehicle: ' . $e->getMessage();
            }
        }
    }
}

// Check for duplicate error from session
if (isset($_SESSION['duplicate_error'])) {
    $duplicate_error = true;
    unset($_SESSION['duplicate_error']);
    
    // Restore form data
    if (isset($_SESSION['form_data'])) {
        $brand = $_SESSION['form_data']['brand'];
        $model = $_SESSION['form_data']['model'];
        $year = $_SESSION['form_data']['year'];
        $license_plate = $_SESSION['form_data']['license_plate'];
        unset($_SESSION['form_data']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Vehicle</title>
    <link rel="stylesheet" href="CSS/add-vehicle.css">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const licensePlate = document.getElementById('license_plate');
        const errorMessage = document.getElementById('error-message');

        // License plate validation
        if (licensePlate) {
            licensePlate.addEventListener('input', function() {
                const plate = this.value.trim();
                const isValid = /^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i.test(plate);
                errorMessage.textContent = 
                    isValid ? '' : 'Invalid format (Example: BA 01 CHA 1234)';
            });

            // Also validate on form submission
            form.addEventListener('submit', function(e) {
                const plate = licensePlate.value.trim();
                if (!/^[a-z]{2}\s\d{2}\s[a-z]{3}\s\d{4}$/i.test(plate)) {
                    errorMessage.textContent = 'Invalid format (Example: BA 01 CHA 1234)';
                    e.preventDefault();
                }
            });
        }

        // Show alert if there's a duplicate license plate error
        <?php if (isset($duplicate_error)): ?>
            alert('Duplicate license plate entry. This license plate is already registered.');
            window.location = "user_dashboard.php";
        <?php endif; ?>
    });
    </script>
</head>
<body>
    <h2>Update Vehicle</h2>
    
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="update_vehicle.php?id=<?php echo $vehicle_id; ?>" id="updateVehicleForm">
        <label for="brand">Vehicle Brand:</label>
        <input type="text" id="brand" name="brand" value="<?php echo isset($brand) ? htmlspecialchars($brand) : htmlspecialchars($vehicle['brand']); ?>" required maxlength="50">
        
        <label for="model">Vehicle Model:</label>
        <input type="text" id="model" name="model" value="<?php echo isset($model) ? htmlspecialchars($model) : htmlspecialchars($vehicle['model']); ?>" required maxlength="50">
        
        <label for="year">Vehicle Year:</label>
        <input type="number" id="year" name="year" min="1990" max="2025" value="<?php echo isset($year) ? htmlspecialchars($year) : htmlspecialchars($vehicle['year']); ?>" required>
        
        <label for="license_plate">License Plate:</label>
        <input type="text" id="license_plate" name="license_plate" 
               value="<?php echo isset($license_plate) ? htmlspecialchars($license_plate) : htmlspecialchars($vehicle['license_plate']); ?>" 
               required 
               pattern="[a-zA-Z]{2}\s\d{2}\s[a-zA-Z]{3}\s\d{4}"
               title="Format: BA 01 CHA 1234">
        <div id="error-message" style="color:red;"></div>
        
        <input type="submit" value="Update Vehicle">
    </form>
</body>
</html>