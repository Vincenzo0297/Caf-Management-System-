<?php

class ViewUser
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

	public function getUsers()
    {
        return $this->user->getAllUsers();
    }

    // Add other methods related to user operations

    private function redirect($location)
    {
        header("Location: $location");
        exit;
    }
}

?>