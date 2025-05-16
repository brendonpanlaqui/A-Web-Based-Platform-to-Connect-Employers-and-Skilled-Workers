<?php
// config/database.php

$host = 'localhost';  
$username = 'root';      
$password = '';          
$dbname = 'job_portal';   

$con = mysqli_connect($host, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
