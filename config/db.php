<?php
// Step 1: Tell PHP where to find the Oracle Wallet
putenv("TNS_ADMIN=C:/Users/Elisa/Documents/OracleWallet/Wallet_ShelfControl");

// Step 2: Attempt connection using OCI8
$conn = oci_connect(
    'admin',                 // Username for Oracle DB
    'ShelfControl2025.',// Replace with your actual password
    'shelfcontrol_high'      // Wallet-based service name from tnsnames.ora
);

if (!$conn) {
    // Step 3: If connection fails, display error
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}

echo "✅ Connected successfully to Oracle Autonomous DB!";
oci_close($conn);
?>
