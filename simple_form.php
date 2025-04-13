<?php
// This is an extremely simple form viewer without any redirects
session_start();

// Very basic authentication check
if (!isset($_SESSION["user"])) {
    echo "Please log in first.";
    exit;
}

// Get form ID
$form_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get user email from session
$user_email = $_SESSION["user"];

// Simple database connection directly in this file to avoid includes
$host = "localhost";
$username = "root";
$password = "password";
$database = "edoc";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID
$sql = "SELECT pid, pname FROM patient WHERE pemail='$user_email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patient_id = $row["pid"];
    $patient_name = $row["pname"];
} else {
    echo "Patient not found.";
    $conn->close();
    exit;
}

// Get form details using simple query
$sql = "SELECT cf.*, d.docname 
        FROM checkup_forms cf 
        LEFT JOIN doctor d ON cf.docid = d.docid 
        WHERE cf.form_id = $form_id AND cf.pid = $patient_id";
$result = $conn->query($sql);

// Check if form exists
if ($result->num_rows == 0) {
    echo "Form not found or you don't have permission to view it.<br>";
    echo "<a href='index.php'>Go back to home</a>";
    $conn->close();
    exit;
}

// Get form data
$form = $result->fetch_assoc();
$conn->close();

// Simple HTML output with minimal styling
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
        .back-btn {
            background-color: #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Medical Check-up Form</h1>
            <p>Doctor: <?php echo htmlspecialchars($form['docname']); ?></p>
            <p>Date: <?php echo date('F j, Y', strtotime($form['form_date'])); ?></p>
        </div>
        
        <div class="section">
            <h2>Patient Information</h2>
            <p><span class="label">Patient Name:</span> <?php echo htmlspecialchars($patient_name); ?></p>
            <p><span class="label">Height:</span> <?php echo htmlspecialchars($form['height'] ?: 'Not recorded'); ?></p>
            <p><span class="label">Weight:</span> <?php echo htmlspecialchars($form['weight'] ?: 'Not recorded'); ?></p>
            <p><span class="label">Blood Pressure:</span> <?php echo htmlspecialchars($form['blood_pressure'] ?: 'Not recorded'); ?></p>
        </div>
        
        <div class="section">
            <h2>Medical Details</h2>
            <p><span class="label">Diagnosis:</span> <?php echo htmlspecialchars($form['diagnosis'] ?: 'None'); ?></p>
            <p><span class="label">Treatment Plan:</span> <?php echo htmlspecialchars($form['plan'] ?: 'None'); ?></p>
            <p><span class="label">Doctor's Remarks:</span> <?php echo htmlspecialchars($form['doctor_remarks'] ?: 'None'); ?></p>
        </div>
        
        <div class="buttons">
            <button class="back-btn" onclick="location.href='index.php'">Back to Dashboard</button>
            <button onclick="window.print()">Print Form</button>
        </div>
    </div>
</body>
</html> 