<?php
include "../config/session_start.php";
include "../config/db_config.php";
include "../classes/BackButton.php";
include "../controller/cancel_bid.php";
include "../controller/update_bid_process.php";
require_once "../classes/WorkSlot.php";
require_once "../classes/WorkSlotManager.php";
require_once "../controller/viewBid_process.php";

// Variables
$userType = $_SESSION["user_type"];
$staffId = $_SESSION["user_id"];

// Create Instance of Class
$workSlotManager = new WorkSlotManager($conn);
$bidsController = new viewBid_process($conn); // Create an instance of the Bid class
$bids = $bidsController->getAllBids($staffId); // Fetch bids using the getBids() method
$cancelBidController = new cancel_bid($conn);
$updateBidController = new update_bid_process($workSlotManager);
$backButtonHtml = BackButton::generateBackButton($userType);

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["bidId"]) &&
    isset($_POST["action"])
) {
    $bidId = $_POST["bidId"];
    $action = $_POST["action"];

    if ($action == "delete") {
        // Check if $bidId is set before calling cancelBid
        if (isset($bidId)) {
            // cancel the bid using cancelBid
            $cancelBidController->cancelBid($bidId);

            // Remove the canceled bid from the local array
            $bids = array_filter($bids, function ($bid) use ($bidId) {
                return $bid["bid_id"] != $bidId;
            });
        } else {
            // Handle the case where $bidId is not set
            echo "Error: Bid ID not set.";
        }
    } else {
        // Check if $bidId and $newDate are set before calling updateBid
        if (isset($bidId) && isset($_POST["newDate"])) {
            $newDate = $_POST["newDate"];
            $selectedSlotId = $_POST["selectedSlotId"];
            // Update bid
            $updateBidController->updateBid($bidId, $newDate, $selectedSlotId);
        } else {
            // Handle the case where $bidId or $newDate is not set
            echo "Error: Bid ID or new date not set.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bids - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>Your Bids</h1>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <!-- Back button ensures that the dashboard is displayed per user_type -->
        <ul>
        <?php echo $backButtonHtml; ?>
         </ul>
        </nav>

    <main>
        <form method="POST">
            <input type="text" id="searchAvailable" name="search" placeholder="Type to search...">
        </form>
        <div align = 'center'>
            <?php // Check for messages and display them
            if (isset($_SESSION["message"])) {
                        $message = $_SESSION["message"];
                        $messageType = $_SESSION["message_type"];
            
                        // Display the message based on the type (success or error)
                        $messageClass =
                            $messageType === "success"
                                ? "success-message"
                                : "error-message";
                        echo '<div class="' .
                            $messageClass .
                            '">' .
                            htmlspecialchars($message) .
                            "</div>";
            
                        // Unset the session variables after displaying the message
                        unset($_SESSION["message"]);
                        unset($_SESSION["message_type"]);
                    } ?>

            </div>
        <?php if ($bids): ?>
            <table>
                <tr>
                    <th>Bid ID</th>
                    <th>Work Slot ID</th>
                    <th>Bid Status</th>
                    <th>Date</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($bids as $bid): ?>
    <tr>
        <td><?php echo htmlspecialchars($bid["bid_id"]); ?></td>
        <td><?php echo htmlspecialchars($bid["work_slot_id"]); ?></td>
        <td><?php echo htmlspecialchars($bid["bid_status"]); ?></td>
        <td><?php echo htmlspecialchars(
            $workSlotManager->getDateByWorkSlotId($bid["work_slot_id"])
        ); ?></td>
        <td><?php echo htmlspecialchars(
            $workSlotManager->getRoleByWorkSlotId($bid["work_slot_id"])
        ); ?></td>
        <td>
        <?php if ($bid["bid_status"] === "pending"): ?>
                <!-- Delete Form -->
                <form method="POST" action="../pages/view_bids.php">
                    <input type="hidden" name="bidId" value="<?php echo $bid[
                        "bid_id"
                    ]; ?>">
                    <button type="submit" name="action" value="delete">Delete</button>
                </form>
                <!-- Update Form -->
                <form method="POST" action="../pages/view_bids.php">
                    <input type="hidden" name="bidId" value="<?php echo $bid["bid_id"]; ?>">
                    <input type="hidden" name="selectedSlotId" value="<?php echo $selectedSlotId; ?>">
                    <label for="newDate">Select new date:</label>
                    <select name="newDate" id="newDate">
                        <?php
                        $role = $workSlotManager->getRoleByWorkSlotId($bid["work_slot_id"]);
                        $availableSlots = $workSlotManager->getAvailableSlotsByRole($role);
                
                        foreach ($availableSlots as $slot) {
                            echo '<option value="' .
                                $slot["id"] .
                                '">' .
                                $slot["work_date"] .
                                "</option>";
                            }
                                ?>
                    </select>
                    <button type="submit" name="action" value="updateDate">Update</button>
                </form>
                <?php else: ?>
                    <button disabled>Delete</button>
                    <button disabled>Update</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No bids available.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>
</html>


