<?php
/*
 * REDIRECT LOGGER - Place this at the top of any file that's redirecting unexpectedly
 * By including this file at the top of your PHP scripts, it will intercept and log
 * any header() redirects, making it easier to debug redirect issues.
 */

// Start a session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the log file path
$logFile = __DIR__ . '/redirect_log.txt';

// Add entry point information
$entryInfo = [
    'time' => date('Y-m-d H:i:s'),
    'script' => $_SERVER['SCRIPT_FILENAME'],
    'uri' => $_SERVER['REQUEST_URI'],
    'get' => $_GET,
    'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'none'
];

file_put_contents($logFile, "ENTRY: " . json_encode($entryInfo) . "\n", FILE_APPEND);

// Override the header function to log redirects
function __override_header($header, $replace = true, $http_response_code = 0) {
    global $logFile;
    
    // Check if it's a location header (redirect)
    if (stripos($header, 'location:') === 0) {
        $location = trim(substr($header, 9));
        
        // Log the redirect
        $redirectInfo = [
            'time' => date('Y-m-d H:i:s'),
            'from' => $_SERVER['SCRIPT_FILENAME'],
            'to' => $location,
            'get' => $_GET,
            'session' => isset($_SESSION) ? array_keys($_SESSION) : []
        ];
        
        file_put_contents($logFile, "REDIRECT: " . json_encode($redirectInfo) . "\n", FILE_APPEND);
    }
    
    // Call the original header function
    return header($header, $replace, $http_response_code);
}

// Override the header function using runkit if available
if (extension_loaded('runkit') || extension_loaded('runkit7')) {
    if (function_exists('runkit_function_rename')) {
        runkit_function_rename('header', 'original_header');
        runkit_function_rename('__override_header', 'header');
    } elseif (function_exists('runkit7_function_rename')) {
        runkit7_function_rename('header', 'original_header');
        runkit7_function_rename('__override_header', 'header');
    }
} else {
    // If runkit is not available, log that we can't intercept redirects
    file_put_contents($logFile, "WARNING: Cannot intercept redirects - runkit extension not available\n", FILE_APPEND);
}

// Instructions for the user
echo "<!-- Redirect logger active. Logging to: $logFile -->";
?> 