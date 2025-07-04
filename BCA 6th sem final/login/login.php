<?php
session_start();
require_once "../db.php";

// Redirect if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admindashboard/admin_dashboard.php");
    } else {
        header("Location: ../userdashboard/user_dashboard.php");
    }
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // Trim whitespace

    // Validate and sanitize input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Admin specific login check
    if ($email === 'sangamauto@gmail.com' && $password === 'Sangam123' && $role === 'admin') {
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        session_regenerate_id(true); // Regenerate session ID
        header("Location: ../admindashboard/admin_dashboard.php");
        exit;
    }

    // Regular user login check
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            // Set the user_id session variable using the appropriate column name from your database
            $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the column name for user ID
            session_regenerate_id(true); // Regenerate session ID
            
            if ($user['role'] === 'admin') {
                header("Location: ../admindashboard/admin_dashboard.php");
            } else {
                header("Location: ../userdashboard/user_dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sangam Auto Workshop (SAW)</title>
    <link rel="stylesheet" href="css_login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <img src="../IMG/logo.png" alt="Sangam Auto Workshop Logo">
            </div>
            <p>Welcome to Sangam Auto Workshop</p>
            <p>Login to manage your appointments</p>
            <?php if ($error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="role">Login as:</label>
                    <select id="role" name="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="../register/signup.php">Register here</a>.</p>
            <p class="register-link">Go back to homepage? <a href="../homepage/hp.php">CLick here</a>.</p>
        </div>
    </div>
</body>
</html>
