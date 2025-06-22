<?php

function getConnection() {
    // Use environment variables for production
    $tns_admin = $_ENV['TNS_ADMIN'] ?? getenv('TNS_ADMIN') ?? 'C:\\Wallet_ShelfControl';
    $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'admin';
    $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'ShelfControl2025.';
    $connection_string = $_ENV['DB_CONNECTION_STRING'] ?? getenv('DB_CONNECTION_STRING') ?? 'shelfcontrol_high';
    
    // Verify wallet directory exists
    if (!is_dir($tns_admin)) {
        error_log("Oracle wallet directory not found: " . $tns_admin);
        die("❌ Wallet directory not found: " . $tns_admin);
    }
    
    // Set TNS_ADMIN environment variable
    putenv("TNS_ADMIN=" . $tns_admin);
    
    // Attempt connection with better error handling
    $conn = oci_connect($username, $password, $connection_string, "AL32UTF8");

    if (!$conn) {
        $e = oci_error();
        $error_message = "Database connection failed";
        
        if (isset($e['message'])) {
            $error_message .= ": " . $e['message'];
        }
        
        // Log additional debugging information
        error_log("TNS_ADMIN: " . $tns_admin);
        error_log("Connection String: " . $connection_string);
        error_log("Username: " . $username);
        error_log($error_message);
        
        die("❌ " . $error_message);
    }

    return $conn;
}

// Create a default connection for backward compatibility
$conn = getConnection();