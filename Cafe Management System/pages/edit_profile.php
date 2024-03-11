<?php include('../controller/edit_profile_process.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>Edit Your Profile</h1>
    </header>

    <nav>
        <ul>
            <?php switch ($userType) {
                case 'system admin':
                    echo '<li><a href="../dashboard/dashboard_admin.php">Back</a></li>';
                    break;
                case 'cafe owner':
                    echo '<li><a href="../dashboard/dashboard_owner.php">Back</a></li>';
                    break;
                case 'cafe manager':
                    echo '<li><a href="../dashboard/dashboard_manager.php">Back</a></li>';
                    break;
                case 'cafe staff':
                    echo '<li><a href="../dashboard/dashboard_staff.php">Back</a></li>';
                    break;
                } ?>
            <!-- Add more navigation links specific to each user type as needed -->
        </ul>
    </nav>

    <main>
        <!-- Edit profile form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $userInfo['username']; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userInfo['email']; ?>" required><br>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $userInfo['phone']; ?>" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Save Changes">
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
