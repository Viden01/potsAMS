<?php 
session_start();
include '../connection/db_conn.php';

// Configuration
$max_attempts = 3; // Maximum failed attempts before CAPTCHA
$lockout_time = 30 * 60; // Lockout time in seconds (30 minutes)
$recaptcha_secret = "YOUR_GOOGLE_RECAPTCHA_SECRET_KEY"; // Replace with your reCAPTCHA secret key

// Initialize session variables if not set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

// Check lockout status
if ($_SESSION['failed_attempts'] >= $max_attempts && time() - $_SESSION['lockout_time'] < $lockout_time) {
    echo '<div class="alert alert-danger">
        <strong>Your account is temporarily locked. Please try again later.</strong>
        </div>';
    exit;
}

// Reset lockout after the lockout time has passed
if (time() - $_SESSION['lockout_time'] > $lockout_time) {
    $_SESSION['failed_attempts'] = 0;
}

if (isset($_POST['email_address'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  

    if (isset($_POST['user_password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['user_password']);

        // Verify CAPTCHA if failed attempts exceed limit
        if ($_SESSION['failed_attempts'] >= $max_attempts) {
            $recaptcha_response = $_POST['recaptcha_response'] ?? '';
            $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
            $response = file_get_contents($recaptcha_url . "?secret=$recaptcha_secret&response=$recaptcha_response");
            $responseKeys = json_decode($response, true);

            if (!$responseKeys['success']) {
                echo '<div class="alert alert-danger">
                    <strong>CAPTCHA verification failed. Please try again.</strong>
                    </div>';
                exit;
            }
        }

        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array();

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
            $_SESSION['failed_attempts']++;
            if ($_SESSION['failed_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time();
                echo '<div class="alert alert-danger">
                    <strong>CAPTCHA required for further login attempts.</strong>
                    </div>';
            } else {
                echo '<div class="alert alert-danger">
                    <strong>Invalid Email Address or Password.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }

    // Password Reset Section (unchanged)
    if (isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            echo '<div class="alert alert-danger">
                <strong>New passwords do not match. Please try again.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        } else {
            $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
            $row = $query->fetch_array();

            if ($row && password_verify($old_password, $row["user_password"])) {
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $conn->query("UPDATE login_admin SET user_password = '$new_password_hashed' WHERE email_address = '$username'") or die(mysqli_error($conn));

                echo '<div class="alert alert-success">
                    <strong>Password has been successfully reset!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                echo '<div class="alert alert-danger">
                    <strong>Old password is incorrect.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }
}
?>
