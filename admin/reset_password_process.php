<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'config.php'; // Include database connection and settings (make sure this exists)
require_once 'recaptcha.php'; // Include reCAPTCHA verification logic

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    // Get POST data
    $forgot_email = $_POST['email'] ?? null; // Align with frontend email field
    $recaptchaToken = $_POST['recaptchaToken'] ?? null;

    // Validate required fields
    if (!$forgot_email || !$recaptchaToken) {
        throw new Exception("Missing required fields.");
    }

    // Verify reCAPTCHA
    if (!verifyRecaptcha($recaptchaToken)) {
        throw new Exception("reCAPTCHA verification failed.");
    }

    // Connect to the database
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->execute(['email' => $forgot_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("No user found with this email.");
    }

    // Generate a password reset token (you can store it in a reset_tokens table or similar)
    $reset_token = bin2hex(random_bytes(32)); // Random 32-byte token for the reset link

    // Store the reset token (in a reset_tokens table or update the user record with it)
    $stmt = $pdo->prepare("UPDATE users SET reset_token = :reset_token WHERE email = :email");
    $stmt->execute(['reset_token' => $reset_token, 'email' => $forgot_email]);

    // Send reset email (you can implement your own email system here)
    $reset_link = "https://yourdomain.com/reset-password.php?token=" . $reset_token;

    // Send the reset link via email (using PHP's mail function or a third-party email service)
    mail($forgot_email, "Password Reset Request", "Click the link to reset your password: $reset_link");

    // Return success message
    echo json_encode(['success' => true, 'message' => 'Password reset email sent.']);
} catch (Exception $e) {
    // Return error message
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
