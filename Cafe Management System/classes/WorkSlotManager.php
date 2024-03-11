<?php
require_once("WorkSlot.php");
include('../config/db_config.php');
require_once("Bid.php");
require_once("User.php");


class WorkSlotManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getWorkSlotsByOwner($ownerId) {
        $workSlots = array();
        $query = "SELECT * FROM work_slots WHERE cafe_owner_id = $ownerId";
        $result = $this->conn->query($query);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $workSlot = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_date'], $row['work_slot_limit']);
                array_push($workSlots, $workSlot);
            }
        }
    
        return $workSlots;
    }
	
	public function searchWorkSlots($role, $date) {
    $workSlots = array();
    $query = "SELECT * FROM work_slots WHERE 1=1";

    $params = array();

    if (!empty($role) && $role !== "All Roles") {
        $query .= " AND role = ?";
        $params[] = $role;
    }

    if (!empty($date)) {
        $query .= " AND work_date = ?";
        $params[] = $date;
    }

    $stmt = $this->conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $workSlot = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_date'], $row['work_slot_limit']);
            array_push($workSlots, $workSlot);
        }
    }

    return $workSlots;
}

	
    // Method to get bids by staff ID
    public function getBidsByStaffId($staffId) {
        $bids = [];
        $sql = "SELECT * FROM bids WHERE staff_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $staffId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $bid = new Bid($row['id'], $row['staff_id'], $row['work_slot_id'], $row['bid_status']);
            $bids[] = $bid;
        }

        return $bids;
    }

    public function addWorkSlot($cafeOwnerId, $role, $workDate, $workSlotLimit) {
        $assignedStaffIds = ''; // Initially, no staff assigned
        $workSlot = new WorkSlot(null, $cafeOwnerId, $role, $assignedStaffIds, $workDate, $workSlotLimit);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateWorkSlot($workSlotId, $role, $workDate, $workSlotLimit)
{
    $stmt = $this->conn->prepare("UPDATE work_slots SET role = ?, work_date = ?, work_slot_limit = ? WHERE id = ?");
    $stmt->bind_param("ssii", $role, $workDate, $workSlotLimit, $workSlotId);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Update error: " . $stmt->error);
        return false;
    }
}

    public function deleteWorkSlot($workSlotId)
{
    $stmt = $this->conn->prepare("DELETE FROM work_slots WHERE id = ?");
    $stmt->bind_param("i", $workSlotId);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

public function getWorkSlotById($workSlotId)
    {
        $query = "SELECT * FROM work_slots WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $workSlotId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $workSlot = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_date'], $row['work_slot_limit']);
            return $workSlot;
        } else {
            return null;
        }
    }

    public function createWorkSlot($cafeOwnerId, $role, $workDate, $workSlotLimit)
    {
        $stmt = $this->conn->prepare("INSERT INTO work_slots (cafe_owner_id, role, work_date, work_slot_limit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $cafeOwnerId, $role, $workDate, $workSlotLimit);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Method to get all work slots for all users
    public function getAllWorkSlots()
{
    $userType = $_SESSION['user_type'];
    @$cafeRoleId = $_SESSION['cafe_role_id'];

    // Modify the query based on the user type and cafe role
    if ($userType === 'cafe staff') {
        $workSlots = $this->getWorkSlotsByCafeRole($cafeRoleId);
    } else {
        // Default query to fetch all work slots
        $sql = "SELECT * FROM work_slots";
        $result = $this->conn->query($sql);

        $workSlots = [];
        while ($row = $result->fetch_assoc()) {
            $workSlot = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_date'], $row['work_slot_limit']);
            $workSlots[] = $workSlot;
        }
    }

    return $workSlots;
}

    // Get bid details by bid ID
    public function getBidById($bidId) {
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bidId);
        $stmt->execute();
        $result = $stmt->get_result();
        $bid = $result->fetch_assoc();
        $stmt->close();
        return $bid;
    }

    // Update bid status by bid ID
    public function updateBidStatus($bidId, $status) {
        $sql = "UPDATE bids SET bid_status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $bidId);
        $stmt->execute();
        $stmt->close();
    }

    public function cancelBid($bidId)
    {
        $sql = "DELETE FROM bids WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bidId);
        if ($stmt->execute()) {
            return true; // Bid canceled successfully
        } else {
            error_log("Error canceling bid: " . $stmt->error);
            return false; // Bid cancellation failed
        }
    }
    public function hasBid($staffId, $workSlotId) {
        $sql = "SELECT * FROM bids WHERE staff_id = ? AND work_slot_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $staffId, $workSlotId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function placeBid($staffId, $workSlotId) {
        $sql = "INSERT INTO bids (staff_id, work_slot_id, bid_status) VALUES (?, ?, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $staffId, $workSlotId);
        return $stmt->execute();
    }

    // Function to fetch date by work_slot_id
    public function getDateByWorkSlotId($workSlotId)
    {
        $sql = "SELECT work_date FROM work_slots WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $workSlotId);
        $stmt->execute();
        $stmt->bind_result($workDate);
        $stmt->fetch();
        $stmt->close();
        return $workDate;
    }

    // Function to fetch role by work_slot_id
    public function getRoleByWorkSlotId($workSlotId)
    {
        $sql = "SELECT role FROM work_slots WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $workSlotId);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();
        return $role;
    }
    public function getAssignedStaffUsernames($conn) {
        // Assuming $this->assignedStaffIds is a comma-separated string of staff IDs
        $staffIds = explode(',', $this->assignedStaffIds);
        $usernames = [];
    
        foreach ($staffIds as $staffId) {
            // Fetch usernames from the database based on staff IDs and store them in an array
            $username = getUserUsernameById($staffId, $conn); // Implement this function to fetch username by staff ID
            if ($username) {
                $usernames[] = $username;
            }
        }
    
        return $usernames;
    }

    public function getAssignedStaffNames($workSlotId)
    {
        $workSlot = $this->getWorkSlotById($workSlotId);
        if ($workSlot && isset($workSlot->assigned_staff_ids)) {
            $staffIds = explode(',', $workSlot->assigned_staff_ids);
            $staffNames = [];
            foreach ($staffIds as $staffId) {
                $staffName = $this->getUserUsernameById($staffId);
                if ($staffName) {
                    $staffNames[] = $staffName;
                }
            }
            return $staffNames;
        } else {
            return []; // Return an empty array if the assigned_staff_ids is not set or if there are no staff IDs.
        }
    }
    public function getAssignedStaffIds($workSlotId) {
        $sql = "SELECT assigned_staff_ids FROM work_slots WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $workSlotId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $staffIdsString = $row['assigned_staff_ids'];
            return explode(",", $staffIdsString);
        } else {
            return [];
        }
    }

    public function getStaffUsernameById($staffId) {
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $staffId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['username'];
        } 
    }
    public function getWorkSlotsByCafeRole($cafeRoleId)
    {
        $sql = "SELECT * FROM work_slots WHERE role = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cafeRoleId); // Assuming cafe role IDs are stored as integers in the database
        $stmt->execute();
        $result = $stmt->get_result();
        $workSlots = [];

        while ($row = $result->fetch_assoc()) {
            $workSlot = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_date'], $row['work_slot_limit']);

            // You might need to set other properties of the WorkSlot object here based on your database schema
            $workSlots[] = $workSlot;
        }

        return $workSlots;
    }
    public function getWorkSlotsForCafeStaff($cafeStaffId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM work_slots WHERE role IN (SELECT cafe_role FROM user_profiles WHERE user_id = ?)");
        $stmt->bind_param("i", $cafeStaffId);
        $stmt->execute();
        $result = $stmt->get_result();
        $workSlots = [];

        while ($row = $result->fetch_assoc()) {
            $workSlots[] = new WorkSlot($row['id'], $row['cafe_owner_id'], $row['role'], $row['assigned_staff_ids'], $row['work_slot_limit'], $row['work_date']);
        }

        return $workSlots;
    }
	
	 public function getMaxWorkSlots($staffId)
    {
        $sql = "SELECT max_work_slots FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $staffId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['max_work_slots'];
        } else {
            return 0; // Return 0 if the user is not found or max_work_slots is not set
        }
    }
	
	 public function getSaveWorkSlots($staffId)
    {
        $sql = "SELECT save_work_slots FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $staffId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['save_work_slots'];
        } else {
            return 0; // Return 0 if the user is not found or max_work_slots is not set
        }
    }
	
	private function getBidIdByWorkSlotAndStaff($workSlotId, $staffId)
	{
		$sql = "SELECT id FROM bids WHERE work_slot_id = ? AND staff_id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("ii", $workSlotId, $staffId);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['id'];
		} else {
			return null;
		}
	}
	
	
	public function addToAssignedStaffIds($workSlotId, $staffId)
{
    // Check the maximum work slots for the user
    $maxWorkSlots = $this->getMaxWorkSlots($staffId);
	$saveWorkSlots = $this->getSaveWorkSlots($staffId);

    // Check if the user has reached the maximum work slots
    if ($maxWorkSlots <= 0) {
        return "You have reached the maximum allowed work slots.";
    }

    // Check the number of work slots picked by the user in the current week
    $pickedWorkSlotsThisWeek = $this->getPickedWorkSlotsThisWeek($staffId);

    // Check if the user has reached the limit for the current week
    //$maxWorkSlotsPerWeek = 2; // You can adjust this limit as needed
    if ($pickedWorkSlotsThisWeek >= $saveWorkSlots) {
        // Get the bid ID associated with the current work slot and staff ID
        $bidId = $this->getBidIdByWorkSlotAndStaff($workSlotId, $staffId);

        // Update the bid status to 'rejected' when max work slots for the week are reached
        if ($bidId) {
            $updateBidSql = "UPDATE bids SET bid_status = 'rejected' WHERE id = ?";
            $stmt = $this->conn->prepare($updateBidSql);
            $stmt->bind_param("i", $bidId);

            if ($stmt->execute()) {
                // If the update is successful, you may perform additional actions if needed
                return "You have reached the maximum allowed work slots for this week. Bid status updated to 'rejected'.";
            } else {
                return "Error updating bid status: " . $stmt->error;
            }
        }

        return "Bid ID not found. Unable to update bid status.";
    }

    // Update the record in the database
    $updateWorkSlotSql = "UPDATE work_slots SET assigned_staff_ids = CONCAT_WS(',', COALESCE(assigned_staff_ids, ''), ?), work_slot_limit = work_slot_limit - 1 WHERE id = ?";
    $stmt = $this->conn->prepare($updateWorkSlotSql);
    $stmt->bind_param("ii", $staffId, $workSlotId);

    if ($stmt->execute()) {
        // Decrease the user's maximum work slots after a successful bid
        $this->decreaseMaxWorkSlots($staffId);

        // Increment the number of work slots picked by the user in the current week
        //$this->incrementPickedWorkSlotsThisWeek($staffId);

        return "Bid status updated, staff ID added to assigned_staff_ids column, and work slot count incremented for the week.";
    } else {
        return "Error updating work slot: " . $stmt->error;
    }
}




    // Method to get the number of work slots picked by the user in the current week
    private function getPickedWorkSlotsThisWeek($staffId)
    {
        $currentWeekStartDate = date('Y-m-d', strtotime('monday this week'));
        $currentWeekEndDate = date('Y-m-d', strtotime('sunday this week'));

        $sql = "SELECT COUNT(*) FROM work_slots WHERE FIND_IN_SET(?, assigned_staff_ids) AND work_date BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $staffId, $currentWeekStartDate, $currentWeekEndDate);
        $stmt->execute();
        $stmt->bind_result($pickedWorkSlots);
        $stmt->fetch();
        $stmt->close();

        return $pickedWorkSlots;
    }
	
	public function decreaseMaxWorkSlots($staffId)
{
    // Validate input
    if (!is_int($staffId) || $staffId <= 0) {
        // Handle invalid input
        return;
    }

    // Check the current max_work_slots value
    $currentMaxWorkSlots = $this->getMaxWorkSlots($staffId);

    if ($currentMaxWorkSlots > 0) {
        // Decrease the maximum work slots in the database
        $sql = "UPDATE users SET max_work_slots = GREATEST(max_work_slots - 1, 0) WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $staffId);

        if ($stmt->execute()) {
            // Log success or other information
            error_log("Max work slots decreased successfully for user ID: $staffId");
        } else {
            // Log error
            error_log("Error decreasing max work slots: " . $stmt->error);
        }

        $stmt->close();
    } else {
        // Log or handle the case where max_work_slots is already 0
        error_log("Max work slots is already 0 for user ID: $staffId");
    }
}


    // Method to increment the number of work slots picked by the user in the current week
    private function incrementPickedWorkSlotsThisWeek($staffId)
    {
		$currentWeekStartDate = date('Y-m-d', strtotime('monday this week'));
        $sql = "UPDATE user_work_slot_count SET picked_work_slots = picked_work_slots + 1 WHERE staff_id = ? AND week_start_date = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $staffId, $currentWeekStartDate);
        $stmt->execute();
        $stmt->close();
    }
	
	 public function increaseMaxWorkSlots($staffId)
	{
		// Validate input
		if (!is_int($staffId) || $staffId <= 0) {
			// Handle invalid input
			return;
		}

		// Increase the maximum work slots in the database
		$sql = "UPDATE users SET max_work_slots = max_work_slots + 1 WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("i", $staffId);

		if ($stmt->execute()) {
			// Log success or other information
			error_log("Max work slots increased successfully for user ID: $staffId");
		} else {
			// Log error
			error_log("Error increasing max work slots: " . $stmt->error);
		}

		$stmt->close();
	}
	public function getAllAssignedStaffIds($workSlotId)
{

    // Retrieve assigned staff IDs from the database
    $sql = "SELECT assigned_staff_ids FROM work_slots WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $workSlotId);
    $stmt->execute();
    $stmt->bind_result($assignedStaffIds);

    // Fetch the first row
    $stmt->fetch();

    $stmt->close();

    // Return the assigned staff IDs as a string
    return $assignedStaffIds ?: '';
}



	
	public function removeStaffFromWorkSlot($workSlotId, $staffId)
	{
	   // Explicitly cast to integers
		$workSlotId = (int)$workSlotId;
		$staffId = (int)$staffId;

		// Validate input
		if ($workSlotId <= 0 || $staffId <= 0) {
			// Handle invalid input
			return "Invalid input";
		}

	   // Retrieve assigned staff IDs from the database
		$assignedStaffIdsString = $this->getAllAssignedStaffIds($workSlotId);
		$assignedStaffIds = explode(',', $assignedStaffIdsString);

		// Find and remove the specified staffId from the array
		$indexToRemove = array_search($staffId, $assignedStaffIds);

		if ($indexToRemove !== false) {
			unset($assignedStaffIds[$indexToRemove]);

			// Update the record in the database
			$updatedAssignedStaffIdsString = implode(',', $assignedStaffIds);
			$updateWorkSlotSql = "UPDATE work_slots SET assigned_staff_ids = ? WHERE id = ?";
			$stmt = $this->conn->prepare($updateWorkSlotSql);
			$stmt->bind_param("si", $updatedAssignedStaffIdsString, $workSlotId);

			if ($stmt->execute()) {
				// Increment the user's maximum work slots after removing a staff from the assignment
				$this->increaseMaxWorkSlots($staffId);
				$this->handleBidsOnStaffRemoval($staffId, $workSlotId);

				return "success";
			} else {

				return "Error removing staff from work slot assignment: " . $stmt->error;
			}
		} else {
			echo "Staff ID not found in assigned staff IDs.<br>";
			return "Staff ID not found in assigned staff IDs.";
		}
    }

