<?php
// This file contains functions to create and verify database tables

/**
 * Create the checkup_forms table if it doesn't exist
 */
function createCheckupFormsTable($database) {
    // Check if the checkup_forms table already exists
    $checkTableQuery = $database->query("SHOW TABLES LIKE 'checkup_forms'");
    if ($checkTableQuery->num_rows === 0) {
        // Create the checkup_forms table if it doesn't exist
        $createTableQuery = "
        CREATE TABLE `checkup_forms` (
            `form_id` int(11) NOT NULL AUTO_INCREMENT,
            `appoid` int(11) NOT NULL,
            `pid` int(11) NOT NULL,
            `docid` int(11) NOT NULL,
            `form_date` datetime NOT NULL DEFAULT current_timestamp(),
            `height` varchar(50) DEFAULT NULL,
            `weight` varchar(50) DEFAULT NULL,
            `blood_pressure` varchar(50) DEFAULT NULL,
            `diagnosis` text DEFAULT NULL,
            `plan` text DEFAULT NULL,
            `doctor_remarks` text DEFAULT NULL,
            `form_data` longtext DEFAULT NULL,
            PRIMARY KEY (`form_id`),
            KEY `appoid` (`appoid`),
            KEY `pid` (`pid`),
            KEY `docid` (`docid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4";
        
        if ($database->query($createTableQuery)) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

/**
 * Create the payments table if it doesn't exist
 */
function createPaymentsTable($database) {
    // Check if the payments table already exists
    $checkTableQuery = $database->query("SHOW TABLES LIKE 'payments'");
    if ($checkTableQuery->num_rows === 0) {
        $createTableQuery = "
        CREATE TABLE `payments` (
            `payment_id` int(11) NOT NULL AUTO_INCREMENT,
            `pid` int(11) NOT NULL,
            `appoid` int(11) NOT NULL,
            `amount` decimal(10,2) NOT NULL,
            `payment_type` enum('Cash','Cashless') NOT NULL,
            `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
            `doctor_remarks` text DEFAULT NULL,
            PRIMARY KEY (`payment_id`),
            KEY `pid` (`pid`),
            KEY `appoid` (`appoid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4";
        
        if ($database->query($createTableQuery)) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

/**
 * Create the checkup_data table if it doesn't exist
 */
function createCheckupDataTable($database) {
    // Check if the checkup_data table already exists
    $checkTableQuery = $database->query("SHOW TABLES LIKE 'checkup_data'");
    if ($checkTableQuery->num_rows === 0) {
        $createTableQuery = "
        CREATE TABLE `checkup_data` (
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
            `diagnosis` text DEFAULT NULL,
            `plan` text DEFAULT NULL,
            `checkboxData` text DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `appoid` (`appoid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4";
        
        if ($database->query($createTableQuery)) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

/**
 * Update the appointment table to include payment_status column if it doesn't exist
 */
function updateAppointmentTable($database) {
    // Check if payment_status column exists
    $checkColumn = $database->query("SHOW COLUMNS FROM appointment LIKE 'payment_status'");
    if ($checkColumn->num_rows === 0) {
        // Add payment_status column with default value 'Unpaid'
        $addColumn = $database->query("ALTER TABLE appointment ADD COLUMN payment_status VARCHAR(20) DEFAULT 'Unpaid'");
        return ($addColumn) ? true : false;
    }
    return true;
}
?>