<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim all input values to remove whitespace
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user'; // Automatically assigning user role

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($phone) || empty($password)) {
        echo "<script>alert('All fields are required.')</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email.')</script>";
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Please enter a valid 10-digit phone number.')</script>";
        exit;
    }

    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.')</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
        exit;
    }

    // Check if email already exists
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $result_email = $check_email_stmt->get_result();

    if ($result_email->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email.');
                window.location.href = 'signup.php';
        </script>";
        
    }
    $check_email_stmt->close();

    // Check if phone number already exists
    $check_phone_sql = "SELECT * FROM users WHERE phone = ?";
    $check_phone_stmt = $conn->prepare($check_phone_sql);
    $check_phone_stmt->bind_param("s", $phone);
    $check_phone_stmt->execute();
    $result_phone = $check_phone_stmt->get_result();

    if ($result_phone->num_rows > 0) {
echo "<script>
    alert('Phone number already exists. Please use a different phone number.');
    window.location.href = 'signup.php';
</script>";
exit;

    }
    $check_phone_stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (first_name, last_name, email, address, phone, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $address, $phone, $role, $hashed_password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['registration_success'] = "Registration successful. You can now login.";
        header("Location: ../login/login.php");
        exit;
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.location.href = 'signup.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="regcss.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="validate.js"></script>
</head>
<body>
    <div class="container">
        <h2>Register for Sangam Auto Workshop</h2>
        <form action="signup.php" method="post">
            <div class="input-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            
            <div class="input-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="input-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" title="10 digit phone number" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password (min 8 characters):</label>
                <input type="password" id="password" name="password" minlength="8" required>
            </div>
            
            <div class="input-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
            </div>
            
            <div class="input-group">
                <button type="submit">Register</button>
            </div>
            
            <div class="login-link">
                Already registered? <a href="../login/login.php">Login here</a>
            </div>
        </form>
    </div>
</body>
</html>
