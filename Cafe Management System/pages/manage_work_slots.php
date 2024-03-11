<?php
// Include necessary files and create a controller instance
include('../controller/manage_work_slots_process.php');
include('../controller/search_WorkSlots_process.php');
require_once('../controller/deleteWorkSlot_process.php');
$cafeOwnerId = $_SESSION['user_id'];

$searchController = new searchWorkSlots_process($conn);


// Get search parameters from the form
$role = isset($_POST['role']) ? $_POST['role'] : '';
$date = isset($_POST['workDate']) ? $_POST['workDate'] : '';

// Call the searchWorkSlots method to get the filtered work slots
$workSlots = $searchController->searchForWorkSlots($role, $date); 
//$workSlots = $searchController->getWorkSlots();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createWorkSlot'])) {
    $role = $_POST['role']; 
    $workDate = $_POST['workDate'];
    $workSlotLimit = $_POST['workSlotLimit'];

    // Create a WorkSlotCreator instance and use it to create the work slot
    $workSlotCreator = new manage_work_slots_process($conn);
    $created = $workSlotCreator->createForWorkSlot($cafeOwnerId, $role, $workDate, $workSlotLimit);

    if ($created) {
        header("Location: ../pages/manage_work_slots.php");
        exit();
    } else {
        // Handle the error if the creation fails
        echo "Failed to create the work slot.";
    }
	$workSlots = $workSlotCreator->getWorkSlotsByOwner($cafeOwnerId);
}

$workSlotManager = new WorkSlotManager($conn); 
$workSlotController = new deleteWorkSlot_process($workSlotManager);
if (isset($_POST['deleteWorkSlot'])) {
    $workSlotId = $_POST['deleteWorkSlotId'];

    // Call the deleteWorkSlot method in the controller
    if ($workSlotController->deleteForWorkSlot($workSlotId)) {
        // Work slot deleted successfully, you can redirect or display a message
        header("Location: ../pages/manage_work_slots.php");
    } else {
        // Handle the error if the deletion fails
        echo "Failed to delete the work slot.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Work Slots - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
</head>

<body>
    <header>
        <h1>Manage Work Slots</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_owner.php">Dashboard</a></li>
            <li><a href="view_work_slots.php">View Work Slots</a></li>
            <li><a href="../pages/manage_work_slots.php">Manage Work Slots</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    <center>
        <main class="main-container">
            <div class="work-slots">
                <h2>Your Work Slots</h2>
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

		<?php if (count($workSlots) > 0) : ?>
			<table>
				<tr>
					<th>ID</th>
					<th>Role</th>
					<th>Work Date</th>
					<th>Work Slot Limit</th>
					<th>Actions</th>
				</tr>
        <?php foreach ($workSlots as $workSlot) : ?>
            <tr>
                <td><?php echo $workSlot->getId(); ?></td>
                <td><?php echo $workSlot->getRole(); ?></td>
                <td><?php echo $workSlot->getWorkDate(); ?></td>
                <td><?php echo $workSlot->getWorkSlotLimit(); ?></td>
                <td>
                    <!-- Edit button -->
                    <a href="../pages/edit_work_slot.php?id=<?php echo $workSlot->getId(); ?>"
                       class="action-button">Edit</a>
                    <!-- Delete button -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          style="display:inline;">
                        <input type="hidden" name="deleteWorkSlotId"
                               value="<?php echo $workSlot->getId(); ?>">
                        <input type="submit" name="deleteWorkSlot" value="Delete"
                               class="action-button" onclick="return confirm('Are you sure you want to delete this work slot?');">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <p>No work slots available.</p>
<?php endif; ?>
            </div>

            <div class="create-work-slot">
                <h2>Create Work Slot</h2>
                <!-- Form to create a new work slot -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      class="create-work-slot-form">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="chef">Chef</option>
                        <option value="cashier">Cashier</option>
                        <option value="waiter">Waiter</option>
                    </select><br>
                    <label for="workDate">Work Date:</label>
                    <input type="date" id="workDate" name="workDate" required><br>
                    <label for="workSlotLimit">Work Slot Limit:</label>
                    <input type="number" id="workSlotLimit" name="workSlotLimit" required><br>
                    <input type="submit" name="createWorkSlot" value="Create Work Slot"
                           class="action-button">
                </form>
            </div>
        </main>
    </center>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
