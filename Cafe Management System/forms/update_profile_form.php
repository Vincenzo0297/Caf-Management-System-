<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Profile - CatFe</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>SIM CatFe</h1>
    </header>

    <nav>
        <ul>
            <li><a href="dashboard_admin.php">Dashboard</a></li>
            <li><a href="manage_profiles.php">Manage User Profiles</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <center>
            <h2>Update User Profile</h2>
            <form action="update_profile_process.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $userProfile['username']; ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $userProfile['email']; ?>" required><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $userProfile['phone']; ?>" required><br>

                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <input type="submit" value="Update Profile">
            </form>
        </center>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
