<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/UserProfile.php');

// Check if the user is logged in, if not, redirect to login page
$userProfile = new UserProfile($conn);
$userInfo = $userProfile->getUserProfileById($_SESSION['user_id']);

// Fetch user type from the database
$userType = $userInfo['Type']; // Assuming 'Type' is the column name in your users table

//update user information in the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newPhone = mysqli_real_escape_string($conn, $_POST['phone']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']); // Retrieve raw password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password

    // Update user information in the database using UPDATE SQL query
    $updateQuery = "UPDATE users SET username='$newUsername', email='$newEmail', phone='$newPhone', password='$hashedPassword' WHERE id={$_SESSION['user_id']}";

    if (mysqli_query($conn, $updateQuery)) {
        // Redirect users
        switch ($userType) {
            case 'system admin':
                header("Location: ../dashboard/dashboard_admin.php");
                break;
            case 'cafe owner':
                header("Location: ../dashboard/dashboard_owner.php");
                break;
            case 'cafe manager':
                header("Location: ../dashboard/dashboard_manager.php");
                break;
            case 'cafe staff':
                header("Location: ../dashboard/dashboard_staff.php");
                break;
        }
        exit();
    } else {
        // Handle update error
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>