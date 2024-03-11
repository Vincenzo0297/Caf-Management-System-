<?php
include('../config/db_config.php');
require_once("WorkSlot.php");
require_once("UserProfile.php");
require_once("User.php");
class Bid {

    public function __construct($conn) {
		$this->conn = $conn;
    }
	
	 public function getBidsToManage()
    {
        $sql = "SELECT bids.id as bid_id, users.username, user_profiles.cafe_role, work_slots.id as work_slot_id, work_slots.role as work_slot_role, work_slots.work_date, bids.bid_status 
                FROM bids 
                INNER JOIN users ON bids.staff_id = users.id 
                INNER JOIN work_slots ON bids.work_slot_id = work_slots.id
                LEFT JOIN user_profiles ON bids.staff_id = user_profiles.user_id 
                WHERE bids.bid_status NOT IN ('approved', 'rejected')";

        $result = $this->conn->query($sql);

        return $result;
    }
	
	   public function getBids($staffId)
    {
        $sql = "SELECT bids.id as bid_id, users.username, user_profiles.cafe_role, work_slots.id as work_slot_id, work_slots.role as work_slot_role, work_slots.work_date, bids.bid_status 
                FROM bids 
                INNER JOIN users ON bids.staff_id = users.id 
                INNER JOIN work_slots ON bids.work_slot_id = work_slots.id
                LEFT JOIN user_profiles ON bids.staff_id = user_profiles.user_id
                WHERE bids.staff_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $staffId); // 'i' represents an integer, adjust if staff_id is not an integer
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getId() {
        return $this->id;
    }

    public function getStaffId() {
        return $this->staffId;
    }

    public function getWorkSlotId() {
        return $this->workSlotId;
    }

    public function getBidStatus() {
        return $this->bidStatus;
    }
}
?>