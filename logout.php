<?php
session_start();  // Start the session

// Destroy the session
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to the login page (or home page)
header("Location: index.html");  // You can change this to wherever you want to redirect the user after logout
exit();
?>
