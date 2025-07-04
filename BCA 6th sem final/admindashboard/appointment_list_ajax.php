<?php
require_once 'admin_auth.php';
header('Content-Type: application/json');

// Define priority levels for different service types
$priorityMap = [
    // High priority (3) - Critical safety and drivability issues
    'Brake Failure Diagnosis and Repair' => 3,
    'Brake Pad Replacement' => 3,
    'Complete Brake System Check' => 3,
    'Brake Fluid Leak Repair' => 3,
    'Complete Engine Tune-up' => 3,
    'Timing Belt Replacement' => 3,
    'Alternator Repair' => 3,
    'Starter Motor Repair' => 3,
    'Transmission Service' => 3,
    'Clutch Repair' => 3,
    
    // Medium priority (2) - Important but not immediately critical
    'Spark Plug Replacement' => 2,
    'Flat Tire Repair/Replacement' => 2,
    'Professional Tire Rotation' => 2,
    'Precision Wheel Alignment' => 2,
    'Wheel Balancing' => 2,
    'Battery Replacement' => 2,
    'Dashboard Warning Light Diagnosis' => 2,
    'Headlight/Taillight Replacement' => 2,
    'Complete Electrical System Diagnostic' => 2,
    'AC Not Cooling Diagnosis' => 2,
    'Complete Coolant Flush' => 2,
    'Radiator Repair and Maintenance' => 2,
    'Power Steering Repair' => 2,
    'Suspension Repair' => 2,
    'Exhaust System Repair' => 2,
    
    // Low priority (1) - Routine maintenance
    'Oil Change' => 1,
    'Air Filter Replacement' => 1,
    'Fuel System Cleaning' => 1,
    'Tire Pressure Check and Adjustment' => 1
];

// Determine sorting method from GET parameter
$sortMethod = isset($_GET['sort']) ? $_GET['sort'] : 'priority';

// Fetch all appointments with user and vehicle info
$query = "SELECT appointments.*, 
                 users.id AS user_id,
                 users.first_name AS user_name,
                 users.last_name AS user_last_name,
                 vehicles.brand AS vehicle_brand,
                 vehicles.model AS vehicle_model,
                 vehicles.license_plate AS vehicle_plate
          FROM appointments
          LEFT JOIN users ON appointments.user_id = users.id
          LEFT JOIN vehicles ON appointments.vehicle_id = vehicles.id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die(json_encode(['error' => 'Database error: ' . mysqli_error($conn)]));
}

// Process appointments and assign priorities
$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $serviceType = $row['service_type'];
    $priority = $priorityMap[$serviceType] ?? 1; // Default to low priority if not found
    
    // Add priority information to the appointment data
    $row['priority'] = $priority;
    $row['priority_label'] = getPriorityLabel($priority);
    
    $appointments[] = $row;
}

// Sort appointments based on selected method
switch ($sortMethod) {
    case 'user':
        usort($appointments, function($a, $b) {
            return $a['user_id'] - $b['user_id'];
        });
        break;
        
    case 'time':
        usort($appointments, function($a, $b) {
            // Sort by date first, then by time
            $dateCompare = strtotime($a['appointment_date']) - strtotime($b['appointment_date']);
            if ($dateCompare === 0) {
                return strcmp($a['appointment_time'], $b['appointment_time']);
            }
            return $dateCompare;
        });
        break;
        
    case 'priority':
    default:
        usort($appointments, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                $dateCompare = strtotime($a['appointment_date']) - strtotime($b['appointment_date']);
                if ($dateCompare === 0) {
                    return strcmp($a['appointment_time'], $b['appointment_time']);
                }
                return $dateCompare;
            }
            return $b['priority'] - $a['priority'];
        });
        break;
}

// Helper function to convert priority level to label
function getPriorityLabel($priority) {
    switch ($priority) {
        case 3: return 'High';
        case 2: return 'Medium';
        default: return 'Low';
    }
}

// Return JSON response
echo json_encode($appointments);
?>