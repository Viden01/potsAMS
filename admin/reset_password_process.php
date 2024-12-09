<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set content type to JSON
header('Content-Type: application/json');
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");

// Database connection (replace with your actual connection details)
$host = 'localhost';
$dbuser = 'your_username';
$dbpass = 'your_password';
$dbname = 'your_database';

// Create connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit;
}

// Receive input
$email = isset($_POST['email_address']) ? trim($_POST['email_address']) : '';

// Validate email
if (empty($email)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email address is required.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format.'
    ]);
    exit;
}

// Function to generate reset token
function generateResetToken($length = 32) {
    return bin2hex(random_bytes($length));
}

try {
    // Prepare statement to check email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If no user found
    if ($result->num_rows == 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'If an account exists with this email, reset instructions will be sent.'
        ]);
        exit;
    }

    // Get user ID
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Generate reset token
    $reset_token = generateResetToken();
    $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Prepare statement to update reset token
    $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $reset_token, $token_expiry, $user_id);
    
    if (!$update_stmt->execute()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error generating reset token.'
        ]);
        exit;
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

    // Attempt to send email
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Password reset instructions sent to your email.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error sending reset email.'
        ]);
    }

} catch (Exception $e) {
    // Log the full error in your server logs
    error_log($e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An unexpected error occurred.'
    ]);
} finally {
    // Close statements and connection
    if (isset($stmt)) $stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    $conn->close();
}
?>