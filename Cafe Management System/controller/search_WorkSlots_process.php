<?php
require_once('../classes/WorkSlotManager.php');

class searchWorkSlots_process
{
    private $workSlotManager;

    public function __construct($conn)
    {
        $this->workSlotManager = new WorkSlotManager($conn);
    }

    public function searchForWorkSlots($role, $date) {
        return $this->workSlotManager->searchWorkSlots($role, $date);
    }
	

    // Other controller methods for creating, updating, and deleting work slots...
}
