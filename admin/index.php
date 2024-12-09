<?php
// Security headers
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(self), microphone=()");

$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

session_start();

// Initialize failed attempts counter if not already set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_address = $_POST['email_address'];
    $user_password = $_POST['user_password'];

    // Check if account is locked
    if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3) {
        echo "<p class='text-danger'>Your account is locked due to multiple failed login attempts. Please try again later.</p>";
        exit();
    }

    // Simulate user credentials validation (replace with your actual validation)
    $is_valid_login = validate_user_credentials($email_address, $user_password);

    if ($is_valid_login) {
        $_SESSION['failed_attempts'] = 0; // Reset failed attempts after successful login
        echo "<p class='text-success'>Login successful!</p>";
    } else {
        $_SESSION['failed_attempts']++; // Increment failed attempts counter
        // Only show the locked account warning if attempts are 3 or more, no count message
        if ($_SESSION['failed_attempts'] >= 3) {
            echo "<p class='text-danger'>Your account is locked due to multiple failed login attempts. Please try again later.</p>";
            exit();
        }
    }
}

function show_recaptcha() {
    echo '<div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>';
}

function validate_user_credentials($email, $password) {
    // Replace with actual user credential validation
    $valid_email = "user@example.com";
    $valid_password = "password123"; // In real use, hash passwords and compare securely.

    return ($email === $valid_email && $password === $valid_password);
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POTS - ESL</title>
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <!-- reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9"></script>

  <style>
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
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 15px;
      font-size: 16px;
      border: 1px solid #ddd;
      width: 100%;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 8px rgba(40, 167, 69, 0.2);
    }

    .submit {
      background-color: #28a745;
      border: none;
      border-radius: 5px;
      padding: 15px;
      font-size: 18px;
      color: white;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .submit:hover {
      background-color: #218838;
    }

    .checkbox label {
      font-size: 0.9em;
      color: #333;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row" style="padding-top: 10%">
      <div class="col-md-4 custom-offset">
        <div class="login-panel panel panel-default" id="signInPanel">
          <div class="panel-heading">
            <h3 class="panel-title">Sign In</h3>
          </div>
          <div class="panel-body">
            <div id="msg"></div>
            <form role="form" id="form_action" method="POST">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" alt="email_address" type="email" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" alt="user_password" type="password" autocomplete="off" required>
                </div>
                <div class="form-options">
                  <div class="checkbox">
                    <label>
                      <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                    </label>
                  </div>
                  <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                </div>
                <button type="submit" class="btn submit" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function executeRecaptcha(action, callback) {
      grecaptcha.ready(function () {
        grecaptcha.execute('6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9', { action: action }).then(function (token) {
          callback(token);
        });
      });
    }

    $('.submit').click(function (e) {
      e.preventDefault();
      const email_address = $('input[alt="email_address"]').val().trim();
      const user_password = $('input[alt="user_password"]').val().trim();

      if (!email_address || !user_password) {
        $('#msg').html('<p class="text-danger">Please fill in both fields.</p>');
        return;
      }

      $.ajax({
        type: 'POST',
        url: '',
        data: { email_address, user_password },
        success: function (response) {
          $('#msg').html(response);
        },
        error: function () {
          $('#msg').html('<p class="text-danger">Error logging in. Please try again later.</p>');
        }
      });
    });
  </script>

</body>

</html>
