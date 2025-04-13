<?php
// Start session
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    echo "Unauthorized access";
    exit;
}

// Import database connection
include("connection.php");

// Get the user's email from session
$useremail = $_SESSION["user"];

// Get the patient's ID
$patientQuery = $database->query("SELECT pid FROM patient WHERE pemail='$useremail'");
$patientData = $patientQuery->fetch_assoc();
$patientId = $patientData["pid"];

// Check if form ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Form ID is required";
    exit;
}

$formId = $_GET['id'];
$isModal = isset($_GET['modal']) && $_GET['modal'] === 'true';

// Fetch the form data
$formQuery = "SELECT cd.*, a.pid, d.docname, s.title as session_title, s.scheduledate 
              FROM checkup_data cd 
              INNER JOIN appointment a ON cd.appoid = a.appoid 
              INNER JOIN schedule s ON a.scheduleid = s.scheduleid 
              INNER JOIN doctor d ON s.docid = d.docid 
              WHERE cd.id = ? AND a.pid = ?";

$stmt = $database->prepare($formQuery);
$stmt->bind_param("ii", $formId, $patientId);
$stmt->execute();
$result = $stmt->get_result();

// Check if form exists and belongs to this patient
if ($result->num_rows === 0) {
    echo "Form not found or you don't have permission to view it";
    exit;
}

$formData = $result->fetch_assoc();

// Process checkbox data if it exists
$checkboxData = [];
if (isset($formData['checkboxData']) && !empty($formData['checkboxData'])) {
    $checkboxData = json_decode($formData['checkboxData'], true) ?: [];
}

// Helper function to check if a checkbox was checked
function wasChecked($key, $checkboxData) {
    return isset($checkboxData[$key]) && ($checkboxData[$key] === 'true' || $checkboxData[$key] === true);
}

