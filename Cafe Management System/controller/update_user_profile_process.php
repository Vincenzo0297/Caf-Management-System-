<?php
include('../config/session_start.php');
include('../config/db_config.php');

class update_user_profile_process {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function updateForUserProfile($userId, $postData) {

		$userProfileController = new update_user_profile_process($conn);

		if (isset($_GET['id'])) {
			$userId = $_GET['id'];
			$userProfileController->updateUserProfile($userId, $_POST);
		} else {
			header("Location: ../pages/manage_user_profiles.php");
			exit;
		}
	}
}
?>