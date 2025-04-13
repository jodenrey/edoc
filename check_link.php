<?php
// This is a diagnostic script to track where links are going

// Start session to check user
session_start();

echo "<h1>Link Test Page</h1>";

// Show current URL and script path
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Script Path: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Check query parameters
echo "<h2>Query Parameters</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

// Trace HTTP_REFERER if available
echo "<h2>HTTP Referrer</h2>";
if (isset($_SERVER['HTTP_REFERER'])) {
    echo "<p>Came from: " . $_SERVER['HTTP_REFERER'] . "</p>";
} else {
    echo "<p>No referrer information available</p>";
}

// Check session status
echo "<h2>Session Information</h2>";
if (isset($_SESSION["user"])) {
    echo "<p>User: " . $_SESSION["user"] . "</p>";
    echo "<p>User Type: " . $_SESSION["usertype"] . "</p>";
} else {
    echo "<p>No session active</p>";
}

// Link testing
echo "<h2>Test Links</h2>";
echo "<p>Click these links to test where they go:</p>";
echo "<ul>";
echo "<li><a href='view_checkup.php?id=1'>Test view_checkup.php</a></li>";
echo "<li><a href='patient_form_view.php?id=1'>Test patient_form_view.php</a></li>";
echo "<li><a href='patient/schedule.php'>Patient Schedule Page</a></li>";
echo "</ul>";
?> 