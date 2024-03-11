<?php
include('../config/db_config.php');
//include('../classes/User.php');
include('../config/session_start.php');
class login_process {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function processLogin($username, $password) {
        // Your login processing logic here
        $user = new User($this->conn);
        $authResult = $user->authenticate($username, $password);

        // Handle authentication results and redirects
        if ($authResult === 'cafe staff') {
            $_SESSION['user_type'] = 'cafe staff';
            $_SESSION['cafe_role_id'] = $cafeRoleId; // Set the actual cafe role ID here
            // Redirect to the appropriate location for cafe staff
            header('Location: ../dashboard/dashboard_staff.php');
            exit;
        } elseif ($authResult === 'system admin') {
            // Handle the redirection for system admin
			$_SESSION['user_type'] = 'system admin';
            header('Location: ../dashboard/dashboard_admin.php');
            exit;
        } elseif ($authResult === 'cafe owner') {
            // Handle the redirection for cafe owner
			$_SESSION['user_type'] = 'cafe owner';
            header('Location: ../dashboard/dashboard_owner.php');
            exit;
        } elseif ($authResult === 'cafe manager') {
            // Handle the redirection for cafe manager
			$_SESSION['user_type'] = 'cafe manager';
            header('Location: ../dashboard/dashboard_manager.php');
            exit;
        } else {
            $_SESSION['login_error'] = 'Incorrect username or password'; // Display the error message
            header('Location: ../pages/login.php');
            exit;
        }
    }
}
?>
