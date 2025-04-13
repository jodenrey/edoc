<?php
// Include database connection
include("connection.php");
session_start();

// Check if the user is logged in as a doctor
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

// Process the request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if appointment ID exists in request
    if (!isset($_POST['appoid']) || empty($_POST['appoid'])) {
        echo json_encode(['status' => 'error', 'message' => 'Appointment ID is required']);
        exit;
    }

    $appoid = $_POST['appoid'];
    
    // First check if the checkup_data table exists
    $tableExists = false;
    $checkTableQuery = "SHOW TABLES LIKE 'checkup_data'";
    $tableResult = $database->query($checkTableQuery);
    
    if ($tableResult && $tableResult->num_rows > 0) {
        $tableExists = true;
    }
    
    if (!$tableExists) {
        // Table doesn't exist, return empty result
        echo json_encode(['status' => 'success', 'checkupData' => null]);
        exit;
    }
    
    // Query to fetch checkup data from database
    $query = "SELECT * FROM checkup_data WHERE appoid = ?";
    $stmt = $database->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $database->error]);
        exit;
    }
    
    $stmt->bind_param("i", $appoid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch checkup data
        $checkupData = $result->fetch_assoc();
        
        // Process checkbox data if it exists
        if (isset($checkupData['checkboxData']) && !empty($checkupData['checkboxData'])) {
            $checkboxData = json_decode($checkupData['checkboxData'], true);
            
            // Merge checkbox data into the main data array
            if (is_array($checkboxData)) {
                $checkupData = array_merge($checkupData, $checkboxData);
            }
            
            // Remove the raw JSON data from the response
            unset($checkupData['checkboxData']);
        }
        
        // Return success with data
        echo json_encode(['status' => 'success', 'checkupData' => $checkupData]);
    } else {
        // No data found
        echo json_encode(['status' => 'success', 'checkupData' => null]);
    }
    
    $stmt->close();
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?> 