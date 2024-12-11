<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    // Get POST data
    $forgot_email = $_POST['email'] ?? null;
    $recaptchaToken = $_POST['recaptchaToken'] ?? null;

    // Validate email field
    if (!$forgot_email) {
        throw new Exception("Email is required.");
    }

    // Validate reCAPTCHA if required (pseudo-code)
    if ($recaptchaToken) {
        // Verify the token with Google's reCAPTCHA API (you need to implement this)
        // Example: $recaptchaResponse = verifyRecaptcha($recaptchaToken);
        // if (!$recaptchaResponse['success']) {
        //     throw new Exception("Invalid reCAPTCHA token.");
        // }
    }

    // Simulate checking email in the database
    if ($forgot_email === 'test@example.com') { // Replace with your database query logic
        // Simulate sending reset email
        $resetToken = bin2hex(random_bytes(16)); // Generate a secure token for password reset

        // Save the token in the database with an expiry time (pseudo-code)
        // Example:
        // saveResetToken($forgot_email, $resetToken);

        // Send email (pseudo-code)
        // Example:
        // sendResetEmail($forgot_email, $resetToken);

        // Example email content
        $resetLink = "https://yourwebsite.com/reset_password.php?token=$resetToken";
        $emailMessage = "Hello,\n\nClick the link below to reset your password:\n\n$resetLink\n\nIf you didn't request this, please ignore this email.\n\nThanks,\nYour Team";

        // Use mail() for simplicity (replace with PHPMailer or a better library in production)
        $emailSent = mail($forgot_email, "Password Reset Request", $emailMessage, "From: no-reply@yourwebsite.com");

        if ($emailSent) {
            echo json_encode([
                'success' => true,
                'message' => 'Password reset instructions have been sent to your email.'
            ]);
        } else {
            throw new Exception("Failed to send email. Please try again later.");
        }
    } else {
        throw new Exception("Email not found in our records.");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
