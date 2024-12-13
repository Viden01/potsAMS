<?php
// Security headers
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(self), microphone=()");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POTS - ESL | Reset Password</title>
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

    .password-requirements {
      font-size: 0.8em;
      color: #666;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row" style="padding-top: 10%">
      <div class="col-md-4 custom-offset">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Reset Password</h3>
          </div>
          <div class="panel-body">
            <div id="msg"></div>
            <form role="form" id="reset_password_form" method="POST">
              <input type="hidden" id="reset_token" name="reset_token">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" 
                         placeholder="New Password" 
                         type="password" 
                         id="new_password" 
                         name="new_password" 
                         autocomplete="new-password" 
                         required>
                  <div class="password-requirements">
                    Password must be:
                    <ul>
                      <li>At least 8 characters long</li>
                      <li>Contain at least one uppercase letter</li>
                      <li>Contain at least one lowercase letter</li>
                      <li>Contain at least one number</li>
                      <li>Contain at least one special character</li>
                    </ul>
                  </div>
                </div>
                <div class="form-group">
                  <input class="form-control" 
                         placeholder="Confirm New Password" 
                         type="password" 
                         id="confirm_password" 
                         name="confirm_password" 
                         autocomplete="new-password" 
                         required>
                </div>
                <button type="button" class="btn submit" id="resetPasswordBtn">Reset Password</button>
              </fieldset>
            </form>
          </div>  
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Extract token from URL
      const urlParams = new URLSearchParams(window.location.search);
      const resetToken = urlParams.get('token');
      
      if (!resetToken) {
        $('#msg').html('<div class="alert alert-danger">Invalid or missing reset token.</div>');
        $('.submit').prop('disabled', true);
      } else {
        $('#reset_token').val(resetToken);
      }

      // Password validation function
      function validatePassword(password) {
        const requirements = [
          password.length >= 8,
          /[A-Z]/.test(password),
          /[a-z]/.test(password),
          /[0-9]/.test(password),
          /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        ];
        return requirements.every(req => req);
      }

      // Reset password button click handler
      $('#resetPasswordBtn').click(function(e) {
        e.preventDefault();
        
        const newPassword = $('#new_password').val().trim();
        const confirmPassword = $('#confirm_password').val().trim();
        const resetToken = $('#reset_token').val().trim();

        // Clear previous messages
        $('#msg').html('');

        // Validate inputs
        if (!newPassword || !confirmPassword) {
          $('#msg').html('<div class="alert alert-danger">Please fill in both password fields.</div>');
          return;
        }

        if (newPassword !== confirmPassword) {
          $('#msg').html('<div class="alert alert-danger">Passwords do not match.</div>');
          return;
        }

        // Validate password strength
        if (!validatePassword(newPassword)) {
          $('#msg').html('<div class="alert alert-danger">Password does not meet requirements.</div>');
          return;
        }

        // AJAX call to reset password
        $.ajax({
          type: 'POST',
          url: 'reset_password_process.php',
          data: {
            reset_token: resetToken,
            new_password: newPassword
          },
          success: function(response) {
            $('#msg').html(response);
            
            // If successful, redirect to login after a few seconds
            if (response.includes('alert-success')) {
              setTimeout(function() {
                window.location.href = 'login.php';
              }, 3000);
            }
          },
          error: function() {
            $('#msg').html('<div class="alert alert-danger">Error processing password reset. Please try again.</div>');
          }
        });
      });
    });
  </script>
</body>
</html>