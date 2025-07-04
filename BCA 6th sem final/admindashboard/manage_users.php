<?php
require_once 'admin_auth.php';

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Fetch all users
$users_query = "SELECT id, first_name, last_name, email, phone FROM users";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="admin-style.css">

    <style>
     .btn{
    display: inline-block;
    background-color: #95a5a6;
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #7f8c8d;
}
   
    </style>
</head>
<body>
    <div id="container">
        <div id="dashboard-header">
            <h1>Manage Users</h1>
        </div>
        
        <div id="nav" class="sidenav">
            <a href="../homepage/hp.php" class="btn-home">Click here to Goto Homepage</a>
            <a href="javascript:void(0)" onclick="loadContent('manage_users.php')" class="nav-item active">Manage Users</a>
            <a href="javascript:void(0)" onclick="loadContent('appointment_list.php')" class="nav-item">Appointment List</a>
            <a href="javascript:void(0)" onclick="loadContent('admin_dashboard.php')" class="nav-item">← Back to Dashboard</a>
            <a href="../logout.php" class="nav-item">Logout</a>
        </div>
        
        <div id="main-content">
            <div id="dynamic-content">
                <h2>User Management</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($users_result) > 0): ?>
                            <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['phone']) ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn">Edit</a>
                                        <a href="manage_users.php?delete_id=<?= $user['id'] ?>" 
                                           class="btn" 
                                           onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No users found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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