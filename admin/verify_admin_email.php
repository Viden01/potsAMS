<?php
require_once '../connection/db_conn.php'; // Database connection
require_once '../vendor/autoload.php'; // If using PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random 5-digit code
function generateVerificationCode() {
    return str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
}

// Disable error reporting for production
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/secure/error.log');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo '<div class="alert alert-danger">Invalid email address.</div>';
        exit();
    }

    try {
        if (!$conn) {
            throw new Exception('Database connection failed.');
        }

        // Check if the email exists for id=5
        $stmt = $conn->prepare("SELECT id FROM login_admin WHERE id = 5 AND email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo '<div class="alert alert-danger">No account found with this email address.</div>';
            exit();
        }

        // Generate a 5-digit verification code
        $verification_code = generateVerificationCode();
        $code_generated_at = date('Y-m-d H:i:s');

        // Update the database with the verification code
        $update_stmt = $conn->prepare("UPDATE login_admin SET verification_code = ?, code_generated_at = ? WHERE id = 5 AND email = ?");
        $update_stmt->bind_param("sss", $verification_code, $code_generated_at, $email);

        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update verification code in the database.");
        }

        // Send the verification code via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bsit.2s.maru.julius@gmail.com';
            $mail->Password   = 'hvdf ehhg pvpr alki';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bsit.2s.maru.julius@gmail.com', 'Verify Email');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Admin Verification Code';
            $mail->Body    = "
                <h2>Admin Verification Code</h2>
                <p>Your verification code is:</p>
                <p><strong>{$verification_code}</strong></p>
                <p>This code will expire in 10 minutes.</p>
            ";

            $mail->send();
            echo '<div class="alert alert-success">Verification code has been sent to your email.</div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">An error occurred: ' . $e->getMessage() . '</div>';
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($update_stmt)) $update_stmt->close();
        if (isset($conn)) $conn->close();
    }
} else {
    echo '<div class="alert alert-danger">Invalid request method.</div>';
    exit();
}
?>
