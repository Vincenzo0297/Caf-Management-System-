<?php 
include('../controller/update_user_profile_process.php'); 
// Initialize variables to avoid undefined variable warnings
$userId = null;
$can_manage_bids = 0; // Initialize to 0
$can_manage_work_slots = 0; // Initialize to 0
$can_bid_for_work_slots = 0; // Initialize to 0

if ($_SESSION['user_type'] !== 'system admin') {
    header('Location: ../error_page.php');
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user data based on the ID
    $sql = "SELECT * FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);

        // Update the variables based on user data
        $can_manage_bids = $userData['can_manage_bids'];
        $can_manage_work_slots = $userData['can_manage_work_slots'];
        $can_bid_for_work_slots = $userData['can_bid_for_work_slots'];
    } else {
        header("Location: ../pages/manage_user_profiles.php");
        exit;
    }
} else {
    header("Location: ../pages/manage_user_profiles.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProfile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $can_view_dashboard = isset($_POST['can_view_dashboard']) ? 1 : 0;
    $can_manage_bids = isset($_POST['can_manage_bids']) ? 1 : 0;
    $can_manage_work_slots = isset($_POST['can_manage_work_slots']) ? 1 : 0;
    $can_bid_for_work_slots = isset($_POST['can_bid_for_work_slots']) ? 1 : 0;

    $sql = "UPDATE users SET username='$username', password='$password', 
            can_view_dashboard=$can_view_dashboard, 
            can_manage_bids=$can_manage_bids, 
            can_manage_work_slots=$can_manage_work_slots, 
            can_bid_for_work_slots=$can_bid_for_work_slots 
            WHERE id=$userId";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../pages/manage_user_profiles.php");
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
  }

    // Check if the form was submitted and the "updateProfile" button was clicked
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProfile'])) {
	    // Create an instance of the controller class
		$userProfileController = new update_user_profile_process($conn);

		// Call the updateUserProfile method
		$userProfileController->updateForUserProfile($userId, $_POST);
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Profile</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>Update User Profile</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_admin.php">Dashboard</a></li>
            <li><a href="../pages/manage_user_profiles.php">Manage User Profiles</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <div class="center">
            <h2>Update User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $userId; ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>" required><br>

                <label>Permissions:</label><br>
                <input type="checkbox" name="can_view_dashboard" <?php echo ($userData['can_view_dashboard'] ? 'checked' : ''); ?>> Can View Dashboard<br>
                <input type="checkbox" name="can_manage_bids" <?php echo ($userData['can_manage_bids'] ? 'checked' : ''); ?>> Can Manage Bids<br>
                <input type="checkbox" name="can_manage_work_slots" <?php echo ($userData['can_manage_work_slots'] ? 'checked' : ''); ?>> Can Manage Work Slots<br>
                <input type="checkbox" name="can_bid_for_work_slots" <?php echo ($userData['can_bid_for_work_slots'] ? 'checked' : ''); ?>> Can Bid for Work Slots<br>

                <input type="submit" name="updateProfile" value="Update User">
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?>CatFe 2023</p>
    </footer>
</body>

</html>
