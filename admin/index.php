<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login - POTS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="private/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: #6c63ff;
            border: none;
        }

        .btn-primary:hover {
            background: #4a4ae6;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card p-4" style="max-width: 400px; margin: auto;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Login</h3>
                <div id="msg" class="alert d-none"></div>
                <form id="loginForm" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-options mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                        <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // AJAX login
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            const email = $('#email').val();
            const password = $('#password').val();

            $.ajax({
                url: 'public/login_process.php',
                type: 'POST',
                data: { email, password },
                success: function(response) {
                    $('#msg').removeClass('d-none').addClass('alert-success').text('Login successful!');
                    setTimeout(() => {
                        window.location.href = "dashboard.php";
                    }, 1000);
                },
                error: function(err) {
                    $('#msg').removeClass('d-none').addClass('alert-danger').text('Invalid credentials. Please try again.');
                }
            });
        });

        // Forgot password modal
        $('#forgotPasswordLink').on('click', function(e) {
            e.preventDefault();
            alert("Redirect to forgot password functionality.");
        });
    </script>
</body>

</html>
