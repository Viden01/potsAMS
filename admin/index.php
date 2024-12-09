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
  <link href="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <style>
    body {
      background-image: url('picture1.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    .login-panel {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 100%;
      max-width: 400px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .submit {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .submit:hover {
      background-color: #218838;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 500px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .alert {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-panel">
      <h2 style="text-align: center;">Sign In</h2>
      <div id="msg"></div>
      <form id="loginForm">
        <input type="email" class="form-control" id="email" placeholder="Email" required>
        <input type="password" class="form-control" id="password" placeholder="Password" required>
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
          <label>
            <input type="checkbox"> Remember Me
          </label>
          <a href="#" id="forgotPasswordLink">Forgot Password?</a>
        </div>
        <button type="button" class="submit" id="loginBtn">Login</button>
      </form>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Forgot Password</h3>
      <div id="forgotPasswordMsg"></div>
      <form id="forgotPasswordForm">
        <input type="email" class="form-control" id="forgotEmail" placeholder="Enter your email" required>
        <button type="button" class="submit" id="resetPasswordBtn">Reset Password</button>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Login Button Click
      $('#loginBtn').click(function() {
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();

        if (!email || !password) {
          $('#msg').html('<div class="alert alert-danger">Please fill in both email and password.</div>');
          return;
        }

        $.ajax({
          type: 'POST',
          url: 'login_process.php',
          data: { email: email, password: password },
          success: function(response) {
            $('#msg').html(response);
          },
          error: function() {
            $('#msg').html('<div class="alert alert-danger">Error logging in. Please try again.</div>');
          }
        });
      });

      // Forgot Password Modal Functionality
      const forgotPasswordModal = document.getElementById('forgotPasswordModal');
      const forgotPasswordLink = document.getElementById('forgotPasswordLink');
      const closeModal = document.getElementsByClassName('close')[0];

      // Open Modal
      forgotPasswordLink.onclick = function(e) {
        e.preventDefault();
        forgotPasswordModal.style.display = 'block';
      }

      // Close Modal
      closeModal.onclick = function() {
        forgotPasswordModal.style.display = 'none';
      }

      // Close Modal when clicking outside of it
      window.onclick = function(event) {
        if (event.target == forgotPasswordModal) {
          forgotPasswordModal.style.display = 'none';
        }
      }

      // Reset Password Button Click
      $('#resetPasswordBtn').click(function() {
        const forgotEmail = $('#forgotEmail').val().trim();

        if (!forgotEmail) {
          $('#forgotPasswordMsg').html('<div class="alert alert-danger">Please enter your email address.</div>');
          return;
        }

        $.ajax({
          type: 'POST',
          url: 'reset_password_process.php',
          data: { email_address: forgotEmail },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              $('#forgotPasswordMsg').html('<div class="alert alert-success">' + response.message + '</div>');
              $('#forgotEmail').val('');
              setTimeout(function() {
                $('#forgotPasswordModal').fadeOut();
              }, 3000);
            } else {
              $('#forgotPasswordMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
          },
          error: function(xhr, status, error) {
            let errorMessage = 'Error processing your request.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 0) {
              errorMessage = 'No connection. Please check your network.';
            } else if (xhr.status === 404) {
              errorMessage = 'Requested page not found.';
            } else if (xhr.status === 500) {
              errorMessage = 'Internal server error.';
            }

            $('#forgotPasswordMsg').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            console.error('AJAX Error:', status, error);
          }
        });
      });
    });
  </script>
</body>
</html>