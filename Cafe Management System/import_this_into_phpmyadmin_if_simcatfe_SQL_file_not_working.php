-- Create the users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    -- 4 new columns for the checkbox
    can_view_dashboard BOOLEAN DEFAULT 0,
    can_manage_bids BOOLEAN DEFAULT 0,
    can_manage_work_slots BOOLEAN DEFAULT 0,
    can_bid_for_work_slots BOOLEAN DEFAULT 0,
    Type ENUM('system admin', 'cafe owner', 'cafe manager', 'cafe staff') NOT NULL
    is_suspended ENUM('active','suspended') DEFAULT 'active',
    max_work_slots int(11) NOT NULL,
    save_work_slots int(11) NOT NULL   
);

-- Create the user_profiles table
CREATE TABLE user_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    cafe_role ENUM('chef', 'cashier', 'waiter'), -- applicable for cafe staff only
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the work_slots table
CREATE TABLE work_slots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cafe_owner_id INT,
    role ENUM('chef', 'cashier', 'waiter') NOT NULL,
    assigned_staff_ids TEXT, -- Store assigned staff IDs as comma-separated values
    work_slot_limit INT, -- Number of work slots available for this role
    work_date DATE, -- Date of the work slot
    FOREIGN KEY (cafe_owner_id) REFERENCES users(id)
);

-- Create the bids table
CREATE TABLE bids (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    work_slot_id INT,
    bid_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (staff_id) REFERENCES users(id),
    FOREIGN KEY (work_slot_id) REFERENCES work_slots(id)
);

-- Create the user_work_slot_count table
CREATE TABLE user_work_slot_count (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    week_start_date DATE NOT NULL,
    picked_work_slots INT DEFAULT 0,
    UNIQUE KEY unique_staff_week (staff_id, week_start_date)
);


/*
-- Create the assigned_roles table
CREATE TABLE assigned_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    role ENUM('chef', 'cashier', 'waiter'),
    FOREIGN KEY (staff_id) REFERENCES users(id)
);

-- Create the staff_availability table
CREATE TABLE staff_availability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    work_day DATE,
    available BOOLEAN,
    FOREIGN KEY (staff_id) REFERENCES users(id)
);
*/