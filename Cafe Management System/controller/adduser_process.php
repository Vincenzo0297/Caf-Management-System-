<?php
include('../config/session_start.php');
include('../config/db_config.php');
require_once('../classes/User.php');

class adduser_process {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function processAddUser($username, $password, $email, $phone, $type) {
        $user = new User($this->conn);

        // Add user logic here
        $addResult = $user->addUser($username, $password, $email, $phone, $type);

        if ($addResult === true) {
            $_SESSION['registration_success'] = true;
        } else {
            $_SESSION['registration_error'] = $addResult;
        }

        $this->redirect("../pages/adduser.php");
    }

    private function redirect($location) {
        header("Location: $location");
        exit;
    }
}


?>