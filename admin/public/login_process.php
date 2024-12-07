<?php 
session_start();
include '../connection/db_conn.php';

// Initialize or update failed attempts counter
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if (!isset($_SESSION['last_failed_attempt'])) {
    $_SESSION['last_failed_attempt'] = time();
}

// Lock account for 10 minutes if 3 failed attempts
if ($_SESSION['failed_attempts'] >= 3 && (time() - $_SESSION['last_failed_attempt']) < 600) {
    echo '<div class="alert alert-danger">
            <strong>Your account is locked. Please try again after 10 minutes.</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    exit;
}

if (isset($_POST['email_address'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  

    if (isset($_POST['user_password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['user_password']);
        
        // Check if CAPTCHA is required
        if ($_SESSION['failed_attempts'] >= 3 && empty($_POST['g-recaptcha-response'])) {
            echo '<div class="alert alert-danger">
                    <strong>Please complete the CAPTCHA to continue.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
            exit;
        }

        // Verify CAPTCHA
        if ($_SESSION['failed_attempts'] >= 3 && !verify_captcha($_POST['g-recaptcha-response'])) {
            echo '<div class="alert alert-danger">
                    <strong>Invalid CAPTCHA. Please try again.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
            exit;
        }

        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array();

        if ($row && password_verify($password, $row["user_password"])) {
            // Successful login
            $_SESSION["user_no"] = $row["id"];
            $_SESSION["email_address"] = $row["email_address"];
            
            // Reset failed attempts on successful login
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['last_failed_attempt'] = time();

            // Logging user actions (omitted for brevity)

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

    // Password Reset Section
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

// Function to verify CAPTCHA
function verify_captcha($captcha_response) {
    $secret_key = 'YOUR_SECRET_KEY';
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($verify_url . '?secret=' . $secret_key . '&response=' . $captcha_response);
    $response_keys = json_decode($response, true);
    return $response_keys['success'];
}
?>
