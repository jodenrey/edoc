<?php
session_start();

// Check if user is logged in as a patient
if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'p') {
    header("location: login.php");
    exit;
}

// Import database
include("connection.php");

// Make sure we have a form ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No form ID provided.";
    exit;
}

// Get the form ID from the URL
$formId = $_GET['id'];

// Get user details
$useremail = $_SESSION["user"];
$userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

// Check if checkup_forms table exists
$checkTableQuery = $database->query("SHOW TABLES LIKE 'checkup_forms'");
if ($checkTableQuery->num_rows === 0) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Check-up forms feature is not available yet</h2>";
    echo "<p>No doctor has submitted any check-up forms.</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background-color:#2196F3; color:white; text-decoration:none; border-radius:4px;'>Return to Dashboard</a>";
    echo "</div>";
    exit;
}

// Get the form details
$formQuery = $database->query("SELECT cf.*, d.docname, s.title 
                              FROM checkup_forms cf 
                              INNER JOIN appointment a ON cf.appoid = a.appoid 
                              INNER JOIN schedule s ON a.scheduleid = s.scheduleid 
                              INNER JOIN doctor d ON cf.docid = d.docid 
                              WHERE cf.form_id='$formId' AND cf.pid='$userid'");

// Check if the form exists and belongs to this patient
if ($formQuery->num_rows == 0) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Form not found or you don't have permission to view it</h2>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background-color:#2196F3; color:white; text-decoration:none; border-radius:4px;'>Return to Dashboard</a>";
    echo "</div>";
    exit;
}

$formData = $formQuery->fetch_assoc();
$formJson = json_decode($formData['form_data'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/admin.css">
    <title>Check-up Form</title>
    <style>
        .form-container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .checkup-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        
        .checkup-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        
        .section-title {
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        
        .form-field {
            margin-right: 20px;
            margin-bottom: 10px;
            flex: 1;
            min-width: 200px;
        }
        
        .form-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #666;
        }
        
        .form-field-value {
            font-weight: 600;
            padding: 5px 0;
        }
        
        .form-field-label {
            color: #444;
            font-weight: 600;
        }
        
        .form-field-data {
            color: #000;
        }
        
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-back {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        @media print {
            .btn-container, .menu {
                display: none;
            }
            
            .form-container {
                box-shadow: none;
                width: 100%;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .dash-body {
                margin: 0;
                padding: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body">
            <div class="form-container">
                <div class="checkup-header">
                    <h2>CHECK UP FORM</h2>
                    <p>Doctor: <?php echo $formData['docname']; ?> | Date: <?php echo date('F j, Y', strtotime($formData['form_date'])); ?></p>
                    <p>Session: <?php echo $formData['title']; ?></p>
                </div>
                
                <!-- Personal Information -->
                <div class="checkup-section">
                    <div class="section-title">Personal Information</div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Patient Name:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['patientName']) ? $formJson['patientName'] : '-'; ?> <?php echo isset($formJson['firstName']) ? $formJson['firstName'] : ''; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Patient ID:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['patientId']) ? $formJson['patientId'] : '-'; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Date of Birth:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['dob']) ? $formJson['dob'] : '-'; ?></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Age:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['patientAge']) ? $formJson['patientAge'] : '-'; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Accompanied By:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['accompaniedBy']) ? $formJson['accompaniedBy'] : '-'; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Relationship:</label>
                            <div class="form-field-value form-field-data"><?php echo isset($formJson['relationship']) ? $formJson['relationship'] : '-'; ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Physical Examination -->
                <div class="checkup-section">
                    <div class="section-title">Physical Examination</div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Height:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['height'] ?: '-'; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Weight:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['weight'] ?: '-'; ?></div>
                        </div>
                        <div class="form-field">
                            <label class="form-field-label">Blood Pressure:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['blood_pressure'] ?: '-'; ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Medical History -->
                <div class="checkup-section">
                    <div class="section-title">Medical History</div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Past Medical History WNL:</label>
                            <div class="form-field-value form-field-data">
                                <?php echo isset($formJson['pastMedical']) ? $formJson['pastMedical'] : '-'; ?>
                                <?php if (isset($formJson['pastMedicalDesc']) && !empty($formJson['pastMedicalDesc'])): ?>
                                <p><strong>Description:</strong> <?php echo $formJson['pastMedicalDesc']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Developmental History WNL:</label>
                            <div class="form-field-value form-field-data">
                                <?php echo isset($formJson['developmentalHistory']) ? $formJson['developmentalHistory'] : '-'; ?>
                                <?php if (isset($formJson['developmentalDesc']) && !empty($formJson['developmentalDesc'])): ?>
                                <p><strong>Description:</strong> <?php echo $formJson['developmentalDesc']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Behavioral Health Status WNL:</label>
                            <div class="form-field-value form-field-data">
                                <?php echo isset($formJson['behavioralHealth']) ? $formJson['behavioralHealth'] : '-'; ?>
                                <?php if (isset($formJson['behavioralDesc']) && !empty($formJson['behavioralDesc'])): ?>
                                <p><strong>Description:</strong> <?php echo $formJson['behavioralDesc']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Diagnosis & Plan -->
                <div class="checkup-section">
                    <div class="section-title">Diagnosis & Plan</div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Diagnosis:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['diagnosis'] ?: '-'; ?></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Plan:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['plan'] ?: '-'; ?></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-field-label">Doctor's Remarks:</label>
                            <div class="form-field-value form-field-data"><?php echo $formData['doctor_remarks'] ?: '-'; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="btn-container">
                    <button class="btn-back" onclick="window.location.href='index.php'">Back to Dashboard</button>
                    <button class="btn-print" onclick="window.print()">Print Check-up Form</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 