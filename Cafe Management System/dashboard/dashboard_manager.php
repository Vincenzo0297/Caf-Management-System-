<?php
include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/UserProfile.php');

$userProfile = new UserProfile($conn);
$userInfo = $userProfile->getUserProfileById($_SESSION['user_id']);
$profilePictureDirectory = '../assets/';
$profilePicturePath = $profilePictureDirectory . $userInfo['username'] . '.jpg'; 


if (!file_exists($profilePicturePath)) {
    $profilePicturePath = '../assets/default.jpg'; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - CatFe</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $userInfo['username']; ?>!</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../pages/assign_roles.php">Assign Roles</a></li>
			<li><a href="../pages/MaxWorkSlot.php">Set Max Work Slot</a></li>
            <li><a href="../pages/view_available_staff.php">View Available Staff</a></li>
            <li><a href="../pages/view_work_slots.php">View Work Slots</a></li>
            <li><a href="../pages/manage_bids.php">Manage Bids</a></li>
            <li><a href="../controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <div class="user-info-container">
            <div class="user-details">
                <h2>Your Information</h2>
                <p><strong>ID:</strong> <?php echo $userInfo['id']; ?></p>
                <p><strong>Username:</strong> <?php echo $userInfo['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $userInfo['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $userInfo['phone']; ?></p>
                <p><strong>User Type:</strong> <?php echo $userInfo['Type']; ?></p>
                <!-- Edit button -->
                <a href="../pages/edit_profile.php">Edit Profile</a>
            </div>
            <div class="profile-picture-container">
                <?php if (!empty($profilePicturePath)) : ?>
                    <img src="<?php echo $profilePicturePath; ?>" alt="Profile Picture" class="profile-picture">
                <?php else : ?>
                    <p>No profile picture uploaded</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CatFe 2023</p>
    </footer>
</body>

</html>
