<?php
require_once 'admin_auth.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = (int)$_GET['id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $bind_result = $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
    
    if ($bind_result === false) {
        die("Bind failed: " . $stmt->error);
    }
    
    if ($stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin-style.css">
    <style>
        /* Add to your existing admin-style.css */

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
    max-width: 500px;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: var(--dark);
}

.form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

/* Button Styles */
.btn-edit {
    background-color: #e74c3c;
    color: white;
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
    margin-right: 1rem;
}

.btn-edit:hover {
    background-color: #c0392b;
}

.btn-delete {
    display: inline-block;
    background-color: #95a5a6;
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.btn-delete:hover {
    background-color: #7f8c8d;
}

/* Error Message */
.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
    border: 1px solid #f5c6cb;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-group {
        max-width: 100%;
    }
    
    .form-group input {
        padding: 0.7rem;
    }
    
    .btn-edit, .btn-delete {
        padding: 0.7rem 1.2rem;
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <div id="container">
        <div id="dashboard-header">
            <h1>Edit User</h1>
        </div>
        
        <div id="nav" class="sidenav">
            <a href="../homepage/hp.php" class="btn-home">← Back to Home</a>
            <a href="javascript:void(0)" onclick="loadContent('manage_users.php')" class="nav-item">Manage Users</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment_list.php')" class="nav-item">Appointment List</a>
            <a href="javascript:void(0)" onclick="loadContent('admin_dashboard.php')" class="nav-item">← Back to Dashboard</a>
            <a href="../logout.php" class="nav-item">Logout</a>
        </div>
        
        <div id="main-content">
            <div id="dynamic-content">
                <h2>Edit User: <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>
                    
                    <button type="submit" class="btn-edit">Update User</button>
                    <a href="manage_users.php" class="btn-delete">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Sangam Auto Workshop. ALL RIGHTS RESERVED.
    </div>
    
    <script>
        function loadContent(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>