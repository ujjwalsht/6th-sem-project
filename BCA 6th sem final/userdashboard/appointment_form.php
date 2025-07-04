<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

// --- DATA SOURCE FOR SERVICE DURATIONS (in minutes) ---
$service_durations = [
    'Brake Failure Diagnosis and Repair' => 120, 'Brake Pad Replacement' => 90, 'Complete Brake System Check' => 60, 'Brake Fluid Leak Repair' => 120,
    "Engine Won't Start Diagnosis" => 180, 'Complete Engine Tune-up' => 240, 'Spark Plug Replacement' => 60, 'Timing Belt Replacement' => 480, 'Alternator Repair' => 180, 'Starter Motor Repair' => 150,
    'Flat Tire Repair/Replacement' => 30, 'Professional Tire Rotation' => 30, 'Precision Wheel Alignment' => 60, 'Wheel Balancing' => 45, 'Tire Pressure Check' => 15,
    'Battery Replacement' => 20, 'Dashboard Warning Light Diagnosis' => 90, 'Headlight/Taillight Replacement' => 30, 'Complete Electrical System Diagnostic' => 240,
    'AC Not Cooling Diagnosis' => 90, 'Complete Coolant Flush' => 60, 'Radiator Repair and Maintenance' => 180,
    'Transmission Service' => 240, 'Clutch Repair' => 480, 'Power Steering Repair' => 120,
    'Oil Change' => 30, 'Air Filter Replacement' => 15, 'Fuel System Cleaning' => 60, 'Regular Maintenance Check' => 90,
    'Suspension Repair' => 300, 'Exhaust System Repair' => 120
];

// --- HELPER FUNCTIONS ---
function format_duration($minutes) {
    if ($minutes <= 0) return '';
    if ($minutes < 60) return "({$minutes} mins)";
    if ($minutes % 60 == 0) { $hours = $minutes / 60; return $hours > 1 ? "({$hours} hrs)" : "(1 hr)"; }
    $hours = floor($minutes / 60); $mins = $minutes % 60; return "({$hours}h {$mins}m)";
}

function is_checked($serviceName, $selections) {
    return in_array($serviceName, $selections) ? 'checked' : '';
}

