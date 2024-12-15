<?php
session_start();
include '../connection/db_conn.php';

// reCAPTCHA secret key
$recaptcha_secret = '6Le4KpUqAAAAAMe6T1Q7I-XWrstLj-ON0DW7l2Lq';

// Constants
$max_attempts = 3;
$lockout_time = 30; // in seconds

if (isset($_POST['recaptcha_token'])) {
    $recaptcha_token = $_POST['recaptcha_token'];

    // Verify reCAPTCHA token
    $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_token}");
    $response_data = json_decode($verify_response);

    if (!$response_data->success || $response_data->score < 0.5) {
        echo 'recaptcha_failed'; // Response for failed reCAPTCHA verification
        exit;
    }
}

if (isset($_POST['email_address'])) {
    $username = $_POST['email_address'];  

    // Initialize session for failed attempts
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = 0;
    }
    if (!isset($_SESSION['last_failed_attempt'])) {
        $_SESSION['last_failed_attempt'] = time();
    }

    // Check if user has exceeded the maximum number of failed attempts
    if ($_SESSION['failed_attempts'] >= $max_attempts && time() - $_SESSION['last_failed_attempt'] < $lockout_time) {
        $remaining_time = $lockout_time - (time() - $_SESSION['last_failed_attempt']);
        echo "locked_out,{$remaining_time}"; // Return lockout message
        exit;
    }

    // Login process
    if (isset($_POST['user_password'])) {
        $password = $_POST['user_password'];

        // Use prepared statements to retrieve user data
        $stmt = $conn->prepare("SELECT * FROM login_admin WHERE email_address = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["user_password"])) {
                // Successful login process
                $_SESSION["user_no"] = $row["id"];
                $_SESSION["email_address"] = $row["email_address"];
                $_SESSION['failed_attempts'] = 0; // Reset failed attempts on success

                echo 'login_successful'; // Response for successful login
            } else {
                // Increment failed attempts
                $_SESSION['failed_attempts']++;
                $_SESSION['last_failed_attempt'] = time();

                echo 'invalid_credentials'; // Invalid credentials response
            }
        } else {
            // No user found
            $_SESSION['failed_attempts']++;
            $_SESSION['last_failed_attempt'] = time();

            echo 'invalid_credentials'; // Invalid credentials response
        }

        // Close the statement
        $stmt->close();
    }
}
?>
