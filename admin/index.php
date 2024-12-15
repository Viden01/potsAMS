<?php
require_once '../connection/db_conn.php'; // Database connection

// Security headers
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(self), microphone=()");

// Check if the URL contains a token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token from the database for id = 5
    $stmt = $conn->prepare("SELECT token FROM login_admin WHERE id = 5");
    $stmt->execute();
    $stmt->bind_result($dbToken);
    $stmt->fetch();
    $stmt->close();

    // If token does not match or is empty, redirect to two_step_verification.php
    if (empty($dbToken) || $dbToken !== $token) {
        header("Location: two_step_verification.php");
        exit();
    }
} else {
    // If no token is provided in the URL, redirect to two_step_verification.php
    header("Location: two_step_verification.php");
    exit();
}

// Redirect .php URLs to clean URLs
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POTS - ESL</title>
  <!-- Core CSS - Include with every page -->
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
  
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  
  <!-- SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <!-- reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9"></script>

  <!-- Disable right-click -->
  <script>
    document.addEventListener('contextmenu', function (e) {
      e.preventDefault();
    });
  </script>

  <style>
    /* Previous CSS remains the same */
    body {
      background-image: url('picture1.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }

    .custom-offset {
      margin-left: 63%;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .form-options a {
      font-size: 0.9em;
    }

    .login-panel {
      position: absolute;
      z-index: 1;
      left: 0;
      top: 0;
      margin: 10% auto;
      padding: 30px;
      background-color: rgba(255, 255, 255, 0.9);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      border-radius: 8px;
    }

    .panel-heading {
      text-align: center;
    }

    .panel-title {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 5px;
      padding: 15px;
      font-size: 16px;
      border: 1px solid #ddd;
      width: 100%;
    }

    .submit {
      background-color: #28a745;
      border: none;
      padding: 15px;
      font-size: 18px;
      color: white;
      width: 100%;
      cursor: pointer;
    }

    .submit:hover {
      background-color: #218838;
    }

    .back-to-login {
      text-align: center;
      margin-top: 15px;
    }

    /* Modal styles can be removed as we're using SweetAlert2 */
  </style>
</head>

<body>
  <div class="container">
    <div class="row" style="padding-top: 10%">
      <div class="col-md-4 custom-offset">
        <div class="login-panel panel panel-default" id="authPanel">
          <div class="panel-heading">
            <h3 class="panel-title" id="panelTitle">Sign In</h3>
          </div>
          <div class="panel-body">
            <div id="loginForm">
              <form role="form" id="form_action" method="POST">
                <fieldset>
                  <div class="form-group">
                    <input class="form-control" placeholder="E-mail" alt="email_address" type="email" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" placeholder="Password" alt="user_password" type="password" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>
                      <input type="checkbox" id="termsCheckbox" required>
                      I agree to the <a href="#" id="viewTerms">Terms and Conditions</a>.
                    </label>
                  </div>
                  <div class="form-options">
                    <div class="checkbox">
                      <label>
                        <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                      </label>
                    </div>
                    <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                  </div>
                  <button type="button" class="btn submit" value="Login">Login</button>
                </fieldset>
              </form>
            </div>
            <div id="forgotPasswordForm" style="display: none;">
              <form role="form" id="forgot_password_form" method="POST">
                <fieldset>
                  <div class="form-group">
                    <input class="form-control" placeholder="E-mail" alt="forgot_email" type="email" autocomplete="off" required>
                  </div>
                  <button type="button" class="btn submit" id="resetPasswordBtn">Reset Password</button>
                  <div class="back-to-login">
                    <a href="#" id="backToLoginLink">Back to Login</a>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Terms Modal Functionality (now using SweetAlert2)
      $('#viewTerms').click(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Terms and Conditions',
          html: `
            <p>[Insert your terms and conditions here.]</p>
            <p>Please read and understand the terms carefully.</p>
          `,
          icon: 'info',
          confirmButtonText: 'I Understand'
        });
      });

      // Forgot Password Form Toggle
      $('#forgotPasswordLink').click(function(e) {
        e.preventDefault();
        $('#loginForm').hide();
        $('#forgotPasswordForm').show();
        $('#panelTitle').text('Forgot Password');
      });

      // Back to Login Form
      $('#backToLoginLink').click(function(e) {
        e.preventDefault();
        $('#forgotPasswordForm').hide();
        $('#loginForm').show();
        $('#panelTitle').text('Sign In');
      });

      // Login Form Submission
      $('.submit').click(function(e) {
        e.preventDefault();
        
        // Check if we're on the login form or forgot password form
        if ($(this).closest('#loginForm').length) {
          const email_address = $('input[alt="email_address"]').val().trim();
          const user_password = $('input[alt="user_password"]').val().trim();
          const termsCheckbox = $('#termsCheckbox').is(':checked');

          if (!email_address || !user_password) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Please fill in both fields.',
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
            return;
          }

          if (!termsCheckbox) {
            Swal.fire({
              icon: 'warning',
              title: 'Terms and Conditions',
              text: 'You must agree to the terms and conditions.',
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
            return;
          }

          $.ajax({
            type: 'POST',
            url: 'public/login_process.php',
            data: { email_address, user_password },
            success: function(response) {
              if (response.includes('alert-danger')) {
                Swal.fire({
                  icon: 'error',
                  title: 'Login Failed',
                  text: 'Invalid email or password.',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });
              } else {
                Swal.fire({
                  icon: 'success',
                  title: 'Login Successful',
                  text: 'Redirecting...',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000
                }).then(() => {
                  // Add your redirection logic here
                });
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error logging in. Please try again later.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              });
            }
          });
        } 
        // Forgot Password Form Submission
        else if ($(this).closest('#forgotPasswordForm').length) {
          const forgot_email = $('input[alt="forgot_email"]').val().trim();

          if (!forgot_email) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Please enter your email address.',
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
            return;
          }

          $.ajax({
            type: 'POST',
            url: 'forgot_password_process.php',
            data: { email_address: forgot_email },
            success: function(response) {
              if (response.includes('alert-success')) {
                Swal.fire({
                  icon: 'success',
                  title: 'Password Reset',
                  text: 'Password reset instructions sent to your email.',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                }).then(() => {
                  $('#forgotPasswordForm').hide();
                  $('#loginForm').show();
                  $('#panelTitle').text('Sign In');
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Unable to process password reset.',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error processing password reset. Please try again later.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              });
            }
          });
        }
      });
    });
  </script>
</body>

</html>