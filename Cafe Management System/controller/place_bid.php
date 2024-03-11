<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/db_config.php');
require_once('../classes/WorkSlotManager.php');


class place_bid {

    private $workSlotManager;
    
    public function __construct($conn) {
        $this->workSlotManager = new WorkSlotManager($conn);
    }

    public function placeABid($workSlotId, $staffId) {
        // Get work slot information
        $workSlot = $this->workSlotManager->getWorkSlotById($workSlotId);
    
        // Check if the work slot is null
        if ($workSlot === null) {
            $_SESSION['message'] = "Work slot not found.";
            $_SESSION['message_type'] = 'error';
            return;
        }
    
        // Check if the staff member already placed a bid for this work slot
        if ($this->workSlotManager->hasBid($staffId, $workSlotId)) {
            $_SESSION['message'] = "You have already placed a bid for this work slot.";
            $_SESSION['message_type'] = 'error';
            return;
        }
    
        // Place the bid
        if ($this->workSlotManager->placeBid($staffId, $workSlotId)) {
            $role = $workSlot->getRole();
            $date = $workSlot->getWorkDate();
            $_SESSION['message'] = "Bid placed successfully for the role of $role on $date.";
            $_SESSION['message_type'] = 'success';
            return $_SESSION['message'];
        } else {
            $_SESSION['message'] = "Error placing bid.";
            $_SESSION['message_type'] = 'error';
            return $_SESSION['message'];
        }
    }
    
}

?>
<!-- Error message color -->
<style>
    .success-message {
        color: green; 
    }

    .error-message {
        color: red; 
    }
</style>
