<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/BackButton.php');
include('../controller/place_bid.php');
require_once('../classes/WorkSlot.php');
require_once('../classes/WorkSlotManager.php');
require_once('../controller/view_workslot_process.php');
require_once('../controller/delete_staff_workslot_process.php');
include('../controller/search_WorkSlots_process.php');

// Variables
$userType = $_SESSION['user_type'];
$userId = $_SESSION['user_id'];

// Create Instance of Class
$workSlotManager = new WorkSlotManager($conn);
$workSlotController = new view_workslot_process($conn);
$placeBidController = new place_bid($conn);
$deleteStaffController = new delete_staff_workslot_process($conn);
$backButtonHtml = BackButton::generateBackButton($userType);

// Check if the form is submitted and the bid button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bidButton'])) {
    $workSlotId = $_POST['workSlotId'];
    $staffId = $_SESSION['user_id'];

    // Check if $workSlotId and $staffId are set before calling placeABid
    if (isset($workSlotId) && isset($staffId)) {
        // Place the bid using place_bid
        $placeBidController->placeABid($workSlotId, $staffId);
    } else {
        // Handle the case where $workSlotId or $staffId is not set
        echo "Error: Work slot ID or staff ID not set.";
    }
}

// delete staff from workslot
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_work_slot_id'])) {
    $workSlotId = $_POST['delete_work_slot_id'];
    $staffId = $_POST['selectedUserId'];

    // Debugging: Call the method to remove the work slot
    $result = $deleteStaffController->removeStaffWorkSlot($workSlotId,$staffId);

    // Debugging: Print out the result
    echo "Result from removeStaffFromWorkSlot: " . $result;

    // Set a message based on the result
    if ($result === "success") {
        $_SESSION['message'] = "staff deleted from Work slot successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting staff from work slot: " . $result;
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the same page
    header("Location: ../pages/view_work_slots.php");
    exit();
}

// Get work slots based on user type and ID
if ($userType === 'cafe staff') {
    $workSlots = $workSlotManager->getWorkSlotsForCafeStaff($userId);
} else {
    $workSlots = $workSlotManager->getAllWorkSlots();
}

// Initialize search controller
$searchController = new searchWorkSlots_process($conn);

// Get search parameters from the form
$role = isset($_POST['role']) ? $_POST['role'] : '';
$date = isset($_POST['workDate']) ? $_POST['workDate'] : '';

// Call the searchWorkSlots method to get the filtered work slots
$workSlots = $searchController->searchForWorkSlots($role, $date); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Work Slots - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
    <script src="../placebid.js"></script>
    
    <style>
        /* Add the following styles for centering the form */
        main {
            text-align: center;
        }

        form {
            display: inline-block;
            text-align: left;
        }
    </style>
</head>

<body>
    <header>
        <h1>Work Slots</h1>
    </header>
    
    <!-- Navigation Bar -->
    <nav>
                <ul>
                <?php echo $backButtonHtml; ?>
                    </ul>
                </nav>
                
    <!-- Display Page  -->
    <main>
        <h2>Available Work Slots</h2>
        <div>
            <form method="POST">
                <select id="role" name="role">
                    <option value="" selected>All Roles</option>
                    <option value="chef" <?php if ($role === 'chef') echo 'selected'; ?>>Chef</option>
                    <option value="cashier" <?php if ($role === 'cashier') echo 'selected'; ?>>Cashier</option>
                    <option value="waiter" <?php if ($role === 'waiter') echo 'selected'; ?>>Waiter</option>
                </select>
                <input type="date" id="workDate" name="workDate" value="<?php echo $date; ?>">
                <input type="submit" value="Search" class="action-button">
            </form>
            
            <?php // Check for messages and display them
            if (isset($_SESSION['message'])) {
                $message = $_SESSION['message'];
                $messageType = $_SESSION['message_type'];
            
                // Display the message based on the type (success or error)
                $messageClass = ($messageType === 'success') ? 'success-message' : 'error-message';
                echo '<div class="' . $messageClass . '">' . htmlspecialchars($message) . '</div>';
            
                // Clear the session variables to avoid displaying the message again on subsequent requests
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
        </div>

        <?php if (count($workSlots) > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Created by</th>
                    <th>Role</th>
                    <th>Work Date</th>
                    <th>Work Slot Limit</th>
                    <th>Assigned Staff</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($workSlots as $workSlot) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($workSlot->getId()); ?></td>
                        <td><?php echo htmlspecialchars(WorkSlot::getOwnerUsernameById($workSlot->getCafeOwnerId(), $conn)); ?></td>
                        <td><?php echo htmlspecialchars($workSlot->getRole()); ?></td>
                        <td><?php echo htmlspecialchars($workSlot->getWorkDate()); ?></td>
                        <td><?php echo htmlspecialchars($workSlot->getWorkSlotLimit()); ?></td>
                        <td>
                            <div style="max-height: 150px; overflow-y: auto;">
                                <?php $assignedStaffIds = $workSlotManager->getAssignedStaffIds($workSlot->getId()); ?>
                                <?php if (!empty($assignedStaffIds)) : ?>
                                    <?php foreach ($assignedStaffIds as $staffId) : ?>
                                        <?php $staffUsername = $workSlotManager->getStaffUsernameById($staffId); ?>
                                        <?php echo htmlspecialchars($staffUsername); ?><br>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    No assigned staff
                                <?php endif; ?>
                            </div>
                        </td>
                       <?php if ($userType === 'cafe manager') : ?>
							 <td>
								<form method="POST" action="../pages/view_work_slots.php">
									<select name="selectedUserId">
										<?php foreach ($assignedStaffIds as $staffId) : ?>
											<option value="<?php echo $staffId; ?>">
												<?php echo htmlspecialchars($workSlotManager->getStaffUsernameById($staffId)); ?>
											</option>
										<?php endforeach; ?>
									</select><br>
									<input type="hidden" name="delete_work_slot_id" value="<?php echo $workSlot->getId(); ?>">
									<input type="submit" name="deleteButton" value="Delete" onclick="return confirm('Are you sure you want to delete this work slot for the selected user?');">
								</form>
							</td>
					   <?php elseif ($userType === 'cafe staff') : ?>
							<td>
								<?php
								// Check if the current user has already placed a bid for this work slot
								$hasBid = $workSlotManager->hasBid($userId, $workSlot->getId());
								
								if (!$hasBid) {
									// If the user hasn't placed a bid, show the bid button
									?>
									<form method="POST">
										<input type="hidden" name="workSlotId" value="<?php echo $workSlot->getId(); ?>">
										<input type="hidden" name="workSlotDate" value="<?php echo $workSlot->getWorkDate(); ?>">
										<input type="submit" name="bidButton" value="Bid">
									</form>
								<?php } else {
									// If the user has already placed a bid, show a message or leave it empty
									echo "Already bid"; 
								}
								?>
							</td>
						<?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No work slots available.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
