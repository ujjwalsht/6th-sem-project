<?php
session_start();
include '../db.php';

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect or handle the case when user_id is not set
    // For example, redirect to the login page
    header("Location: ../login/login.php");
    exit; // Stop further execution
}

$user_id = $_SESSION['user_id'];

// Prepare and execute SQL query to select user details
$sql = "SELECT first_name, last_name, email, address, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user with given user_id exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle the case when user does not exist
    // For example, display an error message or redirect to a different page
    echo "User not found";
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
   </head>
<body>
    <h2>Profile</h2>
    <p>First Name: <?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ""; ?></p>
    <p>Last Name: <?php echo isset($user['last_name']) ? htmlspecialchars($user['last_name']) : ""; ?></p>
    <p>Email: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ""; ?></p>
    <p>Address: <?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ""; ?></p>
    <p>Phone: <?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ""; ?></p>
    <a href="edit_profile.php" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; text-decoration: none; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#0056b3'" onmouseout="this.style.backgroundColor='#007bff'">
    Edit Profile</a>
</body>
</html>
