<?php

if (!is_readable("C:\\Wallet_ShelfControl\\sqlnet.ora")) {
    die("❌ PHP cannot read sqlnet.ora — check permissions or path!");
}

putenv("TNS_ADMIN=C:\\Wallet_ShelfControl");

$walletPath = "C:\\Wallet_ShelfControl";

if (!is_readable($walletPath . "\\sqlnet.ora")) {
    die("❌ PHP can't read sqlnet.ora — path or permissions wrong!");
}

if (!is_readable($walletPath . "\\tnsnames.ora")) {
    die("❌ PHP can't read tnsnames.ora!");
}


$conn = oci_connect("admin", "ShelfControl2025.", "shelfcontrol_high");

if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}

echo "✅ Connected to Oracle DB!";
oci_close($conn);
?>