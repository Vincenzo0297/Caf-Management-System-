<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/User.php'); // Include the User entity class

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Rest of your deletion logic here...
    
    if ($user->deleteUser($userId)) {
        // User deleted successfully
        $_SESSION['delete_user_message'] = "User successfully deleted.";
        header("Location: ../pages/manage_user.php"); // Redirect to the manage profiles page after deletion
        exit;
    } else {
        // Error occurred while deleting user
        $_SESSION['delete_user_message'] = "Error: User deletion failed.";
        header("Location: ../pages/manage_user.php"); // Redirect with an error message
        exit;
    }
} else {
    // Invalid request method or missing user ID
    echo "Invalid request.";
}
?>
