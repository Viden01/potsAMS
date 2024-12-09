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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <!-- reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=6Le4KpUqAAAAAEvYzCj1R_cz4IMSvMGdPpQ9vmy9"></script>

  <!-- Disable right-click -->
  <script>
    document.addEventListener('contextmenu', function (e) {
      e.preventDefault();
    });
  </script>

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

    /* Modal Styles */
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
      max-width: 500px;
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
                <button type="button" class="btn submit" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- The Modal -->
  <div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Forgot Password</h2>
      <p>Please enter your Gmail account to begin the password reset process.</p>
      <form id="forgotPasswordForm" method="POST">
        <div class="form-group">
          <input class="form-control" placeholder="Enter your Gmail" type="email" name="forgot_email" required>
        </div>
        <button type="button" class="btn btn-primary" id="nextStepBtn">Next</button>
      </form>
      <div id="passwordResetSection" style="display: none;">
        <p>Please enter your old password and new password.</p>
        <form id="resetPasswordForm" method="POST">
          <div class="form-group">
            <input class="form-control" placeholder="Enter old password" type="password" name="old_password" required>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Enter new password" type="password" name="new_password" required>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Confirm new password" type="password" name="confirm_password" required>
          </div>
          <button type="button" class="btn btn-primary" id="resetPasswordBtn">Submit</button>
        </form>
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

    const modal = document.getElementById("forgotPasswordModal");
    const btn = document.getElementById("forgotPasswordLink");
    const span = document.getElementsByClassName("close")[0];
    const nextStepBtn = document.getElementById("nextStepBtn");
    const passwordResetSection = document.getElementById("passwordResetSection");

    btn.onclick = function (e) {
      e.preventDefault();
      modal.style.display = "block";
    };

    span.onclick = function () {
      modal.style.display = "none";
    };

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    };

    nextStepBtn.onclick = function () {
      const forgot_email = $('input[name="forgot_email"]').val().trim();
      
      // Check if the entered email is a valid Gmail address
      const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
      if (!gmailRegex.test(forgot_email)) {
        alert("Please enter a valid Gmail address.");
        return;
      }

      // Proceed to show the password reset form
      $('#forgotPasswordForm').hide();
      passwordResetSection.style.display = "block";
    };

    $('#resetPasswordBtn').click(function (e) {
      e.preventDefault();

      const old_password = $('input[name="old_password"]').val().trim();
      const new_password = $('input[name="new_password"]').val().trim();
      const confirm_password = $('input[name="confirm_password"]').val().trim();

      if (!old_password || !new_password || !confirm_password) {
        alert("All fields are required.");
        return;
      }

      if (new_password !== confirm_password) {
        alert("New passwords do not match. Please try again.");
        return;
      }

      executeRecaptcha('reset_password', function (recaptchaToken) {
        $.ajax({
          type: 'POST',
          url: 'public/reset_password_process.php',
          data: {
            forgot_email: forgot_email,
            old_password: old_password,
            new_password: new_password,
            recaptchaToken: recaptchaToken
          },
          beforeSend: function () {
            $('#resetPasswordBtn').text('Processing...').prop('disabled', true);
          },
          success: function (response) {
            try {
              const data = JSON.parse(response);
              if (data.success) {
                alert('Your password has been reset successfully.');
                modal.style.display = "none";
              } else {
                alert(data.message || 'Error resetting password.');
              }
            } catch (err) {
              alert('Unexpected response from server.');
            }
          },
          error: function (xhr, status, error) {
            alert(`Error: ${xhr.status} - ${xhr.statusText}. Details: ${error}`);
            console.log(xhr.responseText);
          },
          complete: function () {
            $('#resetPasswordBtn').text('Submit').prop('disabled', false);
          }
        });
      });
    });

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
        url: 'public/login_process.php',
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

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
</body>

</html>
