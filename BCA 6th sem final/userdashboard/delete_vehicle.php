
<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}



// Check if ID is provided
if (!isset($_POST['id'])) {
    http_response_code(400);
    echo "Vehicle ID not provided";
    exit;
}

$vehicle_id = (int)$_POST['id'];
$user_id = (int)$_SESSION['user_id'];

try {
    // Prepare and execute delete query
    $query = "DELETE FROM vehicles WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $vehicle_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Vehicle deleted successfully";
    } else {
        throw new Exception("Error deleting vehicle: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
} finally {
    mysqli_close($conn);
}
?>
