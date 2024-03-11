<?php
require_once('../classes/User.php');

class view_available_staff_process
{
    private $user;

    public function __construct($conn)
    {
        $this->user = new User($conn);
    }

    public function getAllAvailableStaff()
    {
        return $this->user->getAvailableStaff();
    }


    // Add other methods for managing bids here
}
?>
