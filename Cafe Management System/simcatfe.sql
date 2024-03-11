-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 10:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simcatfe`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `work_slot_id` int(11) DEFAULT NULL,
  `bid_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `user_work_slot_count` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `week_start_date` date NOT NULL,
  `picked_work_slots` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `can_view_dashboard` tinyint(1) DEFAULT 0,
  `can_manage_bids` tinyint(1) DEFAULT 0,
  `can_manage_work_slots` tinyint(1) DEFAULT 0,
  `can_bid_for_work_slots` tinyint(1) DEFAULT 0,
  `Type` enum('system admin','cafe owner','cafe manager','cafe staff') NOT NULL,
  `is_suspended` enum('active','suspended') NOT NULL DEFAULT 'active',
  `max_work_slots` int(11) NOT NULL,
  `save_work_slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `can_view_dashboard`, `can_manage_bids`, `can_manage_work_slots`, `can_bid_for_work_slots`, `Type`) VALUES
(1, 'admin1', '$2y$10$4UczY6NiQ8pwIdW7MZVgbOUGd6H934CaH4RvUb0CzYL/19xQvzUbC', 'admin1@catfe.com', '1234567890', 1, 0, 0, 0, 'system admin'),
(2, 'owner1', '$2y$10$5wB7sjH/TxzwTeFxpTsb0OJYehLQxHzpq9nide6sFWLEz9E8i.A.6', 'owner1@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe owner'),
(3, 'owner2', '$2y$10$Om2U0Xli2Hg0tNoUSB6lEeyKVanNDh8XgIf1csLcCiYrNZm/WfIwe', 'owner2@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe owner'),
(4, 'manager1', '$2y$10$a.V5XMkNrdBTMC0GGasc2OPv8CqmO3OHLyQtfQfMhp1nlyRECbtsa', 'manager1@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe manager'),
(5, 'manager2', '$2y$10$jCJdeKXLRlTC6iecijQ3Quc4kWxQuPnakHQvkSzV9wcuUd4KHc9uy', 'manager2@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe manager'),
(6, 'staff1', '$2y$10$o9K9uBuERiFvvL4Hqpehr.WdPXumtXR9FvzIONQOGkmAWp6OtdWGy', 'staff1@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(7, 'staff2', '$2y$10$M0MQD9e//cAIreqDztvRVuYAssDivMeFUg2jE/HhehTt4O7dLa.5q', 'staff2@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(8, 'staff3', '$2y$10$zjbNr80fR7mcV7HVcZ5OhOa4mAP7w/0hisBqSohoMqUuCVrjxTUA.', 'staff3@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(9, 'staff4', '$2y$10$GdHzBq36CMos/mn2/nt1leW3CnKsWWIfYLTTWeSoYZfDIg4Kz8KTa', 'staff4@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(10, 'staff5', '$2y$10$wVXTHlZMQWIF08OWy0VvRO7uUk7gY3VWZCKCRq50kz0KNh9XEOmMW', 'staff5@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(11, 'staff6', '$2y$10$Ad4cuetg1JwVuA1uHqzs/.77MkYBk6Kd/MwNOULG6B6w8Bxm6TqiK', 'staff6@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(12, 'staff7', '$2y$10$PUBw9IwdmV0xnnD/Z2J9uOHrLwJF5NaOvt790DDsHWoMCWK.kDNee', 'staff7@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(13, 'staff8', '$2y$10$AXK7J2w/dSr4ekAcWR9WQePa93i6cniPn1RMKHJFWRGMFr47BH/FS', 'staff8@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(14, 'staff9', '$2y$10$HjMofSOFkIZB9INBkl5wYu.p/cwXPMYUDTyC49OCDLVtd9OiKblAi', 'staff9@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(15, 'staff10', '$2y$10$xKVilaYrC6oP.bWRBgzivegpCpTstJOfbbCHaydydGOdKjRmJRO5m', 'staff10@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(16, 'staff11', '$2y$10$/xjgPkDBNDE.wSRUTZQfY.Vo4I76C10KdTIBtn3QXpUvMkmQSBZia', 'staff11@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(17, 'staff12', '$2y$10$Z3zQwD0WOniZGFe2QNFlj.pfJspgwlsOlaFUuskx2yO1QBWh4GNZi', 'staff12@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(18, 'staff13', '$2y$10$KPB5WTHenoB.120R/4XXfuS79RTyB8PseT6hMC7pwXf5HgYcuAYfy', 'staff13@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(19, 'staff14', '$2y$10$HV.Ir97PJQ8VJ3AWDSeeduYA66AC.RUFDExHD/sXbkQDXzFB5dUL6', 'staff14@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(20, 'staff15', '$2y$10$1NZ5/.bQOVKhCDT7fS22p.WO8jrtYG9BdY1v1QEw/HdTcCvwuzaum', 'staff15@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe staff'),
(22, 'manager', '$2y$10$jmiE3lk0hEqIxTHplGTMXuDihqmaXu7QPI1I4BePhobUBljaybXV6', 'manager@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe manager'),
(23, 'owner', '$2y$10$isYUAIB6xzfvk7a2kkNq2.1pX9T.KTiMk8EoT1AG7dL4GJ0Td9auq', 'owner@catfe.com', '1234567890', 1, 0, 0, 0, 'cafe owner'),
(24, 'staff', '$2y$10$5hZWo70X7kMHKeahk5jL9O4gjJ.p.scxyvBRw7zzkYVc335wF42oi', 'staff@catfe.com', '1234567890', 1, 0, 0, 1, 'cafe staff'),
(27, 'admin', '$2y$10$nuJbSXXUmgIGmYJTsVXjCuGpkWlvsDTnl5i17PN1/ZRHk1OYu9AFK', 'admin@catfe.com', '1234567890', 1, 0, 0, 0, 'system admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cafe_role` enum('chef','cashier','waiter') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `cafe_role`) VALUES
(1, 6, 'chef');

