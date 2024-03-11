<?php
include('../config/session_start.php');
include('../config/db_config.php');
//include('../classes/UserProfile.php');
//include('../classes/User.php');
include('../controller/update_user_process.php');

$user = new User($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateUser'])) {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $newType = $_POST['type'];

    $updateController = new update_user_process($conn);
    $updateController->updateForUser($userId, $username, $password, $email, $phone, $newType);
} else {
    // Invalid request method
    $_SESSION['update_profile_error'] = "Invalid request method.";
}



// Check if user ID is provided in the URL parameters
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];
    $userData = $user->getUserById($userId);
    
    // Check if user data is retrieved successfully
    if (!$userData) {
        header("Location: manage_user.php"); 
        exit;
    }
} else {
    header("Location: manage_user.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>SIM CatFe</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/dashboard_admin.php">Dashboard</a></li>
            <li><a href="manage_user.php">Manage User</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <div class="center">
            <h2>Update User</h2>
            <form action="update_user.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>" required><br>
				
				<label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo $userData['password']; ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>" required><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $userData['phone']; ?>" required><br>

                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

                <label for="type">User Type:</label>
            <select id="type" name="type">
                <option value="system admin">system admin</option>
                <option value="cafe owner">cafe owner</option>
                <option value="cafe manager">cafe manager</option>
                <option value="cafe staff">cafe staff</option>
            </select><br>
            
                <input type="submit" name="updateUser" value="Update User">
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
