<?php
// Allow Cross-Origin Requests (if needed)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $forgot_email = $_POST['forgot_email'] ?? null;
    $old_password = $_POST['old_password'] ?? null;
    $new_password = $_POST['new_password'] ?? null;
    $recaptchaToken = $_POST['recaptchaToken'] ?? null;

    // Validate inputs
    if (!$forgot_email || !$old_password || !$new_password || !$recaptchaToken) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Verify reCAPTCHA (if applicable)
    $recaptchaSecret = 'YOUR_RECAPTCHA_SECRET_KEY';
    $recaptchaResponse = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaToken"
    );
    $recaptchaData = json_decode($recaptchaResponse, true);

    if (!$recaptchaData['success']) {
        echo json_encode(['success' => false, 'message' => 'reCAPTCHA validation failed.']);
        exit;
    }

    // Simulate password reset logic (replace with actual database checks)
    if ($forgot_email === 'test@example.com' && $old_password === 'correct_password') {
        // Update password in the database (dummy logic here)
        echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
