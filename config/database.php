<?php
// config/database.php

$host = 'localhost';      // Database host (could also be 127.0.0.1)
$username = 'root';       // Database username
$password = '';           // Database password
$dbname = 'job_portal';   // Database name (change to your actual DB name)

// Create a connection
$con = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
