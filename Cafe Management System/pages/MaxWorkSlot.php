<?php
include('../config/session_start.php');
include('../config/db_config.php');
require_once('../controller/MaxWorkSlot_process.php');

$workSlotController = new MaxWorkSlot_process($conn);

// Handle setting the maximum work slot
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['set_max_work_slot'])) {
	 echo "Form submitted!"; 
    $staffId = (int)$_POST['staff_id'];
    $maxWorkSlot = (int)$_POST['max_work_slot'];
	$saveWorkSlot = $maxWorkSlot;
	
	 echo "Staff ID: $staffId, Max Work Slot: $maxWorkSlot"; // Add this line for debugging

    $workSlotController->setMaxWorkSlot($staffId, $maxWorkSlot,$saveWorkSlot);
}

$staffList = $workSlotController->getAvailableStaff();

// Include your HTML view or render it here
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set For Max Work Slot - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>Set For Max Work Slot</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_manager.php">Dashboard</a></li>
            <li><a href="../pages/assign_roles.php">Assign Roles</a></li>
			<li><a href="../pages/MaxWorkSlot.php">Set Max Work Slot</a></li>
            <li><a href="../pages/view_available_staff.php">View Available Staff</a></li>
            <li><a href="../pages/view_work_slots.php">View Work Slots</a></li>
            <li><a href="../pages/manage_bids.php">Manage Bids</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <!-- Include your main content here -->
        <h2>Set Max Work Slot</h2>
        <form method="POST">
            <label for="staff_id">Select Staff:</label>
            <select id="staff_id" name="staff_id">
                <?php foreach ($staffList as $staff) : ?>
                    <option value="<?php echo $staff['id']; ?>"><?php echo $staff['username']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="max_work_slot">Set Max Work Slot:</label>
            <input type="number" id="max_work_slot" name="max_work_slot" min="0" required>
            <input type="submit" name="set_max_work_slot" value="Set Max Work Slot">
        </form>

        
    </main>

    <footer>
        <!-- Include your footer content here -->
    </footer>
</body>

</html>
