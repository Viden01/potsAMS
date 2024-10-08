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

  <!-- Disable right-click -->
  <script>
    document.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  </script>

  <style>
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
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
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

<body style="background-image: url('picture1.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh;">
  <div class="container">
    <div class="row" style="padding-top: 10%">
      <div class="col-md-4 custom-offset">
        <div class="login-panel panel panel-default" id="signInPanel">
          <div class="panel-heading">
            <h3 class="panel-title">Please Sign In</h3>
          </div>
          <div class="panel-body">
            <div id="msg"></div>
            <form role="form" id="form_action" method="POST">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" alt="email_address" type="email" autocomplete="off">
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" alt="user_password" type="password" autocomplete="off">
                </div>
                <div class="form-options">
                  <div class="checkbox">
                    <label>
                      <input name="remember" type="checkbox" value="Remember Me">Remember Me
                    </label>
                  </div>
                  <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                </div>
                <button type="button" class="btn btn-lg btn-success btn-block submit" value="Login">Login</button>
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
      <p>Please enter your email and current password to reset your password.</p>
      <form id="forgotPasswordForm" method="POST">
        <div class="form-group">
          <input class="form-control" placeholder="Enter your email" type="email" name="forgot_email" required>
        </div>
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

  <script>
    var modal = document.getElementById("forgotPasswordModal");
    var btn = document.getElementById("forgotPasswordLink");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function(e) {
      e.preventDefault();
      modal.style.display = "block";
    }
    span.onclick = function() {
      modal.style.display = "none";
    }
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    $('#resetPasswordBtn').click(function(e) {
      e.preventDefault();
      var forgot_email = $('input[name="forgot_email"]').val();
      var old_password = $('input[name="old_password"]').val();
      var new_password = $('input[name="new_password"]').val();
      var confirm_password = $('input[name="confirm_password"]').val();

      if (new_password !== confirm_password) {
        alert("New passwords do not match. Please try again.");
        return;
      }

      $.ajax({
        type: 'POST',
        data: {
          forgot_email: forgot_email,
          old_password: old_password,
          new_password: new_password
        },
        url: 'public/reset_password_process.php',
        success: function(data) {
          alert('Your password has been reset successfully.');
          modal.style.display = "none";
        },
        error: function(data) {
          alert('Error resetting password. Please try again.');
        }
      });
    });

    $('.submit').click(function(e) {
      e.preventDefault();
      const email_address = $('input[alt="email_address"]').val();
      const user_password = $('input[alt="user_password"]').val();

      $.ajax({
        type: 'POST',
        data: {
          email_address: email_address,
          user_password: user_password,
        },
        url: 'public/login_process.php',
        success: function(data) {
          $('#msg').html(data);
        },
        error: function(data) {
          $('#msg').html(data);
        }
      });
    });
  </script>

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>

</html>