private function handleBidsOnStaffRemoval($workSlotId, $staffId) {
        // Assuming bids table structure (adjust based on your actual structure)
        $bidSql = "UPDATE bids SET bid_status = 'rejected' WHERE staff_id = ? AND work_slot_id = ?";
        $bidStmt = $this->conn->prepare($bidSql);
        $bidStmt->bind_param("ii", $workSlotId, $staffId);

        if ($bidStmt->execute()) {
            // You can perform additional actions if needed
            // For example, notify users about bid rejection
        } else {
            // Handle the error
            // Log it, throw an exception, or perform other error handling actions
        }

        $bidStmt->close();
    }
	
	 public function updateWorkSlotDate($bid, $newDate, $selectedSlotId)
    {
        // Fetch available slots with the same role type
        $role = $this->getRoleByWorkSlotId($bid['work_slot_id']);
        $availableSlots = $this->getAvailableSlotsByRole($role);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newDate'])) {
        $newDate = $_POST['newDate'];

        // Find the selected slot in the available slots
        $selectedSlot = null;
        foreach ($availableSlots as $slot) {
            if ($slot['id'] == $newDate) {
                $selectedSlot = $slot;
                break;
            }
        }
        
// Add debugging output
// echo "Debug: newDate = $newDate, selectedSlot: " . print_r($selectedSlot, true);

// If the selected slot is found, remove the old bid and place a new bid for the selected slot
if ($selectedSlot) {
    // Remove the old bid
    $this->cancelBid($bid['id']);

    // Place a new bid for the selected slot
    $staffId = $bid['staff_id'];
    $newWorkSlotId = $selectedSlot['id'];
    $this->placeBid($staffId, $newWorkSlotId);

    return true;
} else {
    return false;
}
}
    }


// Function to get available slots by role
public function getAvailableSlotsByRole($role)
{
    $currentDate = date('Y-m-d');
    $sql = "SELECT id, work_date FROM work_slots WHERE role = ? AND work_date >= ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $role, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $availableSlots = [];
    while ($row = $result->fetch_assoc()) {
        // Include the work date, id, and role in the array
        $availableSlots[] = [
            'id' => $row['id'],
            'work_date' => $row['work_date'],
            'role' => $role
        ];
    }

    return $availableSlots;
}








	// Add this method to retrieve the assigned staff IDs for a work slot



}
    

?>
