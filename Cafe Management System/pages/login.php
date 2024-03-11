<?php
require_once('../classes/LoginPage.php');
include('../config/db_config.php');
include('../classes/User.php');
$page = new LoginPage($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->processLogin();
}
$page->render();
?>