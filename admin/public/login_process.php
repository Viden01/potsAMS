<?php 
session_start();
include '../connection/db_conn.php';

if (isset($_POST['email_address']) && isset($_POST['user_password']) && isset($_POST['recaptcha_token'])) {
    $username = mysqli_real_escape_string($conn, $_POST['email_address']);  
    $password = mysqli_real_escape_string($conn, $_POST['user_password']);
    $recaptcha_token = $_POST['recaptcha_token'];

    // Secret Key for reCAPTCHA verification
    $secret_key = 'your_secret_key';  // Replace with your reCAPTCHA secret key

    // Verify the reCAPTCHA token with Google
    $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptcha_verify_url . "?secret=" . $secret_key . "&response=" . $recaptcha_token);
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        echo '<div class="alert alert-danger"><strong>reCAPTCHA verification failed. Please try again.</strong></div>';
    } else {
        // reCAPTCHA successful, now check login credentials
        $query = $conn->query("SELECT * FROM login_admin WHERE email_address = '$username'") or die(mysqli_error($conn));
        $row = $query->fetch_array();

        if ($row && password_verify($password, $row["user_password"])) {
            // Successful login process
            $_SESSION["user_no"] = $row["id"];
            $_SESSION["email_address"] = $row["email_address"];

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
}
?>
