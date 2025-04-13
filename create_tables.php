<?php
// Include database connection
include("connection.php");

// Display header
echo "<html><head><title>Database Setup</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
    .container { max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
    h1 { color: #333; }
    .success { color: green; padding: 10px; background-color: #f0fff0; border-radius: 5px; margin-bottom: 10px; }
    .error { color: red; padding: 10px; background-color: #fff0f0; border-radius: 5px; margin-bottom: 10px; }
    .warning { color: orange; padding: 10px; background-color: #fffcf0; border-radius: 5px; margin-bottom: 10px; }
    .info { color: blue; padding: 10px; background-color: #f0f8ff; border-radius: 5px; margin-bottom: 10px; }
</style>";
echo "</head><body><div class='container'>";
echo "<h1>Database Setup</h1>";

// Function to execute SQL statements one by one
function executeSqlFile($database, $file) {
    // Read the SQL file
    $sql = file_get_contents($file);
    
    // Remove comments
    $sql = preg_replace('/\/\*.*?\*\/|--.*?\n|#.*?\n/', '', $sql);
    
    // Split the SQL file by semicolons to get individual queries
    $queries = preg_split('/;\s*$/m', $sql);
    
    $count = 0;
    $errors = 0;
    $warnings = 0;
    
    // Execute each query
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        // Execute the query
        try {
            if ($database->query($query)) {
                $count++;
            } else {
                echo "<div class='error'>Error executing query: " . $database->error . "</div>";
                $errors++;
            }
        } catch (mysqli_sql_exception $e) {
            // Check if the error is about table already existing
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "<div class='warning'>Notice: " . $e->getMessage() . "</div>";
                $warnings++;
            } else {
                echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
                $errors++;
            }
        }
    }
    
    return [
        'success' => $count,
        'errors' => $errors,
        'warnings' => $warnings
    ];
}

// Execute only the checkup_data table part
echo "<h2>Setting up missing tables...</h2>";

// Option 1: Create just the checkup_data table (recommended)
$checkupTableSQL = "
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `appoid` (`appoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Add payment_status to patient table if it doesn't exist
$addPaymentStatusSQL = "
ALTER TABLE `patient` 
ADD COLUMN IF NOT EXISTS `payment_status` enum('Paid','Unpaid','Pending') DEFAULT 'Unpaid';
";

// Create payments table if it doesn't exist
$createPaymentsSQL = "
CREATE TABLE IF NOT EXISTS `payments` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
";

// Execute the individual SQL statements
try {
    if ($database->query($checkupTableSQL)) {
        echo "<div class='success'>Checkup data table created or already exists</div>";
    } else {
        echo "<div class='error'>Error creating checkup_data table: " . $database->error . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}

try {
    if ($database->query($addPaymentStatusSQL)) {
        echo "<div class='success'>Payment status column added to patient table or already exists</div>";
    } else {
        echo "<div class='error'>Error adding payment_status column: " . $database->error . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='warning'>Note: " . $e->getMessage() . "</div>";
}

try {
    if ($database->query($createPaymentsSQL)) {
        echo "<div class='success'>Payments table created or already exists</div>";
    } else {
        echo "<div class='error'>Error creating payments table: " . $database->error . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}

// Option 2: Try to run the full SQL file (if you prefer)
if (false) { // Set to true if you want to run the full file
    if (file_exists('sql_database_edoc_merged.sql')) {
        $result = executeSqlFile($database, 'sql_database_edoc_merged.sql');
        
        echo "<div class='info'>Executed {$result['success']} queries successfully.</div>";
        
        if ($result['warnings'] > 0) {
            echo "<div class='warning'>{$result['warnings']} tables already exist (not an error).</div>";
        }
        
        if ($result['errors'] > 0) {
            echo "<div class='warning'>{$result['errors']} queries failed to execute. Check the error messages above.</div>";
        } else {
            echo "<div class='success'>Database setup completed successfully!</div>";
        }
    } else {
        echo "<div class='error'>SQL file 'sql_database_edoc_merged.sql' not found!</div>";
    }
}

// Verify essential tables exist
echo "<h2>Verifying tables...</h2>";
$essentialTables = ['checkup_data', 'payments'];
$missingTables = [];

foreach ($essentialTables as $table) {
    $result = $database->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        $missingTables[] = $table;
    }
}

if (empty($missingTables)) {
    echo "<div class='success'>All needed tables are properly set up.</div>";
} else {
    echo "<div class='warning'>Missing tables: " . implode(', ', $missingTables) . "</div>";
}

echo "<p><a href='index.php'>Return to Homepage</a></p>";
echo "</div></body></html>";
?> 