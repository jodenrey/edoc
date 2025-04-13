<?php
// This is a diagnostic script to help debug the view_checkup.php redirection issue

// Start with some basic information
echo "<h1>Checkup Form Debug Information</h1>";

// Check if database connection works
require_once("connection.php");
echo "<h2>Database Connection</h2>";
if ($database) {
    echo "<p style='color:green'>✓ Database connection established</p>";
} else {
    echo "<p style='color:red'>✗ Database connection failed</p>";
    exit;
}

// Check if checkup_forms table exists
echo "<h2>Table Structure</h2>";
$checkTableQuery = $database->query("SHOW TABLES LIKE 'checkup_forms'");
if ($checkTableQuery->num_rows > 0) {
    echo "<p style='color:green'>✓ checkup_forms table exists</p>";
    
    // Show table structure
    $tableStructure = $database->query("DESCRIBE checkup_forms");
    echo "<h3>checkup_forms Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 80%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($field = $tableStructure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $field['Field'] . "</td>";
        echo "<td>" . $field['Type'] . "</td>";
        echo "<td>" . $field['Null'] . "</td>";
        echo "<td>" . $field['Key'] . "</td>";
        echo "<td>" . $field['Default'] . "</td>";
        echo "<td>" . $field['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>✗ checkup_forms table does not exist</p>";
    
    // Create the table if it doesn't exist
    echo "<p>Attempting to create the table...</p>";
    include_once("setup_tables.php");
    if (createCheckupFormsTable($database)) {
        echo "<p style='color:green'>✓ Table created successfully!</p>";
    } else {
        echo "<p style='color:red'>✗ Failed to create table: " . $database->error . "</p>";
    }
}

// Test the link
echo "<h2>Link Testing</h2>";
echo "<p>Simulated link: <a href='view_checkup.php?id=1'>view_checkup.php?id=1</a></p>";

// Check for available forms
echo "<h2>Available Forms</h2>";
$formsQuery = $database->query("SELECT * FROM checkup_forms LIMIT 10");
if ($formsQuery->num_rows > 0) {
    echo "<p>Found " . $formsQuery->num_rows . " forms:</p>";
    echo "<table border='1' style='border-collapse: collapse; width: 80%;'>";
    echo "<tr><th>ID</th><th>Patient ID</th><th>Doctor ID</th><th>Date</th><th>Action</th></tr>";
    
    while ($form = $formsQuery->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $form['form_id'] . "</td>";
        echo "<td>" . $form['pid'] . "</td>";
        echo "<td>" . $form['docid'] . "</td>";
        echo "<td>" . $form['form_date'] . "</td>";
        echo "<td><a href='view_checkup.php?id=" . $form['form_id'] . "'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No forms found in the database.</p>";
}

// Check for issues with view_checkup.php
echo "<h2>View Checkup File Check</h2>";
if (file_exists("view_checkup.php")) {
    echo "<p style='color:green'>✓ view_checkup.php file exists</p>";
    $fileSize = filesize("view_checkup.php");
    echo "<p>File size: " . $fileSize . " bytes</p>";
    
    // Check for common issues in the file
    $fileContent = file_get_contents("view_checkup.php");
    $issues = [];
    
    if (strpos($fileContent, "header(\"location:") !== false) {
        $issues[] = "File contains redirect headers that might be interfering";
    }
    
    if (strpos($fileContent, "patient/index.php") !== false) {
        $issues[] = "File contains references to 'patient/index.php' which might be causing wrong redirects";
    }
    
    if (empty($issues)) {
        echo "<p style='color:green'>✓ No common issues found in the file</p>";
    } else {
        echo "<p style='color:orange'>⚠ Potential issues found:</p>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li>" . $issue . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color:red'>✗ view_checkup.php file does not exist</p>";
}

// Provide some debugging tools
echo "<h2>Debugging Tools</h2>";
echo "<p>1. <a href='create_tables.php'>Run Setup Script</a> - Create or check database tables</p>";
echo "<p>2. <a href='patient/index.php'>Go to Patient Dashboard</a> - Return to dashboard</p>";

// Show PHP information
echo "<h2>PHP Environment</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Path: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
?> 