<?php
// This file processes the doctor's remarks and saves them to the database

// Start session
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'd') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Import database connection
include("connection.php");

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Check if required parameters are provided
if (!isset($_POST['appoid']) || !isset($_POST['doctorRemarks'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required parameters'
    ]);
    exit;
}

$appoid = $_POST['appoid'];
$doctorRemarks = $_POST['doctorRemarks'];
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Get doctor information from session
$useremail = $_SESSION["user"];
$doctorQuery = $database->query("SELECT docid FROM doctor WHERE docemail='$useremail'");
$doctorData = $doctorQuery->fetch_assoc();
$docid = $doctorData["docid"];

// Get patient information from appointment
$appointmentQuery = $database->query("SELECT pid FROM appointment WHERE appoid='$appoid'");
$appointmentData = $appointmentQuery->fetch_assoc();
$pid = $appointmentData["pid"];

// Check if we need to create the payments table
$checkTableQuery = $database->query("SHOW TABLES LIKE 'payments'");
if ($checkTableQuery->num_rows === 0) {
    $createTableQuery = "
    CREATE TABLE payments (
        payment_id int(11) NOT NULL AUTO_INCREMENT,
        appoid int(11) NOT NULL,
        pid int(11) NOT NULL,
        amount decimal(10,2) DEFAULT 0.00,
        payment_type varchar(50) DEFAULT 'N/A',
        payment_date datetime NOT NULL DEFAULT current_timestamp(),
        doctor_remarks text DEFAULT NULL,
        PRIMARY KEY (payment_id)
    )";
    
    if (!$database->query($createTableQuery)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error creating payments table: ' . $database->error
        ]);
        exit;
    }
}

// Check if we need to create the checkup_forms table
$checkFormsTableQuery = $database->query("SHOW TABLES LIKE 'checkup_forms'");
if ($checkFormsTableQuery->num_rows === 0) {
    // Include the checkup_forms file to create the table
    include("checkup_forms.php");
}

// Use the setup_tables.php to ensure all required tables exist
include_once("setup_tables.php");
createPaymentsTable($database);
createCheckupFormsTable($database);

