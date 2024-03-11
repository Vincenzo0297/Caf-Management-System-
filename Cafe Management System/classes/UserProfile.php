<?php

class UserProfile
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method to retrieve user profile by ID
    public function getUserProfileById($userId)
    {
        $stmt = $this->conn->prepare("SELECT users.*, user_profiles.cafe_role FROM users LEFT JOIN user_profiles ON users.id = user_profiles.user_id WHERE users.id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $userData = $result->fetch_assoc();
            // Check if 'save_work_slots' exists in the 'users' table
            $userData['save_work_slots'] = isset($userData['save_work_slots']) ? $userData['save_work_slots'] : null;
            return $userData;

        } else {
            return null;
        }
    }
	
	 public function assignUserRole($staffId, $assignedRole) {
        // Perform validation if necessary

        // Update the user_profiles table with the assigned role
        $updateQuery = "INSERT INTO user_profiles (user_id, cafe_role) VALUES (?, ?)";
        $stmt = $this->conn->prepare($updateQuery);
        $stmt->bind_param("is", $staffId, $assignedRole);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	// Method to retrieve all user information
	public function getAllUserProfile()
	{
		$sql = "SELECT * FROM users";
		$result = $this->conn->query($sql);

		if ($result) {
			return $result;
		} else {
			return null;
		}
	}
	
	// Method for searching user by username
	 public function searchProfileByname($searchTerm) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username LIKE ?");
        $searchTerm = '%' . $searchTerm . '%'; // Add wildcards for partial search
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }
	
	//Method to createProfile
	public function createProfile($username, $canViewDashboard, $canManageBids, $canManageWorkSlots, $canBidForWorkSlots)
    {

        $stmt = $this->conn->prepare("INSERT INTO users (username, can_view_dashboard, can_manage_bids, can_manage_work_slots, can_bid_for_work_slots, Type) 
            VALUES (?, ?, ?, ?, ?, '')");

        $stmt->bind_param("siiii", $username, $canViewDashboard, $canManageBids, $canManageWorkSlots, $canBidForWorkSlots);

        if ($stmt->execute()) {
            return true; // Profile creation successful
        } else {
            return false; // Profile creation failed
        }
    }

    // Method to update user profile
    public function updateUserProfile($userId, $newUsername, $newEmail, $newPhone)
    {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $newUsername, $newEmail, $newPhone, $userId);

        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }

    // Method to delete user profile
    public function deleteUserProfile($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
}

?>
