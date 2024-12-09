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
    $forgot_email = $_POST['forgot_email'] ?? null;
    $old_password = $_POST['old_password'] ?? null;
    $new_password = $_POST['new_password'] ?? null;
    $recaptchaToken = $_POST['recaptchaToken'] ?? null;

    // Validate required fields
    if (!$forgot_email || !$old_password || !$new_password) {
        throw new Exception("Missing required fields.");
    }

    // Simulate password reset logic (replace with actual implementation)
    if ($forgot_email === 'test@example.com' && $old_password === '123456') {
        // Simulate success
        echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
    } else {
        throw new Exception("Invalid email or password.");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
