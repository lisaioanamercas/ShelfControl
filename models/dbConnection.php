<?php

function getConnection() {
    // Use environment variables for production
    $tns_admin = $_ENV['TNS_ADMIN'] ?? getenv('TNS_ADMIN') ?? 'C:\\Wallet_ShelfControl';
    $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'admin';
    $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'ShelfControl2025.';
    $connection_string = $_ENV['DB_CONNECTION_STRING'] ?? getenv('DB_CONNECTION_STRING') ?? 'shelfcontrol_high';
    
    putenv("TNS_ADMIN=" . $tns_admin);
    $conn = oci_connect($username, $password, $connection_string, "AL32UTF8");

    if (!$conn) {
        $e = oci_error();
        error_log("Database connection failed: " . $e['message']);
        die("❌ Connection failed: " . $e['message']);
    }

    return $conn;
}

// Create a default connection for backward compatibility
$conn = getConnection();