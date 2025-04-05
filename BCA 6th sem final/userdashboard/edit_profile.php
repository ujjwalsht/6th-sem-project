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

// If form is submitted, update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Prepare and execute SQL query to update user details
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $address, $phone, $user_id);
    $stmt->execute();

    // Redirect to profile page after update
    header("Location: ../userdashboard/user_dashboard.php");
    exit;
}

// Fetch current user details
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
    <title>Edit Profile</title>
    <style>
     body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 700px;
            margin: 50px;
        }
        h2 {
            color: #007BFF;
        }
        form {
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
</style>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Edit Profile</h2>      
    <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>"><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
