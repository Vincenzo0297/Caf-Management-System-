<?php
//include('../config/session_start.php');
include('../config/db_config.php');
include('../classes/User.php');

class suspend_user_process
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function suspendForUser($userId)
    {
        $user = new User($this->conn);

        $result = $user->suspendUser($userId);

    }
}
?>
