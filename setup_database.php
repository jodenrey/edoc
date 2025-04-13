<?php
// Include the database connection
include("connection.php");

// Read the SQL file
$sql = file_get_contents('create_checkup_table.sql');

// Execute the SQL
if ($database->multi_query($sql)) {
    echo "Table created successfully.<br>";
    
    // Clear the result sets from multi_query
    do {
        // Store the result
        if ($result = $database->store_result()) {
            $result->free();
        }
        // Move to next result set
    } while ($database->more_results() && $database->next_result());
    
} else {
    echo "Error creating table: " . $database->error . "<br>";
}

echo "Setup complete!";
?> 