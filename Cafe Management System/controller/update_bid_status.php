<?php
require_once("../classes/WorkSlotManager.php"); // Include your entity class

class update_bid_status {
    private $workSlotManager;

    public function __construct($conn) {
        $this->workSlotManager = new WorkSlotManager($conn);
    }
	public function updateForBidStatus($bidId, $bidStatus) {
		// Get bid information
		$bid = $this->workSlotManager->getBidById($bidId);

		if ($bid) {
			// Check if the bid status is 'approved'
			if ($bidStatus === 'approved') {
				// Append staff ID to the assigned_staff_ids column in work_slots table
				$workSlotId = $bid['work_slot_id'];
				$staffId = $bid['staff_id'];

				// Call addToAssignedStaffIds and check the status
				$addToAssignedStatus = $this->workSlotManager->addToAssignedStaffIds($workSlotId, $staffId);

				// If addToAssignedStaffIds returns a status indicating failure, set bid status to 'rejected'
				if (strpos($addToAssignedStatus, "You have reached the maximum allowed work slots") !== false) {
					// Reject the bid and perform additional actions
					$this->workSlotManager->updateBidStatus($bidId, 'rejected');
					return "You have reached the maximum allowed work slots for this week. Bid status updated to 'rejected'.";
				}
			}

			// Update bid status
			$this->workSlotManager->updateBidStatus($bidId, $bidStatus);
		}
	}



    public function placeBid($staffId, $workSlotId) {
        // Implement the logic to place a bid.
        // You can use $this->workSlotManager to call methods from the entity class.
        // Add your logic here
    }

}
?>