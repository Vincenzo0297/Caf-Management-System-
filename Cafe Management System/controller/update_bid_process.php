<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class update_bid_process
{
    private $workSlotManager;

    public function __construct(WorkSlotManager $workSlotManager)
    {
        $this->workSlotManager = $workSlotManager;
    }

    public function updateBid($bidId, $newDate, $selectedSlotId)
    {
        // Retrieve existing bid details
        $bid = $this->workSlotManager->getBidById($bidId);

        if (!$bid) {
            $_SESSION['message'] = 'Bid not found.';
            $_SESSION['message_type'] = 'error';
            return;
        }

        // Call the updateWorkSlotDate method in WorkSlotManager
        $successMessage = $this->workSlotManager->updateWorkSlotDate($bid, $newDate, $selectedSlotId);

        if ($successMessage) {
            // If the update is successful, set success message
            $_SESSION['message'] = 'Bid updated successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            // If the update fails, set an error message
            $_SESSION['message'] = 'Error updating bid.';
            $_SESSION['message_type'] = 'error';
        }
                // Redirect to the same page
                header("Location: ../pages/view_bids.php");
                exit();
    }
}
?>