<?php
session_start();
include '../connection/db_conn.php';

// reCAPTCHA secret key
$recaptcha_secret = '6Le4KpUqAAAAAMe6T1Q7I-XWrstLj-ON0DW7l2Lq';

// Constants
$max_attempts = 3;
$lockout_time = 30; // in seconds

if (isset($_POST['email_address'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  

    // Initialize session for failed attempts
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = [];
    }

    // Check if the user is locked out
    $current_time = time();
    if (isset($_SESSION['failed_attempts'][$username])) {
        $attempts_data = $_SESSION['failed_attempts'][$username];
        if ($attempts_data['count'] >= $max_attempts && ($current_time - $attempts_data['last_time']) < $lockout_time) {
            $remaining_time = $lockout_time - ($current_time - $attempts_data['last_time']);
            echo '<div class="alert alert-danger">
                <strong>Too many failed login attempts. Please try again in ' . $remaining_time . ' seconds.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            exit;
        } elseif (($current_time - $attempts_data['last_time']) >= $lockout_time) {
            // Reset attempts after lockout period
            $_SESSION['failed_attempts'][$username] = ['count' => 0, 'last_time' => 0];
        }
    }

    if (isset($_POST['user_password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['user_password']);
        
        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array();

        if ($row && password_verify($password, $row["user_password"])) {
            // Successful login
            $_SESSION["user_no"] = $row["id"];
            $_SESSION["email_address"] = $row["email_address"];
            unset($_SESSION['failed_attempts'][$username]); // Reset failed attempts for user
            
            echo '<div class="alert alert-success">
                <strong>Login Successfully!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <script> setTimeout(function() { window.location.href = "private/dashboard.php" }, 1000); </script>
            </div>';
        } else {
            // Failed login
            if (!isset($_SESSION['failed_attempts'][$username])) {
                $_SESSION['failed_attempts'][$username] = ['count' => 0, 'last_time' => 0];
            }
            $_SESSION['failed_attempts'][$username]['count']++;
            $_SESSION['failed_attempts'][$username]['last_time'] = $current_time;

            if ($_SESSION['failed_attempts'][$username]['count'] >= $max_attempts) {
                echo '<div class="alert alert-warning">
                    <strong>Too many failed attempts. Complete CAPTCHA to continue.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                exit;
            } else {
                echo '<div class="alert alert-danger">
                    <strong>Invalid Email Address or Password</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }
}
?>
