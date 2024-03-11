<?php
include("config/db_config.php");

// Sample data for users
$users_data = [

    ["admin", password_hash("adminpassword", PASSWORD_DEFAULT), "admin@catfe.com", "1234567890", "system admin"],
    ["manager", password_hash("managerpassword", PASSWORD_DEFAULT), "manager@catfe.com", "1234567890", "cafe manager"],
    ["owner", password_hash("ownerpassword", PASSWORD_DEFAULT), "owner@catfe.com", "1234567890", "cafe owner"],
    ["staff", password_hash("staffpassword", PASSWORD_DEFAULT), "staff@catfe.com", "1234567890", "cafe staff"]
];


// Insert sample users into the users table
foreach ($users_data as $user) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, Type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $user[0], $user[1], $user[2], $user[3], $user[4]);
    $stmt->execute();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>