<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/User.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

    $user = new User($conn);

    if ($user->reactivateUser($userId)) {
        $_SESSION['reactivate_user_message'] = "User reactivated successfully.";
    } else {
        $_SESSION['reactivate_user_message'] = "Failed to reactivate user.";
    }

    header("Location: ../pages/manage_user.php");
    exit();
} else {
    header("Location: ../pages/manage_user.php");
    exit();
}
?>
