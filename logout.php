<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: http://localhost/sucadministrationsystem/index.php");
exit(); // Terminate script execution after the redirect
?>



