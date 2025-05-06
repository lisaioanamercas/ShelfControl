<?php

putenv("TNS_ADMIN=C:\\Wallet_ShelfControl");
$conn = oci_connect("admin", "ShelfControl2025.", "shelfcontrol_high");

if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}
