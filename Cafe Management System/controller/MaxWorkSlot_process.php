<?php
require_once("../classes/User.php");
class MaxWorkSlot_process
{
	private $user;

    public function __construct($conn)
    {
		$this->user = new User($conn);
    }

 

    public function getAvailableStaff()
    {
        return $this->user->getAvailableStaff();
    }

    public function setMaxWorkSlot($staffId, $maxWorkSlot,$saveWorkSlot)
    {
        // Add your code to set the maximum work slot for a user
        // You might want to validate the input and handle errors appropriately
        $this->user->setUserMaxWorkSlot($staffId, $maxWorkSlot,$saveWorkSlot);
    }
}
?>
