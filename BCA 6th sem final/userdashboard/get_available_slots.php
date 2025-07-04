<?php
include '../connection.php'; // adjust your path

if (isset($_GET['appointment_date']) && isset($_GET['appointment_time'])) {
    $date = $_GET['appointment_date'];
    $time = $_GET['appointment_time'];

    // Fetch all booked slot numbers for the given date and time
    $sql = "SELECT slot_number FROM appointments WHERE appointment_date = ? AND appointment_time = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_slots = [];
    while ($row = $result->fetch_assoc()) {
        $booked_slots[] = $row['slot_number'];
    }

    // All possible slots
    $all_slots = [1, 2, 3, 4];
    $available_slots = array_values(array_diff($all_slots, $booked_slots));

    echo json_encode($available_slots);
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
