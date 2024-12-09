<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get POST data
        $forgot_email = $_POST['forgot_email'] ?? null;
        $old_password = $_POST['old_password'] ?? null;
        $new_password = $_POST['new_password'] ?? null;
        $recaptchaToken = $_POST['recaptchaToken'] ?? null;

        // Simulated logic for debugging
        if (!$forgot_email || !$old_password || !$new_password) {
            throw new Exception("Missing required fields.");
        }

        // Output success for testing
        echo json_encode(['success' => true, 'message' => 'Test successful.']);
    } else {
        throw new Exception("Invalid request method.");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
