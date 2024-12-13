<?php
// Start the session
session_start();

// Destroy all session data
session_unset();  // Unset all session variables

// Destroy the session
session_destroy();  // Destroy the session itself

// Redirect to the homepage or login page
header("Location: ../index.php");  // Change 'index.php' to the page you want to redirect to
exit();
?>
