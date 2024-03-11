<?php 
include('../controller/edit_work_slot_process.php');
include('../config/session_start.php');
include('../config/db_config.php');
$cafeOwnerId = $_SESSION['user_id'];

// Instantiate the edit_work_slot_process class
$editWorkSlotProcess = new edit_work_slot_process($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateWorkSlot'])) {
    $workSlotId = $_POST['workSlotId'];
    $role = $_POST['role'];
    $workDate = $_POST['workDate'];
    $workSlotLimit = $_POST['workSlotLimit'];

    // Call the updateWorkSlot method in the class
    $editWorkSlotProcess->updateWorkSlot($workSlotId, $role, $workDate, $workSlotLimit);
    header("Location: manage_work_slots.php");
    exit();
}

if (isset($_GET['id'])) {
    $workSlotId = $_GET['id'];

    // Call the getWorkSlotById method in the class to check authorization
    $workSlot = $editWorkSlotProcess->getWorkSlotById($workSlotId, $cafeOwnerId);

    if (!$workSlot) {
        echo "Unauthorized access!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Work Slot - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body id="editWorkSlotForm">
    <header>
        <h1>Edit Work Slot</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_owner.php">Back</a></li>
            <li><a href="view_work_slots.php">View Work Slots</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main id="editWorkSlotForm">
        <h2>Edit Work Slot</h2>
        <!-- Form to edit the work slot -->
        <form id="editWorkSlotForm" method="post" action="../pages/edit_work_slot.php">
            <input type="hidden" name="workSlotId" value="<?php echo $workSlot->getId(); ?>">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="chef" <?php echo ($workSlot->getRole() == 'chef') ? 'selected' : ''; ?>>Chef</option>
                <option value="cashier" <?php echo ($workSlot->getRole() == 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                <option value="waiter" <?php echo ($workSlot->getRole() == 'waiter') ? 'selected' : ''; ?>>Waiter</option>
            </select><br>
            <label for="workDate">Work Date:</label>
            <input type="date" id="workDate" name="workDate" value="<?php echo $workSlot->getWorkDate(); ?>" required><br>
            <label for="workSlotLimit">Work Slot Limit:</label>
            <input type="number" id="workSlotLimit" name="workSlotLimit" value="<?php echo $workSlot->getWorkSlotLimit(); ?>" required><br>
            <input type="submit" name="updateWorkSlot" value="Update Work Slot">
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
