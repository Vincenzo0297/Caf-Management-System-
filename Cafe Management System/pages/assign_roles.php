<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../controller/assign_role_process.php');
// Check if the user is logged in and is a manager, if not, redirect to login or show an error message
// Add your logic here

// Query to get cafe staff without assigned roles
$sql = "SELECT users.id, users.username, users.email, users.phone FROM users LEFT JOIN user_profiles ON users.id = user_profiles.user_id WHERE users.Type = 'café staff' AND user_profiles.cafe_role IS NULL";

$result = mysqli_query($conn, $sql);


$userProfile = new UserProfile($conn);
$roleAssignmentController = new assign_role_process($userProfile);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffId = $_POST['staff_id'];
    $assignedRole = $_POST['cafe_role'];

    if ($roleAssignmentController->assignRole($staffId, $assignedRole)) {
        // Role assigned successfully, redirect to the assign roles page
        header("Location: ../pages/assign_roles.php");
        exit();
    } else {
        // Handle error or display an error message
        echo "Error assigning the role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Roles - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
</head>

<body>
    <header>
        <h1>Assign Roles</h1>
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
                <th>Assign Role</th>
            </tr>
            <?php
            // Check if the query was successful
            if ($result) {
                // Fetch and display the cafe staff without assigned roles
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>";
                    echo "<form action='../pages/assign_roles.php' method='POST'>";
                    echo "<input type='hidden' name='staff_id' value='" . $row['id'] . "'>";
                    echo "<select name='cafe_role'>";
                    echo "<option value='chef'>Chef</option>";
                    echo "<option value='cashier'>Cashier</option>";
                    echo "<option value='waiter'>Waiter</option>";
                    echo "</select>";
                    echo "<input type='submit' value='Assign'>";
                    echo "</form>";
                    echo "</td>";
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
