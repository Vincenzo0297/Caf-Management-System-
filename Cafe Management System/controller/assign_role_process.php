<?php
include('../classes/UserProfile.php');
class assign_role_process {
    private $userProfile;

    public function __construct($userProfile) {
        $this->userProfile = $userProfile;
    }

    public function assignRole($staffId, $assignedRole) {
        if ($this->userProfile->assignUserRole($staffId, $assignedRole)) {
            // Role assigned successfully
            return true;
        } else {
            // Error occurred while assigning the role
            return false;
        }
    }
}
?>