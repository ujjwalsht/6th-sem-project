<?php
require_once 'admin_auth.php';

// Fetch all users
$user_query = "SELECT * FROM users";
$user_result = mysqli_query($conn, $user_query);

// Fetch all vehicles
$vehicle_query = "SELECT * FROM vehicles";
$vehicle_result = mysqli_query($conn, $vehicle_query);

// Fetch all appointments
$appointment_query = "SELECT appointments.*, vehicles.license_plate, vehicles.brand AS brand, 
                     users.first_name AS user_first_name 
                     FROM appointments 
                     INNER JOIN vehicles ON appointments.vehicle_id = vehicles.id
                     INNER JOIN users ON appointments.user_id = users.id";
$appointment_result = mysqli_query($conn, $appointment_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
        <div id="container">
        <div id="dashboard-header">
            <h1>Sangam Auto Workshop</h1>
        </div>
        
        <div id="nav" class="sidenav">
            <a href="../homepage/hp.php" class="btn-home">Click here to Goto Homepage</a>
            <a href="javascript:void(0)" onclick="loadContent('manage_users.php')" class="nav-item">Manage Users</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment_list.php')" class="nav-item">Appointment List</a>            
            <a href="../logout.php" class="nav-item">Logout</a>
        </div>
        
        <div id="main-content">
            <div id="user-info">
                <p>Admin Dashboard</p>
                <p>Welcome, Purushottam!</p>
            </div>
            
            <div id="dynamic-content">
                <div class="dashboard-welcome">
                    <h2>Admin Overview</h2>
                    
                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>Total Users</h3>
                            <p><?php echo mysqli_num_rows($user_result); ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Total Vehicles</h3>
                            <p><?php echo mysqli_num_rows($vehicle_result); ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Pending Appointments</h3>
                            <p><?php 
                                $pending_query = "SELECT COUNT(*) FROM appointments WHERE status = 'Pending'";
                                $pending_result = mysqli_query($conn, $pending_query);
                                echo mysqli_fetch_row($pending_result)[0];
                            ?></p>
                        </div>
                    </div>
                    
                   
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Sangam Auto Workshop. ALL RIGHTS RESERVED.
    </div>
    
    <script>
        function loadContent(page) {
            // For now, we'll just navigate to the page
            // In a real implementation, this would use AJAX to load content dynamically
            window.location.href = page;
        }
    </script>
</body>
</html>