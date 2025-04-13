<?php
// Include database connection
include "connection.php";

echo "Checking for payment_status column in appointment table...\n";

// Check if payment_status column exists
$checkColumn = $database->query("SHOW COLUMNS FROM appointment LIKE 'payment_status'");

if ($checkColumn->num_rows === 0) {
    echo "payment_status column doesn't exist. Adding it...\n";
    
    // Add payment_status column with default value 'Unpaid'
    $addColumn = $database->query("ALTER TABLE appointment ADD COLUMN payment_status VARCHAR(20) DEFAULT 'Unpaid'");
    
    if ($addColumn) {
        echo "Successfully added payment_status column\n";
    } else {
        echo "Error adding column: " . $database->error . "\n";
    }
} else {
    echo "payment_status column already exists\n";
}

echo "Done!\n";
?> 