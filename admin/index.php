<?php
session_start();

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

// Limit login attempts and lockout functionality
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Check for lockout
if ($_SESSION['lockout_time'] > time()) {
    echo '<p class="text-danger">Account is locked. Try again later.</p>';
    exit();
}

if ($_SESSION['login_attempts'] >= 3) {
    echo '<p class="text-warning">You have exceeded the maximum number of login attempts. Please complete the CAPTCHA.</p>';
    // Trigger CAPTCHA here (in your form)
}
?>

<!DOCTYPE html>
<html lang="en">

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <!-- reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9"></script>

  <style>
    /* Add your styling for lockout and CAPTCHA */
    .submit[disabled] {
      background-color: #ccc;
      cursor: not-allowed;
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

                <!-- reCAPTCHA -->
                <div id="recaptcha" style="display: none;">
                  <div class="g-recaptcha" data-sitekey="6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9"></div>
                </div>

                <button type="button" class="btn submit" id="loginBtn" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      // Check if the user is locked out
      <?php if ($_SESSION['lockout_time'] > time()): ?>
        $('#loginBtn').prop('disabled', true);
        setTimeout(function () {
          $('#loginBtn').prop('disabled', false);
          alert("You can try logging in again.");
        }, <?php echo ($_SESSION['lockout_time'] - time()) * 1000; ?>);
      <?php endif; ?>

      $('.submit').click(function (e) {
        e.preventDefault();

        const email_address = $('input[alt="email_address"]').val().trim();
        const user_password = $('input[alt="user_password"]').val().trim();

        if (!email_address || !user_password) {
          $('#msg').html('<p class="text-danger">Please fill in both fields.</p>');
          return;
        }

        if ($_SESSION['login_attempts'] >= 3) {
          // Show reCAPTCHA after 3 failed attempts
          if (grecaptcha.getResponse() === "") {
            alert("Please complete the CAPTCHA.");
            return;
          }
        }

        $.ajax({
          type: 'POST',
          url: 'public/login_process.php',
          data: { email_address, user_password },
          success: function (response) {
            if (response === "login_failed") {
              // Increment the login attempts counter
              <?php $_SESSION['login_attempts']++; ?>
              $('#msg').html('<p class="text-danger">Invalid credentials. Try again.</p>');

              if ($_SESSION['login_attempts'] >= 3) {
                // Lock the account for 30 seconds
                <?php $_SESSION['lockout_time'] = time() + 30; ?>
                alert("You have reached the maximum number of login attempts. Please try again after 30 seconds.");
              }
            } else if (response === "login_success") {
              window.location.href = "dashboard.php"; // Redirect on successful login
            }
          },
          error: function () {
            $('#msg').html('<p class="text-danger">Error logging in. Please try again later.</p>');
          }
        });
      });
    });
  </script>

</body>

</html>
