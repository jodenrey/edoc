<?php
// Database migration script
// Run this script to ensure all required tables and columns exist

// Include database connection
include "connection.php";

echo "Starting database migration...\n";

// Check if payment_status column exists in appointment table
echo "Checking appointment table...\n";
$checkAppointmentColumn = $database->query("SHOW COLUMNS FROM appointment LIKE 'payment_status'");
if ($checkAppointmentColumn->num_rows === 0) {
    echo "Adding payment_status column to appointment table...\n";
    $result = $database->query("ALTER TABLE appointment ADD COLUMN payment_status VARCHAR(20) DEFAULT 'Unpaid'");
    if ($result) {
        echo "Successfully added payment_status column to appointment table.\n";
    } else {
        echo "Error adding payment_status column: " . $database->error . "\n";
    }
} else {
    echo "payment_status column already exists in appointment table.\n";
}

// Check if payments table exists
echo "Checking payments table...\n";
$checkPaymentsTable = $database->query("SHOW TABLES LIKE 'payments'");
if ($checkPaymentsTable->num_rows === 0) {
    echo "Creating payments table...\n";
    $createPaymentsTable = $database->query("
        CREATE TABLE payments (
            id INT(11) NOT NULL AUTO_INCREMENT,
            appoid INT(11),
            pid INT(11),
            amount DECIMAL(10,2) NOT NULL,
            payment_type VARCHAR(50) NOT NULL,
            payment_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            doctor_remarks TEXT,
            PRIMARY KEY (id)
        )
    ");
    if ($createPaymentsTable) {
        echo "Successfully created payments table.\n";
    } else {
        echo "Error creating payments table: " . $database->error . "\n";
    }
} else {
    echo "payments table already exists.\n";
}

// Check for process_payment.php file
echo "Checking for process_payment.php file...\n";
if (!file_exists("admin/process_payment.php")) {
    echo "Creating process_payment.php file...\n";
    
    $processPaymentContent = '<?php
session_start();
include("../connection.php");

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

// Get patient ID from appointment
$getPatientQuery = $database->query("SELECT pid FROM appointment WHERE appoid = \'$appoid\'");
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

// Insert payment record
$insertPayment = $database->query("
    INSERT INTO payments (appoid, pid, amount, payment_type, doctor_remarks)
    VALUES (\'$appoid\', \'$pid\', \'$amount\', \'$paymentType\', \'$remarks\')
");

if(!$insertPayment) {
    $response = [
        "status" => "error",
        "message" => "Error inserting payment: " . $database->error
    ];
    echo json_encode($response);
    exit;
}

// Update appointment payment status
$updateAppointment = $database->query("
    UPDATE appointment SET payment_status = \'Paid\' WHERE appoid = \'$appoid\'
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
?>';

    file_put_contents("admin/process_payment.php", $processPaymentContent);
    echo "Successfully created process_payment.php file.\n";
} else {
    echo "process_payment.php file already exists.\n";
}

echo "Database migration completed.\n";
?> 