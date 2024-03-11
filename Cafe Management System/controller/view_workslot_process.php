<?php
require_once('../classes/WorkSlotManager.php');

class view_workslot_process
{
    private $workSlotManager;

    public function __construct($conn)
    {
        $this->workSlotManager = new WorkSlotManager($conn);
    }

    public function getWorkSlots()
    {
        return $this->workSlotManager->getAllWorkSlots();
    }
}
?>
