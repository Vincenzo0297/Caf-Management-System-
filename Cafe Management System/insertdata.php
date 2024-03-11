<?php
include("config/db_config.php");

// Sample data for users
$users_data = [
    ["admin1", password_hash("admin1pass", PASSWORD_DEFAULT), "admin1@catfe.com", "1234567890", "system admin"],
    ["owner1", password_hash("owner1pass", PASSWORD_DEFAULT), "owner1@catfe.com", "1234567890", "cafe owner"],
    ["owner2", password_hash("owner2pass", PASSWORD_DEFAULT), "owner2@catfe.com", "1234567890", "cafe owner"],
    ["manager1", password_hash("manager1pass", PASSWORD_DEFAULT), "manager1@catfe.com", "1234567890", "cafe manager"],
    ["manager2", password_hash("manager2pass", PASSWORD_DEFAULT), "manager2@catfe.com", "1234567890", "cafe manager"],
    ["staff1", password_hash("staff1pass", PASSWORD_DEFAULT), "staff1@catfe.com", "1234567890", "cafe staff"],
    ["staff2", password_hash("staff2pass", PASSWORD_DEFAULT), "staff2@catfe.com", "1234567890", "cafe staff"],
    ["staff3", password_hash("staff3pass", PASSWORD_DEFAULT), "staff3@catfe.com", "1234567890", "cafe staff"],
    ["staff4", password_hash("staff4pass", PASSWORD_DEFAULT), "staff4@catfe.com", "1234567890", "cafe staff"],
    ["staff5", password_hash("staff5pass", PASSWORD_DEFAULT), "staff5@catfe.com", "1234567890", "cafe staff"],
    ["staff6", password_hash("staff6pass", PASSWORD_DEFAULT), "staff6@catfe.com", "1234567890", "cafe staff"],
    ["staff7", password_hash("staff7pass", PASSWORD_DEFAULT), "staff7@catfe.com", "1234567890", "cafe staff"],
    ["staff8", password_hash("staff8pass", PASSWORD_DEFAULT), "staff8@catfe.com", "1234567890", "cafe staff"],
    ["staff9", password_hash("staff9pass", PASSWORD_DEFAULT), "staff9@catfe.com", "1234567890", "cafe staff"],
    ["staff10", password_hash("staff10pass", PASSWORD_DEFAULT), "staff10@catfe.com", "1234567890", "cafe staff"],
    ["staff11", password_hash("staff11pass", PASSWORD_DEFAULT), "staff11@catfe.com", "1234567890", "cafe staff"],
    ["staff12", password_hash("staff12pass", PASSWORD_DEFAULT), "staff12@catfe.com", "1234567890", "cafe staff"],
    ["staff13", password_hash("staff13pass", PASSWORD_DEFAULT), "staff13@catfe.com", "1234567890", "cafe staff"],
    ["staff14", password_hash("staff14pass", PASSWORD_DEFAULT), "staff14@catfe.com", "1234567890", "cafe staff"],
    ["staff15", password_hash("staff15pass", PASSWORD_DEFAULT), "staff15@catfe.com", "1234567890", "cafe staff"],
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

$conn->close();
?>
