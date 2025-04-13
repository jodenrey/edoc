<?php
// This script sets up all required tables for the eDoc application
// It's designed to be included by other files when tables need to be created

// Import database connection
include_once("connection.php");

// Function to check if a table exists
function tableExists($database, $tableName) {
    $checkQuery = $database->query("SHOW TABLES LIKE '$tableName'");
    return $checkQuery->num_rows > 0;
}

// Function to create the checkup_forms table if it doesn't exist
function createCheckupFormsTable($database) {
    if (!tableExists($database, 'checkup_forms')) {
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
        
        return $database->query($createTableQuery);
    }
    return true; // Table already exists
}

// Function to create the payments table if it doesn't exist
function createPaymentsTable($database) {
    if (!tableExists($database, 'payments')) {
        $createTableQuery = "
        CREATE TABLE `payments` (
            `payment_id` int(11) NOT NULL AUTO_INCREMENT,
            `pid` int(11) NOT NULL,
            `appoid` int(11) NOT NULL,
            `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
            `payment_type` enum('Cash','Cashless','N/A') NOT NULL DEFAULT 'N/A',
            `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
            `doctor_remarks` text DEFAULT NULL,
            PRIMARY KEY (`payment_id`),
            KEY `pid` (`pid`),
            KEY `appoid` (`appoid`)
        )";
        
        return $database->query($createTableQuery);
    }
    return true; // Table already exists
}

// This section will run if this script is called directly
// It's useful for manual database setup
if (basename($_SERVER['PHP_SELF']) == 'setup_tables.php') {
    $results = array();
    
    // Try to create each table and store the results
    $results['checkup_forms'] = createCheckupFormsTable($database) ? 'Success' : 'Error: ' . $database->error;
    $results['payments'] = createPaymentsTable($database) ? 'Success' : 'Error: ' . $database->error;
    
    // Display results
    echo "<h2>Database Setup Results</h2>";
    echo "<ul>";
    foreach ($results as $table => $result) {
        echo "<li>$table: $result</li>";
    }
    echo "</ul>";
    echo "<p><a href='index.php'>Return to Homepage</a></p>";
}
?> 