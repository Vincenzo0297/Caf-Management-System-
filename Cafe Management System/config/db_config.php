<?php

$hostname = 'localhost';              
$username = "root";          
$password = "";              
$database = "simcatfe"; 
$port = '3306';      


$conn = new mysqli($hostname, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

