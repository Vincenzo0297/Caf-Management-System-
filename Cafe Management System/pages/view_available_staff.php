<?php
include('../config/session_start.php');
include('../config/db_config.php');
require_once('../controller/view_available_staff_process.php');

$bidsController = new view_available_staff_process($conn);
$result = $bidsController->getAllAvailableStaff();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Available Staff - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
</head>

<body>
    <header>
        <h1>Available Staff</h1>
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
        <form method="POST">
        <input type="text" id="searchAvailable" name="search" placeholder="Type to search...">
        </form>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Café Role</th>
            </tr>
            <?php
            // Check if the query was successful
            if ($result) {
                // Fetch and display the cafe staff with assigned roles
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['cafe_role'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Error: " . mysqli_error($conn) . "</td></tr>";
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
