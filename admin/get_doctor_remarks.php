<?php
// This file fetches doctor's remarks for a specific appointment

// Start session
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'a') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Import database connection
include("../connection.php");

// Check if appointment ID is provided
if (!isset($_GET['appoid'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Appointment ID is required'
    ]);
    exit;
}

$appoid = $_GET['appoid'];

// Query to get doctor remarks for this appointment
$query = "SELECT doctor_remarks FROM payments WHERE appoid = '$appoid' LIMIT 1";
$result = $database->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'remarks' => $row['doctor_remarks']
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'remarks' => null,
        'message' => 'No remarks found for this appointment'
    ]);
}
?> 