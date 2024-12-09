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
    $forgot_email = $_POST['forgot_email'] ?? null;
    $old_password = $_POST['old_password'] ?? null;
    $new_password = $_POST['new_password'] ?? null;
    $recaptchaToken = $_POST['recaptchaToken'] ?? null;

    // Validate required fields
    if (!$forgot_email || !$old_password || !$new_password || !$recaptchaToken) {
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
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
    $stmt->execute(['email' => $forgot_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("No user found with this email.");
    }

    // Verify old password (use password_hash() and password_verify() for secure password checking)
    if (!password_verify($old_password, $user['password'])) {
        throw new Exception("Incorrect old password.");
    }

    // Validate new password (you can set your own password strength rules here)
    if (strlen($new_password) < 8) {
        throw new Exception("New password must be at least 8 characters long.");
    }

    // Hash the new password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the user's password in the database
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->execute(['password' => $hashed_password, 'email' => $forgot_email]);

    // Return success message
    echo json_encode(['success' => true, 'message' => 'Password reset successful.']);

} catch (Exception $e) {
    // Return error message
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

