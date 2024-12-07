<?php 
session_start();
include '../connection/db_conn.php';

if (isset($_POST['email_address'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  

    // reCAPTCHA verification
    if (isset($_POST['recaptcha_token'])) {
        $recaptcha_secret = '6Le4KpUqAAAAAMe6T1Q7I-XWrstLj-ON0DW7l2Lq
'; // Replace with your reCAPTCHA secret key
        $recaptcha_token = $_POST['recaptcha_token'];

        // Verify reCAPTCHA with Google's API
        $recaptcha_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_token");
        $recaptcha_result = json_decode($recaptcha_response, true);

        if (!$recaptcha_result['success']) {
            echo '<div class="alert alert-danger">
                    <strong>reCAPTCHA verification failed. Please try again.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            exit;
        }
    }

    if (isset($_POST['user_password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['user_password']);
        
        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array();

        if ($row && password_verify($password, $row["user_password"])) {
            // Successful login process
            $_SESSION["user_no"] = $row["id"];
            $_SESSION["email_address"] = $row["email_address"];
            
            // Logging user actions (omitted for brevity)

            echo '<div class="alert alert-success">
                <strong>Login Successfully!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <script> setTimeout(function() { window.location.href = "private/dashboard.php" }, 1000); </script>
            </div>';
        } else {
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
?>
