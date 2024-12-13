<?php

include '../connection/db_conn.php'; // Ensure this path is correct.

session_start();
date_default_timezone_set("Asia/Manila");
$time = date("M-d-Y h:i A", strtotime("+0 HOURS"));

// Check if session variable exists.
if (isset($_SESSION['email_address'])) {
    $email = $_SESSION['email_address'];

    // Use a prepared statement for security.
    $stmt = $conn->prepare("UPDATE history_log SET `logout_time` = ? WHERE `id` = ?");
    $stmt->bind_param("ss", $time, $email);

    if ($stmt->execute()) {
        // Log out user.
        $_SESSION = [];
        session_unset();
        session_destroy();

        // Redirect with success message.
        echo "<script type='text/javascript'>
            alert('LogOut Successfully!');
            document.location='../index.php';
        </script>";
    } else {
        // Handle query failure.
        echo "<script type='text/javascript'>
            alert('Error updating logout time. Please try again.');
            document.location='../index.php';
        </script>";
    }
    $stmt->close();
} else {
    // Handle missing session email.
    echo "<script type='text/javascript'>
        alert('Session not found. Please log in again.');
        document.location='../index.php';
    </script>";
}

$conn->close();
?>
