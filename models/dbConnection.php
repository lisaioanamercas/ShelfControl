<?php

function getConnection() {
    putenv("TNS_ADMIN=C:\\Wallet_ShelfControl");
    $conn = oci_connect("admin", "ShelfControl2025.", "shelfcontrol_high");

    if (!$conn) {
        $e = oci_error();
        error_log("Database connection failed: " . $e['message']);
        die("❌ Connection failed: " . $e['message']);
    }

    return $conn;
}

// Create a default connection for backward compatibility
$conn = getConnection();