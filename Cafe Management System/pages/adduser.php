<?php
//include('../config/session_start.php');
include('../config/db_config.php');
include('../controller/adduser_process.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];

    // Create an instance of the controller class
    $addUserController = new adduser_process($conn);

    // Call the processAddUser method to handle user addition
    $addUserController->processAddUser($username, $password, $email, $phone, $type);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - CatFe</title>
	<link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>SIM CatFe</h1>
    </header>
    <style>
	.error-message {
    color: red;
}
	</style>
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
    <center><h2>Add New User</h2></center>
        <form action="../pages/adduser.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="usernamename" name="username" required><br>
			<?php
			if (isset($_SESSION['registration_error'])) {
				echo '<div class="error-message">' . $_SESSION['registration_error'] . '</div>';
				unset($_SESSION['registration_error']); // Clear the error message after displaying
			}
			?>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>
        
            <label for="type">User Type:</label>
            <select id="type" name="type">
                <option value="system admin">system admin</option>
                <option value="cafe owner">cafe owner</option>
                <option value="cafe manager">cafe manager</option>
                <option value="cafe staff">cafe staff</option>
            </select><br>
            
            <input type="submit" value="Add User!">
        </form>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>

</body>
</html>
