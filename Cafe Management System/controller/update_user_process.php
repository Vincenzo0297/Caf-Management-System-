<?php
//include('../config/session_start.php');
include('../config/db_config.php');
//include('../classes/UserProfile.php');
include('../classes/User.php');

class update_user_process
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function updateForUser($userId, $username, $password, $email, $phone, $newType)
    {
        $user = new User($this->conn);
        $updateResult = $user->updateUser($userId, $username, $password, $email, $phone, $newType);

        if ($updateResult) {
            $_SESSION['update_profile_success'] = true;
			// Redirect back to the manage_user.php page
			header("Location: manage_user.php");
			exit;
        } else {
            $_SESSION['update_profile_error'] = "Error updating user profile.";
			header("Location: manage_user.php");
			exit;
        }
    }
}
?>
