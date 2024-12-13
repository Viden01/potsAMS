<?php

include '../connection/db_conn.php';
// This is the logout page when the user clicks the logout button in the system page

session_start();
date_default_timezone_set("Asia/Manila");
$time = date("M-d-Y h:i A", strtotime("+0 HOURS"));

$email = $_SESSION['email_address'];

// Update logout time in the database
$conn->query("UPDATE history_log SET `logout_time` = '$time' WHERE `id` = '$email'");

// Destroy all session data
$_SESSION = NULL;
$_SESSION = [];
session_unset();
session_destroy();

// Return success response
echo json_encode(['status' => 'success']);
exit();
?>
