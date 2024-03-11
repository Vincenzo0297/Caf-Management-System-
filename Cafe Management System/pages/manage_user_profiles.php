<?php 
include('../controller/manage_user_profiles_process.php'); 
include('../controller/viewprofile_process.php');
include('../classes/User.php');
//include('../classes/UserProfile.php');
include('../controller/search_process.php');
include('../config/db_config.php');

// Check the user's role
if ($_SESSION['user_type'] !== 'system admin') {
    header('Location: ../error_page.php');
    exit;
}
$userProfile = new UserProfile($conn);
// Create an instance of the controller
$searchController = new search_process($conn);
$viewController = new ViewUserProfile($userProfile);

// Retrieve the list of users from the controller
$users = $viewController->getUserProfiles();


$userController = new manage_user_profiles_process($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createProfile'])) {
    $username = $_POST['username'];
    $canViewDashboard = isset($_POST['can_view_dashboard']) ? 1 : 0;
    $canManageBids = isset($_POST['can_manage_bids']) ? 1 : 0;
    $canManageWorkSlots = isset($_POST['can_manage_work_slots']) ? 1 : 0;
    $canBidForWorkSlots = isset($_POST['can_bid_for_work_slots']) ? 1 : 0;

    // Call the createProfile method in the controller
    $result = $userController->createForProfile($username, $canViewDashboard, $canManageBids, $canManageWorkSlots, $canBidForWorkSlots);

    if ($result === true) {
        echo "New profile created successfully";
        header("Location: ../pages/manage_user_profiles.php");
        exit;
    } else {
        echo "Error: Profile creation failed";
    }
}

// Search for user profiles if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    // Call the searchProfile method in the controller
    $searchResults = $searchController->searchProfile($searchTerm);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
</head>

<body>
    <header>
        <h1>User Profile Management</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_admin.php">Dashboard</a></li>
            <li><a href="../pages/manage_user_profiles.php">Manage Profiles</a></li>
            <li><a href="../pages/manage_user.php">Manage User</a></li>
            <li><a href="../pages/adduser.php">Add User</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>
    <center>
    <main class="main-container">
<!-- User Profiles Table -->
<div class="work-slots">
            <h2>User Profiles</h2>
            <form method="POST">
                <input type="text" id="searchAvailable" name="search" placeholder="Type to search...">
				<input type="submit" value="Search"> <!-- Add a submit button -->
            </form>
            <table>
                <!-- ... (table header) ... -->
                <?php
				if (isset($searchResults)) {
                if ($searchResults->num_rows > 0) {
				while ($row = $searchResults->fetch_assoc()) {
					
					echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>";
                    // Display permissions based on the new columns
                    echo "<input type='checkbox' disabled " . ($row['can_view_dashboard'] ? 'checked' : '') . "> Can View Dashboard<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_manage_bids'] ? 'checked' : '') . "> Can Manage Bids<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_manage_work_slots'] ? 'checked' : '') . "> Can Manage Work Slots<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_bid_for_work_slots'] ? 'checked' : '') . "> Can Bid for Work Slots<br>";
                    echo "</td>";
                    echo "<td><a href='../pages/update_user_profile.php?id={$row['id']}'>Update</a></td>"; // Add edit and delete actions
                    echo "</tr>";
					}
				} else {
                echo '<tr><td colspan="6">No matching records found.</td></tr>';
				}
				} else {
					
				if ($users) {
                while ($row = mysqli_fetch_assoc($users)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>";
                    // Display permissions based on the new columns
                    echo "<input type='checkbox' disabled " . ($row['can_view_dashboard'] ? 'checked' : '') . "> Can View Dashboard<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_manage_bids'] ? 'checked' : '') . "> Can Manage Bids<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_manage_work_slots'] ? 'checked' : '') . "> Can Manage Work Slots<br>";
                    echo "<input type='checkbox' disabled " . ($row['can_bid_for_work_slots'] ? 'checked' : '') . "> Can Bid for Work Slots<br>";
                    echo "</td>";
                    echo "<td><a href='../pages/update_user_profile.php?id={$row['id']}'>Update</a></td>"; // Add edit and delete actions
                    echo "</tr>";
                    }
				  }
				}
                ?>
            </table>
        </div>

        <!-- Create Profile Form -->
       <div class="create-work-slot">
            <h2>Create User Profile</h2>
            <form method="post" action="../pages/manage_user_profiles.php" class="create-work-slot-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
                <label>Permission Rights:</label><br>
                <input type="checkbox" name="can_view_dashboard"> Can View Dashboard<br>
                <input type="checkbox" name="can_manage_bids"> Can Manage Bids<br>
                <input type="checkbox" name="can_manage_work_slots"> Can Manage Work Slots<br>
                <input type="checkbox" name="can_bid_for_work_slots"> Can Bid for Work Slots<br>
                <!-- can add more checkbox options here if needed -->
                <input type="submit" name="createProfile" value="Create Profile">
            </form>
        </div>

        
    </main>
            </center>
    <footer>
        <p>&copy; <?php echo date("Y"); ?>CatFe 2023</p>
    </footer>
</body>

</html>
