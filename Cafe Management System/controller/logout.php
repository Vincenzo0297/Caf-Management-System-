<?php
include('../config/session_start.php');
session_destroy();
header("Location: ../pages/login.php");
exit;
?>