// Helper function to get radio selection
function getRadioValue($name, $formData, $checkboxData) {
    if (isset($formData[$name])) {
        return $formData[$name];
    }
    
    $yesKey = $name . 'Yes';
    $noKey = $name . 'No';
    
    if (wasChecked($yesKey, $checkboxData)) {
        return 'Yes';
    } else if (wasChecked($noKey, $checkboxData)) {
        return 'No';
    }
    
    return '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Up Form</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            padding: 0;
            line-height: 1.6;
            <?php if ($isModal): ?>
            margin: 0;
            <?php endif; ?>
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
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
        h4 {
            margin: 5px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .label {
            font-weight: bold;
            width: 30%;
        }
        .value {
            width: 70%;
        }
        .checkbox-label {
            display: inline-block;
            margin-right: 15px;
        }
        .checkbox-value {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #333;
            margin-right: 5px;
            position: relative;
            top: 2px;
        }
        .checkbox-checked {
            background-color: #333;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .container { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$isModal): ?>
        <div class="no-print" style="text-align: right; margin-bottom: 20px;">
            <button onclick="window.print()">Print Form</button>
            <button onclick="window.location.href='patient/index.php'">Back to Dashboard</button>
        </div>
        <?php endif; ?>
        
        <div class="checkup-header">
            <h2 style="text-align:center; margin:5px 0;">CHECK UP FORM</h2>
            <div style="text-align:right; margin:5px 0;">
                <span>FORM DATE: <?php echo date('M d, Y', strtotime($formData['created_at'])); ?></span>
                <div style="margin-top:5px;">
                    <span class="checkbox-label">
                        <span class="checkbox-value <?php echo wasChecked('periodic', $checkboxData) ? 'checkbox-checked' : ''; ?>"></span>
                        Periodic
                    </span>
                    <span class="checkbox-label">
                        <span class="checkbox-value <?php echo wasChecked('interpPeriodic', $checkboxData) ? 'checkbox-checked' : ''; ?>"></span>
                        Interperiodic
                    </span>
                    <span class="checkbox-label">
                        <span class="checkbox-value <?php echo wasChecked('parentRequest', $checkboxData) ? 'checkbox-checked' : ''; ?>"></span>
                        Parent/Caregiver Request
                    </span>
                </div>
            </div>
        </div>
        
        <!-- PERSONAL -->
        <div class="checkup-section">
            <h4>PERSONAL</h4>
            <table>
                <tr>
                    <td colspan="2">
                        <div style="margin-bottom: 5px;"><strong>NAME:</strong></div>
                        <div><?php echo htmlspecialchars($formData['patientName'] . ' ' . $formData['firstName']); ?></div>
                    </td>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>ID:</strong></div>
                        <div><?php echo htmlspecialchars($formData['patientId'] ?? ''); ?></div>
                    </td>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>DATE OF BIRTH:</strong></div>
                        <div><?php echo $formData['dob'] ? date('M d, Y', strtotime($formData['dob'])) : ''; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>SESSION:</strong></div>
                        <div><?php echo htmlspecialchars($formData['session_title'] ?? ''); ?></div>
                    </td>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>AGE:</strong></div>
                        <div><?php echo htmlspecialchars($formData['patientAge'] ?? ''); ?></div>
                    </td>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>ACCOMPANIED BY:</strong></div>
                        <div><?php echo htmlspecialchars($formData['accompaniedBy'] ?? ''); ?></div>
                    </td>
                    <td>
                        <div style="margin-bottom: 5px;"><strong>RELATIONSHIP:</strong></div>
                        <div><?php echo htmlspecialchars($formData['relationship'] ?? ''); ?></div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- INTERVAL HISTORY -->
        <div class="checkup-section">
            <h4>INTERVAL HISTORY</h4>
            <table>
                <tr>
                    <td>
                        <div><strong>PAST MEDICAL HISTORY WNL:</strong> <?php echo getRadioValue('pastMedical', $formData, $checkboxData); ?></div>
                        <?php if (!empty($formData['pastMedicalDesc'])): ?>
                        <div style="margin-top: 5px;"><strong>Description:</strong> <?php echo htmlspecialchars($formData['pastMedicalDesc']); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><strong>DEVELOPMENTAL HISTORY WNL:</strong> <?php echo getRadioValue('developmentalHistory', $formData, $checkboxData); ?></div>
                        <?php if (!empty($formData['developmentalDesc'])): ?>
                        <div style="margin-top: 5px;"><strong>Description:</strong> <?php echo htmlspecialchars($formData['developmentalDesc']); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><strong>BEHAVIORAL HEALTH STATUS WNL:</strong> <?php echo getRadioValue('behavioralHealth', $formData, $checkboxData); ?></div>
                        <?php if (!empty($formData['behavioralDesc'])): ?>
                        <div style="margin-top: 5px;"><strong>Description:</strong> <?php echo htmlspecialchars($formData['behavioralDesc']); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- PHYSICAL EXAM -->
        <div class="checkup-section">
            <h4>PHYSICAL EXAM</h4>
            <table>
                <tr>
                    <td style="width:33%;">
                        <div><strong>HEIGHT:</strong> <?php echo htmlspecialchars($formData['height'] ?? ''); ?></div>
                    </td>
                    <td style="width:33%;">
                        <div><strong>WEIGHT:</strong> <?php echo htmlspecialchars($formData['weight'] ?? ''); ?></div>
                    </td>
                    <td style="width:33%;">
                        <div><strong>BLOOD PRESSURE:</strong> <?php echo htmlspecialchars($formData['bloodPressure'] ?? ''); ?></div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- DIAGNOSIS -->
        <div class="checkup-section">
            <h4>DIAGNOSIS</h4>
            <div><?php echo nl2br(htmlspecialchars($formData['diagnosis'] ?? '')); ?></div>
        </div>
        
        <!-- TREATMENT PLAN -->
        <div class="checkup-section">
            <h4>TREATMENT PLAN</h4>
            <div><?php echo nl2br(htmlspecialchars($formData['plan'] ?? '')); ?></div>
        </div>
        
        <!-- DOCTOR REMARKS -->
        <div class="checkup-section">
            <h4>DOCTOR'S REMARKS</h4>
            <div><?php echo nl2br(htmlspecialchars($formData['doctorRemarks'] ?? '')); ?></div>
        </div>
        
        <div style="text-align: right; margin-top: 20px;">
            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($formData['docname'] ?? ''); ?></p>
            <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($formData['created_at'])); ?></p>
        </div>
    </div>
</body>
</html> 