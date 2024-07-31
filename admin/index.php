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
  <style>
    .custom-offset {
      margin-left: 63%; /* Adjust this percentage to fine-tune the position */
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
                <div class="checkbox">
                  <label>
                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                  </label>
                </div>
                <button type="button" class="btn btn-lg btn-success btn-block submit" value="Login">Login</button>
              </fieldset>
            </form>
            <hr>
            <div class="text-center">
              <a href="#" id="showCreateAccount">Create an Account</a>
            </div>
          </div>
        </div>

        <div class="login-panel panel panel-default" id="createAccountPanel" style="display:none;">
          <div class="panel-heading">
            <h3 class="panel-title">Create Account</h3>
          </div>
          <div class="panel-body">
            <div id="createMsg"></div>
            <form role="form" id="create_form_action" method="POST">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" alt="create_email_address" type="email" autocomplete="off">
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" alt="create_user_password" type="password" autocomplete="off">
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Confirm Password" alt="confirm_user_password" type="password" autocomplete="off">
                </div>
                <button type="button" class="btn btn-lg btn-primary btn-block createSubmit" value="Create Account">Create Account</button>
              </fieldset>
            </form>
            <hr>
            <div class="text-center">
              <a href="#" id="showSignIn">Sign In</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#showCreateAccount').click(function() {
        $('#signInPanel').hide();
        $('#createAccountPanel').show();
      });

      $('#showSignIn').click(function() {
        $('#createAccountPanel').hide();
        $('#signInPanel').show();
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

      $('.createSubmit').click(function(e) {
        e.preventDefault();
        const email_address = $('input[alt="create_email_address"]').val();
        const user_password = $('input[alt="create_user_password"]').val();
        const confirm_password = $('input[alt="confirm_user_password"]').val();

        if (user_password !== confirm_password) {
          $('#createMsg').html('Passwords do not match.');
          return;
        }

        $.ajax({
          type: 'POST',
          data: {
            email_address: email_address,
            user_password: user_password,
          },
          url: 'public/create_account_process.php',
          success: function(data) {
            $('#createMsg').html(data);
          },
          error: function(data) {
            $('#createMsg').html(data);
          }
        });
      });
    });
  </script>

  <script src="assets/plugins/jquery-1.10.2.js"></script>
  <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>

</html>
