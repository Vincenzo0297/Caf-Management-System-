<?php
session_start();

// Session timeout
$sessionTimeout = 3600;

// Check if last activity time is set and session has timed out
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionTimeout)) {
    session_unset();
    session_destroy();
    header("Location: ../pages/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

// Check if 'user_type' is set in the session before using it
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'cafe staff') {
        // Fetch and store the cafe role in the session
        $cafeRoleId = 1; // Replace this with the actual cafe role ID fetched from your database
        $_SESSION['cafe_role_id'] = $cafeRoleId;
    }
}
?>
