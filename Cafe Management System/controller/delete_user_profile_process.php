<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/User.php'); // Include the User entity class
include('../classes/UserProfile.php');// Include the User entity class

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Create an instance of the User class
    $user = new User($conn);

    // Call the deleteUser method to delete the user
    if ($user->deleteUser($userId)) {
        // User deleted successfully
        header("Location: ../pages/manage_user_profiles.php"); // Redirect to the manage profiles page after deletion
        exit;
    } else {
        // Error occurred while deleting user
        echo "Error: User deletion failed.";
    }
} else {
    // Invalid request method or missing user ID
    echo "Invalid request.";
}
?>
