<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, id FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

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
    <title>User Dashboard</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div id="container">
        <div id="dashboard-header">
            <h1>User Dashboard</h1> 
        </div>
        
        <div id="nav" class="sidenav">
            <a href="../homepage/hp.php" class="btn-home">Click here to Goto Homepage</a>
            <a href="javascript:void(0)" onclick="loadContent('add-vehicle.php')" class="nav-item">Add Vehicle</a>
            <a href="javascript:void(0)" onclick="loadContent('vehicle-list.php')" class="nav-item">Your Vehicles</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment-list.php')" class="nav-item">Your Appointments</a>
            <a href="javascript:void(0)" onclick="loadContent('profile.php')" class="nav-item">Your Profile</a>
           <a href="../logout.php" class="nav-item" onclick="if(confirm('Are you sure you want to logout?')) { window.location.href='../logout.php'; } return false;">Logout</a>  
        </div>
        
        <div id="main-content">
            <div id="user-info">
                <p>Welcome, <?php echo htmlspecialchars($first_name); ?>!</p>
                <p>User ID: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            
            <div id="dynamic-content">
                <div class="dashboard-welcome">
                    <h2>Getting Started</h2>
                    <p>Select an option from the menu to begin managing your vehicles and appointments.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Sangam Auto Workshop. ALL RIGHTS RESERVED.
    </div>

    <script src="JS/dashboard.js"></script>
</body>
</html>
