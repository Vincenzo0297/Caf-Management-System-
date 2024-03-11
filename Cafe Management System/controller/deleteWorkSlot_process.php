<?php
require_once('../classes/WorkSlotManager.php');

class deleteWorkSlot_process {
    private $workSlotManager;

    public function __construct($workSlotManager) {
        $this->workSlotManager = $workSlotManager;
    }

    public function deleteForWorkSlot($workSlotId) {
        return $this->workSlotManager->deleteWorkSlot($workSlotId);
    }
}
?>