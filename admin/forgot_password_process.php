<?php
require_once '../connection/db_conn.php'; // Adjust the path to your database connection file
require_once '../vendor/autoload.php'; // If you're using PHPMailer, otherwise adjust as needed

// Use PHPMailer for sending emails (recommended)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to generate a secure, unique token
function generateResetToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Disable error reporting for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email input
    $email = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        echo '<div class="alert alert-danger">Invalid email address.</div>';
        exit();
    }

    try {
        // Prepare SQL to check if email exists for admin with ID 1
        $stmt = $conn->prepare("SELECT * FROM admin WHERE id = 1 AND email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo '<div class="alert alert-danger">No account found with this email address.</div>';
            exit();
        }

        // Generate a unique reset token
        $reset_token = generateResetToken();
        $reset_token_at = date('Y-m-d H:i:s');

        // Update the database with the reset token
        $update_stmt = $conn->prepare("UPDATE admin SET token = ?, reset_token_at = ? WHERE id = 1 AND email = ?");
        $update_stmt->bind_param("sss", $reset_token, $reset_token_at, $email);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update token in database.");
        }

        // Prepare the password reset link (adjust the URL as needed)
        $reset_link = "https://potsesl.com/reset_new_password.php?token=" . urlencode($reset_token);

        // Configure PHPMailer for sending email
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bsit.2s.maru.julius@gmail.com';
            $mail->Password   = 'hvdf ehhg pvpr alki';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('bsit.2s.maru.julius@gmail.com', 'Reset Password');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <h2>Password Reset Request</h2>
                <p>You have requested to reset your password. Click the link below to reset:</p>
                <p><a href='{$reset_link}'>Reset Password</a></p>
                <p>If you did not request this, please ignore this email.</p>
                <p>This link will expire in 1 hour.</p>
            ";

            $mail->send();
            
            // Successful response
            echo '<div class="alert alert-success">Password reset instructions have been sent to your email.</div>';
        } catch (Exception $e) {
            // Email sending failed
            echo '<div class="alert alert-danger">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</div>';
        }

    } catch (Exception $e) {
        // Database or other errors
        echo '<div class="alert alert-danger">An error occurred: ' . $e->getMessage() . '</div>';
    } finally {
        // Close database connections
        if (isset($stmt)) $stmt->close();
        if (isset($update_stmt)) $update_stmt->close();
        if (isset($conn)) $conn->close();
    }
} else {
    // Non-POST request
    echo '<div class="alert alert-danger">Invalid request method.</div>';
    exit();
}
?>