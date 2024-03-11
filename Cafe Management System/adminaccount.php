<?php
//Run this script localhost:8080/cat1/adminaccount.php to create admin account
//Note 8080 is my port
include("config/db_config.php");

$password = 'adminpassword';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, email, phone, Type, can_view_dashboard, can_manage_bids, can_manage_work_slots, can_bid_for_work_slots) 
        VALUES ('admin', '$hashedPassword', 'admin@catfe.com', '1234567890', 'system admin', 1, 1, 1, 1)";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