// Save the remarks based on the action
if ($action === 'save_remarks') {
    try {
        // First check if the checkup_data table exists
        $tableExists = false;
        $checkTableQuery = "SHOW TABLES LIKE 'checkup_data'";
        $tableResult = $database->query($checkTableQuery);
        
        if ($tableResult && $tableResult->num_rows > 0) {
            $tableExists = true;
        }
        
        if (!$tableExists) {
            // Create the table since it doesn't exist
            $createTableQuery = "
            CREATE TABLE IF NOT EXISTS `checkup_data` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `appoid` int(11) NOT NULL,
              `doctorRemarks` text DEFAULT NULL,
              `patientName` varchar(255) DEFAULT NULL,
              `firstName` varchar(255) DEFAULT NULL,
              `patientId` varchar(50) DEFAULT NULL,
              `dob` date DEFAULT NULL,
              `patientAge` varchar(10) DEFAULT NULL,
              `accompaniedBy` varchar(255) DEFAULT NULL,
              `relationship` varchar(100) DEFAULT NULL,
              `height` varchar(50) DEFAULT NULL,
              `weight` varchar(50) DEFAULT NULL,
              `bloodPressure` varchar(50) DEFAULT NULL,
              `pastMedical` varchar(10) DEFAULT NULL,
              `pastMedicalDesc` text DEFAULT NULL,
              `developmentalHistory` varchar(10) DEFAULT NULL,
              `developmentalDesc` text DEFAULT NULL,
              `behavioralHealth` varchar(10) DEFAULT NULL,
              `behavioralDesc` text DEFAULT NULL,
              `nutritional` varchar(10) DEFAULT NULL,
              `nutritionalDesc` text DEFAULT NULL,
              `diagnosis` text DEFAULT NULL,
              `plan` text DEFAULT NULL,
              `checkupDate` varchar(50) DEFAULT NULL,
              `checkboxData` text DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `appoid` (`appoid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            
            if (!$database->query($createTableQuery)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create checkup_data table: ' . $database->error]);
                exit;
            }
        } else {
            // Check if checkboxData column exists, add it if it doesn't
            $checkColumnQuery = "SHOW COLUMNS FROM `checkup_data` LIKE 'checkboxData'";
            $columnResult = $database->query($checkColumnQuery);
            
            if ($columnResult && $columnResult->num_rows === 0) {
                // Add the checkboxData column
                $addColumnQuery = "ALTER TABLE `checkup_data` ADD COLUMN `checkboxData` text DEFAULT NULL";
                if (!$database->query($addColumnQuery)) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add checkboxData column: ' . $database->error]);
                    exit;
                }
            }
        }
        
        // Collect all checkbox data into a single JSON object
        $checkboxData = [];
        foreach ($_POST as $field => $value) {
            // Check if the field name might be a checkbox (common patterns in your form)
            if (in_array($field, ['periodic', 'interpPeriodic', 'parentRequest', 'fluoride', 'referred', 
                                  'pastMedicalYes', 'pastMedicalNo', 'developmentalYes', 'developmentalNo',
                                  'behavioralYes', 'behavioralNo', 'nutritionalYes', 'nutritionalNo',
                                  'dentalReferral', 'ua', 'leadScreen', 'other',
                                  'normalVision', 'abnormalVision', 'referredVision',
                                  'normalHearing', 'abnormalHearing', 'referredHearing',
                                  'speechHearingYes', 'speechHearingNo', 'developmentNormal',
                                  'developmentYes', 'developmentNo', 
                                  'current', 'deferred', 'provided',
                                  'dental', 'nutrition', 'regularActivity', 'safety',
                                  'peerRelations', 'communication', 'parentalRole', 'schoolPerformance', 'limitSetting']) ||
                 strpos($field, 'checked') !== false) {
                $checkboxData[$field] = $value;
            }
        }
        
        // Convert checkbox data to JSON
        $checkboxDataJson = json_encode($checkboxData);
        
        // First check if record exists
        $checkQuery = "SELECT * FROM checkup_data WHERE appoid = ?";
        $checkStmt = $database->prepare($checkQuery);
        
        if (!$checkStmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $database->error]);
            exit;
        }
        
        $checkStmt->bind_param("i", $appoid);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing record
            $updateQuery = "UPDATE checkup_data SET doctorRemarks = ?, checkboxData = ?, ";
            $updateParams = [];
            $updateTypes = "ss";
            $updateParams[] = $doctorRemarks;
            $updateParams[] = $checkboxDataJson;
            
            // Add other form fields (excluding checkboxes)
            foreach ($_POST as $field => $value) {
                // Skip checkboxes, appoid, action, doctorRemarks, and the fields we've already processed
                if ($field != 'appoid' && $field != 'action' && $field != 'doctorRemarks' && 
                    !in_array($field, array_keys($checkboxData))) {
                    $updateQuery .= "$field = ?, ";
                    $updateParams[] = $value;
                    $updateTypes .= "s";
                }
            }
            
            // Remove trailing comma and space
            $updateQuery = rtrim($updateQuery, ", ");
            $updateQuery .= " WHERE appoid = ?";
            $updateParams[] = $appoid;
            $updateTypes .= "i";
            
            $updateStmt = $database->prepare($updateQuery);
            
            if (!$updateStmt) {
                echo json_encode(['status' => 'error', 'message' => 'Database error on update: ' . $database->error]);
                exit;
            }
            
            // Bind parameters dynamically
            $updateStmt->bind_param($updateTypes, ...$updateParams);
            $success = $updateStmt->execute();
            
            if ($success) {
                echo json_encode(['status' => 'success', 'message' => 'Checkup data updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating checkup data: ' . $updateStmt->error]);
            }
            
            $updateStmt->close();
        } else {
            // Insert new record
            $insertQuery = "INSERT INTO checkup_data (appoid, doctorRemarks, checkboxData";
            $valuePlaceholders = "?, ?, ?";
            $insertParams = [$appoid, $doctorRemarks, $checkboxDataJson];
            $insertTypes = "iss";
            
            // Add other form fields (excluding checkboxes)
            foreach ($_POST as $field => $value) {
                // Skip checkboxes, appoid, action, doctorRemarks, and the fields we've already processed
                if ($field != 'appoid' && $field != 'action' && $field != 'doctorRemarks' && 
                    !in_array($field, array_keys($checkboxData))) {
                    $insertQuery .= ", $field";
                    $valuePlaceholders .= ", ?";
                    $insertParams[] = $value;
                    $insertTypes .= "s";
                }
            }
            
            $insertQuery .= ") VALUES (" . $valuePlaceholders . ")";
            
            $insertStmt = $database->prepare($insertQuery);
            
            if (!$insertStmt) {
                echo json_encode(['status' => 'error', 'message' => 'Database error on insert: ' . $database->error]);
                exit;
            }
            
            // Bind parameters dynamically
            $insertStmt->bind_param($insertTypes, ...$insertParams);
            $success = $insertStmt->execute();
            
            if ($success) {
                echo json_encode(['status' => 'success', 'message' => 'Checkup data saved successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error saving checkup data: ' . $insertStmt->error]);
            }
            
            $insertStmt->close();
        }
        
        $checkStmt->close();
    } catch (Exception $e) {
        error_log('Save remarks error: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid action'
    ]);
    exit;
}
?> 