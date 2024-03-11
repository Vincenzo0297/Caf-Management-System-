<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/db_config.php');
require_once('../classes/WorkSlotManager.php');

class cancel_bid {
    private $conn;
    private $workSlotManager;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->workSlotManager = new WorkSlotManager($conn);
    }

    public function cancelBid($bidId) {
        $bidCanceller = new WorkSlotManager($this->conn);
        
        // Call the cancelBid method to cancel the bid
        $result = $bidCanceller->cancelBid($bidId);
    
        if ($result) {
            // Set the session variables for success message
            $_SESSION['message'] = "Bid canceled successfully.";
            $_SESSION['message_type'] = 'success';
        } else {
            // Set the session variables for error message
            $_SESSION['message'] = "Error canceling bid.";
            $_SESSION['message_type'] = 'error';
        }
    
        // Redirect to the same page
        header("Location: ../pages/view_bids.php");
        exit();
    }
    

}
?>
<!-- Error message color -->
<style>
    .success-message {
        color: green; /* Change this to your desired color for success messages */
    }

    .error-message {
        color: red; /* Change this to your desired color for error messages */
    }
</style>