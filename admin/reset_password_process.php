<?php
require_once '../connection/db_conn.php';
require_once '../vendor/autoload.php';

// Disable error reporting for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs using modern methods
    $reset_token = isset($_POST['reset_token']) ? trim($_POST['reset_token']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

    // Validate inputs
    if (empty($reset_token) || empty($new_password)) {
        echo '<div class="alert alert-danger">Invalid input. Please try again.</div>';
        exit();
    }

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Prepare statement with parameterized query for token validation
        $token_stmt = $conn->prepare("
            SELECT id, email, reset_token_at 
            FROM login_admin 
            WHERE token = ? AND id = 5
        ");
        $token_stmt->bind_param("s", $reset_token);
        $token_stmt->execute();
        $token_result = $token_stmt->get_result();

        if ($token_result->num_rows === 0) {
            echo '<div class="alert alert-danger">Invalid or expired reset token.</div>';
            exit();
        }

        $admin = $token_result->fetch_assoc();

        // Check token expiration (1 hour validity)
        $reset_time = strtotime($admin['reset_token_at']);
        $current_time = time();
        if ($current_time - $reset_time > 3600) { // 1 hour = 3600 seconds
            echo '<div class="alert alert-danger">Reset token has expired. Please request a new reset link.</div>';
            exit();
        }

        // Hash the new password using bcrypt
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password and clear token
        $update_stmt = $conn->prepare("
            UPDATE login_admin 
            SET password = ?, 
                token = '', 
                reset_token_at = NULL 
            WHERE id = 5 AND token = ?
        ");
        $update_stmt->bind_param("ss", $hashed_password, $reset_token);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update password.");
        }

        // Commit transaction
        $conn->commit();

        // Successful password reset
        echo '<div class="alert alert-success">Password successfully reset. Redirecting to login...</div>';

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();

        // Log the error (consider using a proper logging mechanism)
        error_log("Password Reset Error: " . $e->getMessage());

        // Generic error message to user
        echo '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
    } finally {
        // Close statements and connection
        if (isset($token_stmt)) $token_stmt->close();
        if (isset($update_stmt)) $update_stmt->close();
        if (isset($conn)) $conn->close();
    }
} else {
    // Non-POST request
    echo '<div class="alert alert-danger">Invalid request method.</div>';
    exit();
}
?>