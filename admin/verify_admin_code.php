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

        // If matched, reset the code column (optional for security)
        $resetCodeStmt = $conn->prepare("UPDATE login_admin SET code = NULL WHERE email = ?");
        $resetCodeStmt->bind_param("s", $email);
        $resetCodeStmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Verification successful.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    } finally {
        $stmt->close();
        if (isset($resetCodeStmt)) {
            $resetCodeStmt->close();
        }
        $conn->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
