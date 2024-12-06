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
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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

    #captcha {
      display: none;
      margin-top: 10px;
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
                <!-- CAPTCHA Section -->
                <div id="captcha">
                  <div class="g-recaptcha" data-sitekey="YOUR_GOOGLE_RECAPTCHA_SITE_KEY"></div>
                </div>
                <button type="button" class="btn btn-lg btn-success btn-block submit" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('.submit').click(function (e) {
      e.preventDefault();
      const email_address = $('input[alt="email_address"]').val();
      const user_password = $('input[alt="user_password"]').val();
      const recaptcha_response = grecaptcha.getResponse();

      $.ajax({
        type: 'POST',
        data: {
          email_address: email_address,
          user_password: user_password,
          recaptcha_response: recaptcha_response
        },
        url: 'public/login_process.php',
        success: function (data) {
          if (data.includes('CAPTCHA')) {
            $('#captcha').show(); // Show CAPTCHA
          }
          $('#msg').html(data);
        },
        error: function (data) {
          $('#msg').html(data);
        }
      });
    });
  </script>

</body>

</html>
