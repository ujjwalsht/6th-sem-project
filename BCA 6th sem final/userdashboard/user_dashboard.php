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

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, id FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $first_name = $user['first_name'];
    $user_id = $user['id'];
} else {
    $first_name = "User";
    $user_id = "Unknown";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
        <button id="nav-toggle" onclick="toggleNav()">☰</button>
        <div id="nav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">&times;</a>
            <a href="javascript:void(0)" onclick="loadContent('add-vehicle.php')">Add Vehicle</a>
            <a href="javascript:void(0)" onclick="loadContent('vehicle-list.php')">Your Vehicles</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment-list.php')">Your Appointments</a>
            <a href="javascript:void(0)" onclick="loadContent('profile.php')">Your Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
        <div id="main-content">
            <h1>User Dashboard</h1>
            <div id="user-info">
                <p>Welcome, <?php echo htmlspecialchars($first_name); ?>!</p>
                <p>User ID: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="dynamic-content">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
    <div class="footer">
        &copy; 2025 Sangam Auto Workshop. ALL RIGHTS RESERVED.
    </div>
    <script>
        function toggleNav() {
            var nav = document.getElementById('nav');
            nav.style.left = (nav.style.left === '0px' || nav.style.left === '') ? '-250px' : '0';
        }

        function loadContent(page) {
            console.log("Loading content from: " + page);
            var xhr = new XMLHttpRequest();
            xhr.open('GET', page, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('dynamic-content').innerHTML = xhr.responseText;
                    console.log("Content loaded successfully.");
                } else {
                    document.getElementById('dynamic-content').innerHTML = 'Content could not be loaded.';
                    console.error("Error loading content: " + xhr.status);
                }
            };
            xhr.onerror = function() {
                console.error("Request failed.");
            };
            xhr.send();
        }
    </script>
</body>
</html>
