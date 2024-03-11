<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/UserProfile.php');
//include('../classes/User.php'); // Include the User entity class
include('../controller/viewuser_process.php'); // Include the UserController class
include('../controller/search_process.php');// Include the search controller class
include('../controller/suspend_user_process.php');

// Create an instance of UserProfile class
//$userProfile = new UserProfile($conn);
// Fetch user data from the database including the user type (Type) excluding the logged-in user
//$users = $conn->query("SELECT id, username, email, phone, Type FROM users WHERE id != $loggedInUserId");
$user = new User($conn);
$userController = new ViewUser($user);
$searchController = new search_process($conn);

$loggedInUserId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        $searchResults = $searchController->searchUsers($searchTerm);
    }
}

$suspendUserController = new suspend_user_process($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Suspend'])) {
    $userId = $_POST['id'];
    $suspendUserController->suspendForUser($userId);
}

if (isset($_SESSION['delete_user_message'])) {
    echo '<div class="success-message">' . $_SESSION['delete_user_message'] . '</div>';
    unset($_SESSION['delete_user_message']); // Clear the message to prevent showing it on page refresh
}
$users = $userController->getUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Profiles - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../search.js"></script>
	<style>
	main {
    width: 1000px;
    }
	</style>
</head>

<body>
    <header>
        <h1>SIM CatFe</h1>
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

    <main>
        <center>
            <h2>Manage User</h2>
        </center>
        <!-- Search Form -->
        <form method="POST">
            <input type="text" id="searchAvailable" name="search" placeholder="Type to search...">
            <input type="submit" value="Search"> <!-- Add a submit button -->
        </form>

        <table id="user-profiles">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
				<th>Status</th>
				<th></th>
				<th></th>
				
            </tr>
         <?php
			if (isset($searchResults)) {
				if ($searchResults->num_rows > 0) {
					while ($row = $searchResults->fetch_assoc()) {
						// Display search results
						echo "<tr>";
						echo "<td>{$row['id']}</td>";
						echo "<td>{$row['username']}</td>";
						echo "<td>{$row['email']}</td>";
						echo "<td>{$row['phone']}</td>";
						echo "<td>{$row['Type']}</td>";
						echo "<td>
							<form method='POST' action='../pages/update_user.php'>
								<input type='hidden' name='id' value='{$row['id']}'>
								<input type='submit' value='Update'>
							</form>
						</td>";
						echo "<td>
							<form method='POST' action='../controller/delete_user_process.php'>
								<input type='hidden' name='id' value='{$row['id']}'>
								<input type='submit' value='Delete'>
							</form>
						</td>";
						echo "</tr>";
					}
				} else {
					echo '<tr><td colspan="6">No matching records found.</td></tr>';
				}
			} else {
				// Display the list of all users
				if ($users) {
					while ($row = $users->fetch_assoc()) {
						echo "<tr>";
						echo "<td>{$row['id']}</td>";
						echo "<td>{$row['username']}</td>";
						echo "<td>{$row['email']}</td>";
						echo "<td>{$row['phone']}</td>";
						echo "<td>{$row['Type']}</td>";
						echo "<td>{$row['is_suspended']}</td>"; // Display account status
						echo "<td>
								<form method='POST' action='../pages/update_user.php'>
									<input type='hidden' name='id' value='{$row['id']}'>
									<input type='submit' value='Update'>
								</form>
							</td>";
						echo "<td>";
						if ($row['is_suspended'] == 'active') {
							echo "<form method='POST' action='../pages/manage_user.php'> <!-- Add the form for suspending -->";
							echo "<input type='hidden' name='id' value='{$row['id']}'>";
							echo "<input type='submit' name='Suspend' value='Suspend'>";
						} else {
							echo "<form method='POST' action='../controller/reactivate_user_process.php'> <!-- Add the form for reactivating -->";
							echo "<input type='hidden' name='id' value='{$row['id']}'>";
							echo "<input type='submit' value='Reactivate'>";
						}
						echo "</form>";
						echo "</td>";
						echo "<td>
								<form method='POST' action='../controller/delete_user_process.php'>
									<input type='hidden' name='id' value='{$row['id']}'>
									<input type='submit' value='Delete'>
								</form>
							</td>";
						echo "</tr>";
					}
				}
			}
			?>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
