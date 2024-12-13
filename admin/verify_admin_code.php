<?php
require_once '../connection/db_conn.php'; // Adjust to your database connection file

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $verificationCode = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_STRING);

    if (!$email || !$verificationCode) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or verification code.']);
        exit();
    }

    try {
        // Check if the email and code match in the database
        $stmt = $conn->prepare("SELECT * FROM login_admin WHERE email = ? AND code = ?");
        $stmt->bind_param("ss", $email, $verificationCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid verification code or email.']);
            exit();
        }

        // Generate a new token
        $newToken = bin2hex(random_bytes(16));
        
        // Update the token and clear the code in the database
        $updateStmt = $conn->prepare("UPDATE login_admin SET token = ?, code = NULL WHERE email = ?");
        $updateStmt->bind_param("ss", $newToken, $email);
        if (!$updateStmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update token.']);
            exit();
        }

        // Respond with success, redirect URL, and new token
        echo json_encode([
            'status' => 'success',
            'redirect' => 'index.php',
            'token' => $newToken
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    } finally {
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
