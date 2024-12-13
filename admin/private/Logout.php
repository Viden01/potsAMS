<?php

include '../connection/db_conn.php';
// This is the logout page when the user clicks the logout button in the system page.

session_start();
date_default_timezone_set("Asia/Manila");
$time = date("M-d-Y h:i A", strtotime("+0 HOURS"));

// Retrieve the user's email address from the session.
$email = $_SESSION['email_address'];

// Update the logout time in the history_log table for the corresponding user ID.
$conn->query("UPDATE history_log SET `logout_time` = '$time' WHERE `id` = '$email'");

// Clear session data and destroy the session.
$_SESSION = NULL;
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to the index page with an alert.
echo "<script type='text/javascript'>alert('LogOut Successfully!');
document.location='../index.php'</script>";

?>
