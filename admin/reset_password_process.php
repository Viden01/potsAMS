<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security headers
header('Content-Type: application/json');
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Database connection
require_once 'db_connection.php';

// Function to generate a secure reset token
function generateResetToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Response function
function sendResponse($success, $message) {
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Receive and sanitize input
$email = isset($_POST['email_address']) ? trim($_POST['email_address']) : '';

// Validate email
if (empty($email)) {
    sendResponse(false, 'Email address is required');
}

if (!isValidEmail($email)) {
    sendResponse(false, 'Invalid email format');
}

try {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 0) {
        // Security best practice: Don't reveal if email exists
        sendResponse(true, 'If an account exists with this email, a reset link will be sent');
    }

    // Fetch user details
    $user = $result->fetch_assoc();

    // Generate reset token
    $reset_token = generateResetToken();
    $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Prepare statement to update reset token
    $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $reset_token, $token_expiry, $user['id']);
    
    if (!$update_stmt->execute()) {
        sendResponse(false, 'Error generating reset token');
    }

    // Construct reset link (replace with your actual domain)
    $reset_link = "https://yourwebsite.com/reset_password.php?token=" . $reset_token;

    // Prepare email content
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "You have requested to reset your password. Click the link below to reset:\n\n" . $reset_link . 
               "\n\nThis link will expire in 1 hour.\n\nIf you did not request this, please ignore this email.";
    
    // Additional email headers
    $headers = 'From: noreply@yourwebsite.com' . "\r\n" .
        'Reply-To: noreply@yourwebsite.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Send email (use PHP's mail function or a library like PHPMailer)
    if (mail($to, $subject, $message, $headers)) {
        sendResponse(true, 'Password reset instructions sent to your email');
    } else {
        sendResponse(false, 'Error sending reset email');
    }

} catch (Exception $e) {
    // Log the full error in your server logs
    error_log($e->getMessage());
    sendResponse(false, 'An unexpected error occurred');
} finally {
    // Close statements and connection
    if (isset($stmt)) $stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    $conn->close();
}