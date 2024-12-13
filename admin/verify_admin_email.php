<?php
require_once '../connection/db_conn.php'; // Adjust to your database connection file
require_once '../vendor/autoload.php'; // Adjust if using Composer for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Helper function to generate a 5-digit random number
function generateVerificationCode() {
    return str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit();
    }

    try {
        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT * FROM login_admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'No account found with this email address.']);
            exit();
        }

        // Generate a 5-digit verification code
        $verificationCode = generateVerificationCode();

        // Save the code in the database
        $updateStmt = $conn->prepare("UPDATE login_admin SET code = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $verificationCode, $email);

        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update the verification code in the database.");
        }

        // Send the verification code via email
        $mail = new PHPMailer(true);

        try {
            // Configure PHPMailer
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bsit.2s.maru.julius@gmail.com';
            $mail->Password   = 'hvdf ehhg pvpr alki'; // Ensure this is securely managed
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email details
            $mail->setFrom('bsit.2s.maru.julius@gmail.com', 'Admin Verification');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = "<p>Your verification code is: <strong>{$verificationCode}</strong></p>";

            $mail->send();

            echo json_encode(['status' => 'success', 'message' => 'Verification code sent successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    } finally {
        // Close database connections
        $stmt->close();
        if (isset($updateStmt)) {
            $updateStmt->close();
        }
        $conn->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
