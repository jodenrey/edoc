<?php
// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(0);

session_start();

// Set header to JSON
header('Content-Type: application/json');

// Check if user is logged in and is an admin
if(!isset($_SESSION["user"]) || $_SESSION["usertype"] != "a") {
    $response = [
        "status" => "error",
        "message" => "Unauthorized access"
    ];
    echo json_encode($response);
    exit;
}

// Check if data is received
if(!isset($_POST["appoid"]) || !isset($_POST["amount"]) || !isset($_POST["payment_type"])) {
    $response = [
        "status" => "error",
        "message" => "Missing required data"
    ];
    echo json_encode($response);
    exit;
}

$appoid = $_POST["appoid"];
$amount = $_POST["amount"];
$paymentType = $_POST["payment_type"];
$remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : "";

include("../connection.php");

// Get patient ID from appointment
$getPatientQuery = $database->query("SELECT pid FROM appointment WHERE appoid = '$appoid'");
if($getPatientQuery->num_rows == 0) {
    $response = [
        "status" => "error",
        "message" => "Appointment not found"
    ];
    echo json_encode($response);
    exit;
}

$patientData = $getPatientQuery->fetch_assoc();
$pid = $patientData["pid"];

// Check if there are existing doctor remarks that need to be preserved
$existingRemarksQuery = $database->query("SELECT doctor_remarks FROM payments WHERE appoid = '$appoid' LIMIT 1");
$doctorRemarks = "";

if ($existingRemarksQuery && $existingRemarksQuery->num_rows > 0) {
    // There are existing remarks - get them and use them instead of admin remarks
    $remarksData = $existingRemarksQuery->fetch_assoc();
    $doctorRemarks = $remarksData["doctor_remarks"];
    
    // Update existing payment record with new payment info but keep doctor remarks
    $updatePayment = $database->query("
        UPDATE payments SET 
        amount = '$amount', 
        payment_type = '$paymentType'
        WHERE appoid = '$appoid'
    ");
    
    if(!$updatePayment) {
        $response = [
            "status" => "error",
            "message" => "Error updating payment: " . $database->error
        ];
        echo json_encode($response);
        exit;
    }
} else {
    // No existing remarks, create new payment record
    // Insert payment record - note that admin's remarks are not saved as doctor_remarks
    $insertPayment = $database->query("
        INSERT INTO payments (appoid, pid, amount, payment_type, doctor_remarks)
        VALUES ('$appoid', '$pid', '$amount', '$paymentType', '')
    ");
    
    if(!$insertPayment) {
        $response = [
            "status" => "error",
            "message" => "Error inserting payment: " . $database->error
        ];
        echo json_encode($response);
        exit;
    }
}

// Update appointment payment status
$updateAppointment = $database->query("
    UPDATE appointment SET payment_status = 'Paid' WHERE appoid = '$appoid'
");

if(!$updateAppointment) {
    $response = [
        "status" => "error",
        "message" => "Error updating appointment: " . $database->error
    ];
    echo json_encode($response);
    exit;
}

// Return success response
$response = [
    "status" => "success",
    "message" => "Payment processed successfully"
];
echo json_encode($response);
?> 