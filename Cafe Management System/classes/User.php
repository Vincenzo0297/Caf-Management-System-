<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
	//Method to add users
	public function addUser($username, $password, $email, $phone, $type)
    {
        // Check if the username already exists
		$stmt = $this->conn->prepare("SELECT username FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			return "Username already exists"; // Username is not unique
		}
		
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (`username`, `password`, `email`, `phone`, Type) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $hashedPassword, $email, $phone, $type);

        if ($stmt->execute()) {
            return true; // User added successfully
        } else {
            return false; // Error occurred while adding user
        }
    }
	
	// Method for searching user by username
	 public function searchUserByUsername($searchTerm) {
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

public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password, Type, is_suspended FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
    
            if (password_verify($password, $user['password'])) {
                if ($user['is_suspended'] === 'active') {
                    // Account is not suspended, set session variables and return the user type
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user['Type'];
                    return $user['Type'];
                } else {
                    // Account is suspended, redirect to the suspended account page
                    header('Location: ../pages/error.php');
                    exit;
                }
            }
        }
    
        // Invalid username or password
        return 'User not found';
    }

    // Method to authenticate users
 /*   public function authenticate($username, $password)
{
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session variables for user ID and user type
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['Type'];

            $userType = $user['Type'];

            // Return the user type or an error message
            switch ($userType) {
                case 'system admin':
                    return 'system admin';
                case 'cafe owner':
                    return 'cafe owner';
                case 'cafe manager':
                    return 'cafe manager';
                case 'cafe staff':
                    return 'cafe staff';
                default:
                    return 'Invalid user type';
            }
        } else {
            return 'Incorrect username or password';
        }
    } else {
        return 'User not found';
    }
}*/
	
	
	// Method to retrieve all user information
	public function getAllUsers()
	{
		$sql = "SELECT * FROM users";
		$result = $this->conn->query($sql);

		if ($result) {
			return $result;
		} else {
			return null;
		}
	}
	
	public function getAvailableStaff()
{
    $sql = "SELECT user_profiles.user_id AS id, users.username, users.email, users.phone, user_profiles.cafe_role 
            FROM users
            LEFT JOIN user_profiles ON users.id = user_profiles.user_id
            WHERE users.Type = 'café staff' AND user_profiles.cafe_role IS NOT NULL";

    $result = $this->conn->query($sql);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
    // Method to retrieve user information by ID
    public function getUserById($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

   // Method to update user profile
// Method to update user profile including user type
public function updateUser($userId, $newUsername, $newPassword, $newEmail, $newPhone, $newUserType)
{
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ?, email = ?, phone = ?, Type = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $newUsername, $hashedPassword, $newEmail, $newPhone, $newUserType, $userId);

    if ($stmt->execute()) {
        return true; // Update successful
    } else {
        return false; // Update failed
    }
}
public function updateUserProfile($id, $username, $can_view_dashboard, $can_manage_bids, $can_manage_work_slots, $can_bid_for_work_slots) {
    try {
        $sql = "UPDATE users SET username = ?, can_view_dashboard = ?, can_manage_bids = ?, can_manage_work_slots = ?, can_bid_for_work_slots = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $can_view_dashboard, $can_manage_bids, $can_manage_work_slots, $can_bid_for_work_slots, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

  /*
    // Prepare the SQL query dynamically based on provided permissions
    $sql = "UPDATE users SET username = ?";
    $bindTypes = "s";
    $bindValues = array($username);

    foreach ($permissions as $key => $value) {
        $sql .= ", " . $key . " = ?";
        $bindTypes .= "i";
        $bindValues[] = $value;
    }

    $sql .= " WHERE id = ?";
    $bindTypes .= "i";
    $bindValues[] = $userId;

    $stmt = $this->conn->prepare($sql);

    // Bind parameters dynamically based on the number of permissions
    $stmt->bind_param($bindTypes, ...$bindValues);

    if ($stmt->execute()) {
        return true; // Update successful
    } else {
        return false; // Update failed
    }
    */



	// Method to delete user profile
    public function deleteUser($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
	
    private function redirect($location)
    {
        header("Location: $location");
        exit;
    }
	
	public function suspendUser($userId)
    {
        $stmt = $this->conn->prepare("UPDATE users SET is_suspended = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return true; // Suspend successful
        } else {
            return false; // Suspend failed
        }
    }

    public function reactivateUser($userId)
    {
        $stmt = $this->conn->prepare("UPDATE users SET is_suspended = 'active' WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return true; // Reactivation successful
        } else {
            return false; // Reactivation failed
        }
    }

    public function getCafeRoleId($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM user_profiles WHERE user_id = (SELECT id FROM users WHERE username = ?)");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return null; // Return null if the cafe role ID is not found
        }
    }
	
	public function setUserMaxWorkSlot($staffId, $maxWorkSlot, $saveWorkSlot)
{
    // Validate input
    if (!is_int($staffId) || $staffId <= 0 || !is_int($maxWorkSlot) || $maxWorkSlot < 0) {
        // Handle invalid input
        return;
    }

    // Update the maximum work slots and another column in the database
    $sql = "UPDATE users SET max_work_slots = ?, save_work_slots = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $maxWorkSlot, $saveWorkSlot, $staffId);

    if ($stmt->execute()) {
        // Log success or other information
        error_log("Max work slots and another column updated successfully for user ID: $staffId");
    } else {
        // Log error
        error_log("Error updating max work slots and another column: " . $stmt->error);
    }

    $stmt->close();
}

}

?>
