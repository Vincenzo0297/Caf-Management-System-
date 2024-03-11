<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/UserProfile.php');
class manage_user_profiles_process {
    private $userProfile;
    private $conn;

    public function __construct($conn) {
        $this->userProfile = new UserProfile($conn); // Pass the database connection to UserProfile
        $this->conn = $conn;
    }

     public function createForProfile($username, $canViewDashboard, $canManageBids, $canManageWorkSlots, $canBidForWorkSlots) {
        // Call the createProfile function from the UserProfile Entity class
        return $this->userProfile->createProfile($username, $canViewDashboard, $canManageBids, $canManageWorkSlots, $canBidForWorkSlots);
    }
}
?>