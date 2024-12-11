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
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POTS - ESL</title>
  <!-- Core CSS -->
  <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="private/assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
  <link href="private/assets/css/style.css" rel="stylesheet" />
  <link href="private/assets/css/main-style.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

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

    .form-options {
      text-align: right;
    }

    .form-options a {
      font-size: 0.9em;
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

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 400px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row" style="padding-top: 10%">
      <div class="col-md-4 custom-offset">
        <div class="login-panel panel panel-default">
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
                  <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                </div>
                <button type="button" class="btn submit" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeForgotPasswordModal">&times;</span>
      <h2>Forgot Password</h2>
      <p>Enter your email address to reset your password:</p>
      <input type="email" id="resetEmail" class="form-control" placeholder="E-mail" required>
      <button class="btn submit" id="resetPasswordBtn">Submit</button>
      <div id="resetMsg"></div>
    </div>
  </div>

  <script>
    // Modal handling
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const closeForgotPasswordModal = document.getElementById('closeForgotPasswordModal');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');

    forgotPasswordLink.addEventListener('click', function (e) {
      e.preventDefault();
      forgotPasswordModal.style.display = 'block';
    });

    closeForgotPasswordModal.addEventListener('click', function () {
      forgotPasswordModal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
      if (e.target === forgotPasswordModal) {
        forgotPasswordModal.style.display = 'none';
      }
    });

    // Handle password reset
    resetPasswordBtn.addEventListener('click', function () {
      const email = document.getElementById('resetEmail').value.trim();
      if (!email) {
        document.getElementById('resetMsg').innerHTML = '<p class="text-danger">Please enter your email.</p>';
        return;
      }

      $.ajax({
        type: 'POST',
        url: 'reset_password_process.php', // Backend endpoint
        data: { email },
        success: function (response) {
          const res = JSON.parse(response);
          if (res.success) {
            document.getElementById('resetMsg').innerHTML = '<p class="text-success">' + res.message + '</p>';
          } else {
            document.getElementById('resetMsg').innerHTML = '<p class="text-danger">' + res.message + '</p>';
          }
        },
        error: function () {
          document.getElementById('resetMsg').innerHTML = '<p class="text-danger">Error sending reset instructions. Try again later.</p>';
        }
      });
    });
  </script>
</body>

</html>
