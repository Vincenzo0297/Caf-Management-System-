<?php

class ViewUserProfile
{
    private $user;

    public function __construct($userProfile)
    {
         $this->userProfile = $userProfile;
    }

	 public function getUserProfiles()
    {
        return $this->userProfile->getAllUserProfile();
    }

    // Add other methods related to user operations

    private function redirect($location)
    {
        header("Location: $location");
        exit;
    }
}

?>