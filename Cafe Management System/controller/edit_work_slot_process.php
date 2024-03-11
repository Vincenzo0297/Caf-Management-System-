<?php

require_once('../classes/WorkSlot.php');
require_once('../classes/WorkSlotManager.php');

class edit_work_slot_process {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function updateWorkSlot($workSlotId, $role, $workDate, $workSlotLimit) {
        $workSlotManager = new WorkSlotManager($this->conn);
        $workSlotManager->updateWorkSlot($workSlotId, $role, $workDate, $workSlotLimit);
		
    }

    public function getWorkSlotById($workSlotId, $cafeOwnerId) {
        $workSlotManager = new WorkSlotManager($this->conn);
        $workSlot = $workSlotManager->getWorkSlotById($workSlotId);

        if ($workSlot && $workSlot->getCafeOwnerId() != $cafeOwnerId) {
            return null; // Unauthorized access
        }

        return $workSlot;
    }
}
?>