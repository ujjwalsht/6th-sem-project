<?php
include '../database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete vehicle record from the service table
    $sql = "DELETE FROM service WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
        header("Location: admindashboard.php");
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admindashboard.php");
}
?>