// --- HANDLE FORM SUBMISSION (Contains all validation logic) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vehicle_id'])) {
    header('Content-Type: application/json');
    
    $user_id = $_SESSION['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $slot_number = $_POST['slot_number'];
    $service_types_array = $_POST['service_type'] ?? [];

    $errors = [];

    // --- 1. Basic Validation ---
    if (empty($service_types_array)) $errors[] = 'Please select at least one service.';
    
    $checkVehicleQuery = "SELECT id FROM appointments WHERE vehicle_id = ? AND status = 'CONFORMED' LIMIT 1";
    $checkVehicleStmt = mysqli_prepare($conn, $checkVehicleQuery);
    mysqli_stmt_bind_param($checkVehicleStmt, "i", $vehicle_id);
    mysqli_stmt_execute($checkVehicleStmt);
    if (mysqli_num_rows(mysqli_stmt_get_result($checkVehicleStmt)) > 0) {
        $errors[] = "This vehicle already has a confirmed appointment. A new booking can only be made after the current one is completed.";
    }

    // --- 2. Duration and Overlap Validation ---
    if (empty($errors)) {
        $total_duration_minutes = 0;
        foreach ($service_types_array as $service) {
            $total_duration_minutes += $service_durations[$service] ?? 0;
        }

        if ($total_duration_minutes > 0) {
            $new_start_dt = new DateTime("{$appointment_date} {$appointment_time}");
            $new_end_dt = (clone $new_start_dt)->add(new DateInterval("PT{$total_duration_minutes}M"));

            $shop_open_dt = new DateTime("{$appointment_date} 07:30");
            $shop_close_dt = new DateTime("{$appointment_date} 19:30");
            if ($new_start_dt < $shop_open_dt || $new_end_dt > $shop_close_dt) {
                $errors[] = "The required time does not fit within our opening hours (7:30 AM - 7:30 PM) from your selected start time.";
            }

            // Fetch existing appointments to check for conflicts
            $conflict_query = "SELECT appointment_time, service_type FROM appointments WHERE appointment_date = ? AND slot_number = ? AND status != 'cancelled'";
            $conflict_stmt = mysqli_prepare($conn, $conflict_query);
            mysqli_stmt_bind_param($conflict_stmt, "si", $appointment_date, $slot_number);
            mysqli_stmt_execute($conflict_stmt);
            $existing_apps = mysqli_stmt_get_result($conflict_stmt);
            
            while ($app = mysqli_fetch_assoc($existing_apps)) {
                $existing_duration = 0;
                foreach (explode(', ', $app['service_type']) as $s) {
                    $existing_duration += $service_durations[$s] ?? 0;
                }
                
                if ($existing_duration > 0) {
                    $existing_start_dt = new DateTime("{$appointment_date} {$app['appointment_time']}");
                    $existing_end_dt = (clone $existing_start_dt)->add(new DateInterval("PT{$existing_duration}M"));
                    
                    if ($new_start_dt < $existing_end_dt && $new_end_dt > $existing_start_dt) {
                        $errors[] = "Time conflict: This service bay is booked from " . $existing_start_dt->format('g:i A') . " to " . $existing_end_dt->format('g:i A') . ".";
                        break;
                    }
                }
            }
        }
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode("\n", $errors)]);
        exit;
    }

    // --- 3. If all checks pass, INSERT the appointment ---
    $service_type_string = implode(', ', $service_types_array);
    $insert_query = "INSERT INTO appointments (user_id, vehicle_id, appointment_date, appointment_time, service_type, slot_number, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "iisssi", $user_id, $vehicle_id, $appointment_date, $appointment_time, $service_type_string, $slot_number);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Appointment requested successfully! We will review for time conflicts and confirm shortly.']);
    } else {
        error_log('Booking failed: ' . mysqli_error($conn));
        echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again.']);
    }
    exit;
}

// --- PREPARE DATA FOR THE HTML FORM ---
// Fetch user's vehicles for the form dropdown
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM vehicles WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$vehicles = mysqli_stmt_get_result($stmt);

// Date range setup for the form
$today = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+14 days'));

// Pre-selection logic
$preselectedServices = [];
if (isset($_GET['service'])) {
    $preselectedServices = array_map('trim', explode(',', urldecode($_GET['service'])));
}

