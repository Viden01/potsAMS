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
        echo '<div class="alert alert-danger">
            <strong>Failed reCAPTCHA verification. Please try again.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>';
        exit;
    }
}

if (isset($_POST['email_address'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  

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
        echo "<div class='alert alert-warning'>
            <strong>You have reached the maximum number of login attempts. Please try again in {$remaining_time} seconds.</strong>
        </div>";
        exit;
    }

    // Login process
    if (isset($_POST['user_password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['user_password']);
        
        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array(MYSQLI_ASSOC); // Specify associative array

        if ($row && password_verify($password, $row["user_password"])) {
            // Successful login process
            $_SESSION["user_no"] = $row["id"];
            $_SESSION["email_address"] = $row["email_address"];
            $_SESSION['failed_attempts'] = 0; // Reset failed attempts on success

            echo '<div class="alert alert-success">
                <strong>Login Successfully!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <script> setTimeout(function() { window.location.href = "private/dashboard.php" }, 1000); </script>
            </div>';
        } else {
            // Increment failed attempts
            $_SESSION['failed_attempts']++;
            $_SESSION['last_failed_attempt'] = time();

            echo '<div class="alert alert-danger">
                <strong>Invalid Email Address or Password</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        }
    }
}
?>
