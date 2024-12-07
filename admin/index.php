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
  
  <!-- Google reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=your_site_key"></script>

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
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 15px;
      font-size: 16px;
      border: 1px solid #ddd;
      width: 100%;
      transition: all 0.3s ease;
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
                <button type="button" class="btn submit" id="loginBtn" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    grecaptcha.ready(function() {
      $('#loginBtn').click(function(e) {
        e.preventDefault();

        // Get reCAPTCHA token
        grecaptcha.execute('your_site_key', {action: 'login'}).then(function(token) {
          const email_address = $('input[alt="email_address"]').val();
          const user_password = $('input[alt="user_password"]').val();

          $.ajax({
            type: 'POST',
            data: {
              email_address: email_address,
              user_password: user_password,
              recaptcha_token: token, // Send the token
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
      });
    });
  </script>
</body>

</html>
