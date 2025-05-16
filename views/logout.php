<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or home page
header("Location: ../views/login.php"); // Or redirect to index.php if preferred
exit();
?>