// Service list structured for easier looping in HTML
$service_list = [
    'Brake System' => ['Brake Failure Diagnosis and Repair', 'Brake Pad Replacement', 'Complete Brake System Check', 'Brake Fluid Leak Repair'],
    'Engine Services' => ["Engine Won't Start Diagnosis", 'Complete Engine Tune-up', 'Spark Plug Replacement', 'Timing Belt Replacement', 'Alternator Repair', 'Starter Motor Repair'],
    'Tire & Wheel' => ['Flat Tire Repair/Replacement', 'Professional Tire Rotation', 'Precision Wheel Alignment', 'Wheel Balancing', 'Tire Pressure Check'],
    'Electrical System' => ['Battery Replacement', 'Dashboard Warning Light Diagnosis', 'Headlight/Taillight Replacement', 'Complete Electrical System Diagnostic'],
    'Cooling & AC' => ['AC Not Cooling Diagnosis', 'Complete Coolant Flush', 'Radiator Repair and Maintenance'],
    'Transmission' => ['Transmission Service', 'Clutch Repair', 'Power Steering Repair'],
    'Maintenance' => ['Oil Change', 'Air Filter Replacement', 'Fuel System Cleaning', 'Regular Maintenance Check'],
    'Suspension & Exhaust' => ['Suspension Repair', 'Exhaust System Repair']
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="CSS/appointment_form.css">
    <style>
        .service-duration { color: #888; font-style: italic; font-size: 0.9em; margin-left: 5px; }
        #total_duration_display { background: #e2e3e5; color: #383d41; text-align: center; padding: 10px; border-radius: 5px; }
        .service-checkbox-group { border: 1px solid #ddd; border-radius: 4px; padding: 12px; margin-top: 10px; }
        .service-checkbox-group strong { display: block; margin-bottom: 8px; color: #333; }
        .service-checkbox-group label { display: block; margin-bottom: 5px; font-weight: normal; }
        .service-checkbox-group input { margin-right: 8px; }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <h2>Book an Appointment</h2>
        <form id="appointment-form">
            <div class="form-group">
                <label for="vehicle_id">Select Vehicle:</label>
                <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                    <?php if (mysqli_num_rows($vehicles) > 0): ?>
                        <?php while ($vehicle = mysqli_fetch_assoc($vehicles)): ?>
                            <option value="<?= htmlspecialchars($vehicle['id']) ?>"><?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' (' . $vehicle['license_plate'] . ')') ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No vehicles found. Please add a vehicle first.</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_date">Date:</label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control" min="<?= $today ?>" max="<?= $max_date ?>" required>
            </div>

            <div class="form-group">
                <label for="appointment_time">Time:</label>
                <input type="time" id="appointment_time" name="appointment_time" class="form-control" min="07:30" max="19:30" step="900" required>
                <small>Shop hours: 7:30 AM to 7:30 PM</small>
            </div>

            <div class="form-group">
                <label for="slot_number">Service Bay:</label>
                <select id="slot_number" name="slot_number" class="form-control" required>
                    <option value="">Select a Bay</option>
                    <option value="1">Bay 1</option>
                    <option value="2">Bay 2</option>
                    <option value="3">Bay 3</option>
                    <option value="4">Bay 4</option>
                </select>
            </div>

            <div class="form-group">
                <label>Service Type(s):</label>
                <small>Estimated durations are shown next to each service.</small>
                <div id="total_duration_display" style="margin-top:10px; display:none;">
                    <strong>Total Estimated Time: <span id="total_duration_text">0 mins</span></strong>
                </div>

                <?php foreach ($service_list as $category => $services): ?>
                <div class="service-checkbox-group">
                    <strong><?= htmlspecialchars($category) ?></strong>
                    <?php foreach ($services as $service): ?>
                    <div><label>
                        <input type="checkbox" class="service-checkbox" name="service_type[]" value="<?= htmlspecialchars($service) ?>" <?= is_checked($service, $preselectedServices) ?>>
                        <?= htmlspecialchars($service) ?><span class="service-duration"><?= format_duration($service_durations[$service] ?? 0) ?></span>
                    </label></div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="form-actions">
                <button type="button" onclick="history.back()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-submit">Book Now</button>
            </div>
        </form>
    </div>

    <script>
    const serviceDurations = <?= json_encode($service_durations); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('appointment-form');
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
        const totalDurationDisplay = document.getElementById('total_duration_display');
        const totalDurationText = document.getElementById('total_duration_text');

        function updateTotalDuration() {
            let totalMinutes = 0;
            serviceCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalMinutes += serviceDurations[checkbox.value] || 0;
                }
            });

            if (totalMinutes > 0) {
                let hours = Math.floor(totalMinutes / 60);
                let minutes = totalMinutes % 60;
                totalDurationText.textContent = `${hours}h ${minutes}m`;
                totalDurationDisplay.style.display = 'block';
            } else {
                totalDurationDisplay.style.display = 'none';
            }
        }

        serviceCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateTotalDuration));
        updateTotalDuration(); // Initial call for any pre-selected services

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            if (!formData.has('service_type[]')) {
                alert('Error: Please select at least one service type.');
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Checking Availability...';

            fetch('appointment_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = 'user_dashboard.php';
                } else {
                    alert('Booking Failed:\n' + data.message);
                }
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Book Now';
            });
        });
    });
    </script>
</body>
</html>