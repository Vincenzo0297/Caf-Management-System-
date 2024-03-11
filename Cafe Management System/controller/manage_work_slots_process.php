<?php
include('../config/session_start.php');
include('../config/db_config.php');
require_once('../classes/WorkSlot.php');
require_once('../classes/WorkSlotManager.php');
require_once('../pages/manage_work_slots.php');

class manage_work_slots_process
{
    private $workSlotManager;

    public function __construct($conn)
    {
        $this->workSlotManager = new WorkSlotManager($conn);
    }

    public function createForWorkSlot($cafeOwnerId, $role, $workDate, $workSlotLimit)
    {
        return $this->workSlotManager->createWorkSlot($cafeOwnerId, $role, $workDate, $workSlotLimit);
    }
}
?>