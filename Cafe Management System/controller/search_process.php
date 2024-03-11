<?php

class search_process {
    private $user;
    private $conn;

    public function __construct($conn) {
		$this->conn = $conn;
		$this->user = new User($this->conn); // Initialize the User entity
		$this->profile = new UserProfile($this->conn); // Initialize the UserProfile entity
	}

    public function searchUsers($searchTerm) {
    // Call the search function from the User entity class
		return $this->user->searchUserByUsername($searchTerm);
	}

	public function searchProfile($searchTerm) {
		// Call the search function from the UserProfile class
		return $this->profile->searchProfileByname($searchTerm);
	}


}
?>