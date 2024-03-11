<?php
require_once('../classes/WorkSlotManager.php');

class delete_staff_workslot_process
{
    private $workSlotManager;

    public function __construct($conn)
    {
        $this->workSlotManager = new WorkSlotManager($conn);
    }

   public function removeStaffWorkSlot($workSlotId, $staffId)
{
    $result = $this->workSlotManager->removeStaffFromWorkSlot($workSlotId, $staffId);
    $success = ($result === "success");
    if ($success) {
        header("Location: ../pages/view_work_slots.php");
        exit();
    }
}

}
?>