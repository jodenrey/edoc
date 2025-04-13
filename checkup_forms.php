<?php
// File to create the checkup_forms table in the database

// Import database connection
include("connection.php");

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
    )";
    
    if ($database->query($createTableQuery)) {
        echo "Checkup forms table created successfully!";
    } else {
        echo "Error creating checkup forms table: " . $database->error;
    }
} else {
    echo "Checkup forms table already exists.";
}

// Optionally run the script once to create the table
// Then comment out the echo statements and use the file for reference
?> 