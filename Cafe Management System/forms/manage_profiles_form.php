<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Profiles - CatFe</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <center>
        <h2>Manage User Profile</h2>
        <form action="update_profile_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $userProfile['username']; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userProfile['email']; ?>" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $userProfile['phone']; ?>" required><br>

            <input type="submit" value="Update Profile">
        </form>
    </center>
</body>

</html>
