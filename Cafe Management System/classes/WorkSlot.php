<?php
class WorkSlot {
    private $id;
    private $cafeOwnerId;
    private $role;
    private $assignedStaffIds;
    private $workDate;
    private $workSlotLimit;

    public function __construct($id, $cafeOwnerId, $role, $assignedStaffIds, $workDate, $workSlotLimit) {
        $this->id = $id;
        $this->cafeOwnerId = $cafeOwnerId;
        $this->role = $role;
        $this->assignedStaffIds = $assignedStaffIds;
        $this->workDate = $workDate;
        $this->workSlotLimit = $workSlotLimit;
    }

    public function getId() {
        return $this->id;
    }

    public function getCafeOwnerId() {
        return $this->cafeOwnerId;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAssignedStaffIds() {
        return $this->assignedStaffIds;
    }

    public function getWorkDate() {
        return $this->workDate;
    }

    public function getWorkSlotLimit() {
        return $this->workSlotLimit;
    }

    // Implement getOwnerId() method
    public function getOwnerId() {
        return $this->cafeOwnerId;
    }

    // Implement getOwnerUsernameById($ownerId) method
    public static function getOwnerUsernameById($ownerId, $conn) {
        // Prepare and execute SQL query to fetch owner's username
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param("i", $ownerId);
    
        // Execute the query
        $stmt->execute();
        $stmt->bind_result($ownerUsername);
        $stmt->fetch();
        $stmt->close();
        return $ownerUsername;
    }
    
}
?>
