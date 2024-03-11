<?php
include('../config/session_start.php');
include('../config/db_config.php');
require_once("../controller/update_bid_status.php"); 
require_once('../controller/viewBid_process.php');

$bidsController = new viewBid_process($conn);
$result = $bidsController->getBidsForToManage();



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['bid_id']) && isset($_POST['bid_status'])) {
    $bidId = (int)$_POST['bid_id']; // Cast bid ID to integer
    $bidStatus = $_POST['bid_status'];

    // Create an instance of BidService
    $bidService = new update_bid_status($conn);

    // Call the method to update the bid status
    $bidService->updateForBidStatus($bidId, $bidStatus);

    // Redirect to the manage bids page after processing the bid status
    header("Location: ../pages/manage_bids.php");
    exit();
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
        <h1>Manage Bids</h1>
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
        <table>
            <tr>
                <th>Bid ID</th>
                <th>Staff Username</th>
                <th>Staff Role</th>
                <th>Work Slot ID</th>
                <th>Work Slot Role</th> <!-- Added column for Work Slot Role -->
                <th>Work Date</th>
                <th>Bid Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if the query was successful
            if ($result) {
                // Fetch and display the bids made by staff members that are not approved or rejected
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['bid_id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['cafe_role'] . "</td>"; // Display cafe role
                    echo "<td>" . $row['work_slot_id'] . "</td>";
                    echo "<td>" . $row['work_slot_role'] . "</td>"; // Display work slot role
                    echo "<td>" . $row['work_date'] . "</td>";
                    echo "<td>" . $row['bid_status'] . "</td>";
                    echo "<td>";
                    echo "<form action='../pages/manage_bids.php' method='POST'>";
                    echo "<input type='hidden' name='bid_id' value='" . $row['bid_id'] . "'>";
                    echo "<select name='bid_status'>";
                    echo "<option value='approved'>Approve</option>";
                    echo "<option value='rejected'>Reject</option>";
                    echo "</select>";
                    echo "<input type='submit' value='Update'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Error: " . mysqli_error($conn) . "</td></tr>";
            }

            // Close the connection
            mysqli_close($conn);
            ?>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