-- --------------------------------------------------------

--
-- Table structure for table `work_slots`
--

CREATE TABLE `work_slots` (
  `id` int(11) NOT NULL,
  `cafe_owner_id` int(11) DEFAULT NULL,
  `role` enum('chef','cashier','waiter') NOT NULL,
  `assigned_staff_ids` text DEFAULT NULL,
  `work_slot_limit` int(11) DEFAULT NULL,
  `work_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_slots`
--

INSERT INTO `work_slots` (`id`, `cafe_owner_id`, `role`, `assigned_staff_ids`, `work_slot_limit`, `work_date`) VALUES
(1, 2, 'chef', NULL, 7, '2023-10-15'),
(2, 3, 'cashier', NULL, 8, '2023-10-15'),
(4, 2, 'chef', NULL, 7, '2023-10-16'),
(5, 3, 'cashier', NULL, 8, '2023-10-16'),
(7, 2, 'chef', NULL, 7, '2023-10-17'),
(8, 3, 'cashier', NULL, 8, '2023-10-17'),
(10, 2, 'chef', NULL, 7, '2023-10-18'),
(11, 3, 'cashier', NULL, 8, '2023-10-18'),
(13, 2, 'chef', NULL, 7, '2023-10-19'),
(14, 3, 'cashier', NULL, 8, '2023-10-19'),
(16, 2, 'chef', NULL, 7, '2023-10-20'),
(17, 3, 'cashier', NULL, 8, '2023-10-20'),
(19, 2, 'chef', NULL, 7, '2023-10-21'),
(20, 3, 'cashier', NULL, 8, '2023-10-21'),
(21, 23, 'waiter', ',6', 5, '2023-10-15'),
(22, 23, 'waiter', ',6', 5, '2023-10-16'),
(23, 23, 'waiter', ',6', 5, '2023-10-17'),
(24, 23, 'waiter', ',6', 5, '2023-10-18'),
(25, 23, 'waiter', ',6', 5, '2023-10-19'),
(26, 23, 'waiter', ',6', 5, '2023-10-20'),
(27, 23, 'waiter', ',6', 5, '2023-10-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `work_slot_id` (`work_slot_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `work_slots`
--
ALTER TABLE `work_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cafe_owner_id` (`cafe_owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `work_slots`
--
ALTER TABLE `work_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`work_slot_id`) REFERENCES `work_slots` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `work_slots`
--
ALTER TABLE `work_slots`
  ADD CONSTRAINT `work_slots_ibfk_1` FOREIGN KEY (`cafe_owner_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
