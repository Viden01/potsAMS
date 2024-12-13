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
    <title>Admin Login - Two-Step Verification</title>
    <link href="private/assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="private/assets/css/style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
        }

        #codeSection {
            display: none;
        }

        #errorMsg {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div id="emailSection">
            <h2 class="text-center mb-4">Admin Login</h2>
            <div id="errorMsg"></div>
            <form id="emailForm">
                <input type="email" class="form-control" id="adminEmail" placeholder="Enter Gmail Address" required>
                <button type="submit" class="btn btn-primary">Continue</button>
            </form>
        </div>

        <div id="codeSection">
            <h2 class="text-center mb-4">Verification Code</h2>
            <div id="errorMsg2"></div>
            <form id="codeForm">
                <input type="text" class="form-control" id="verificationCode" placeholder="Enter 6-digit Code" required>
                <button type="submit" class="btn btn-primary">Verify</button>
                <div class="text-center mt-3">
                    <a href="#" id="resendCode">Resend Code</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Email Form Submission
        $('#emailForm').on('submit', function(e) {
            e.preventDefault();
            const email = $('#adminEmail').val().trim();

            // First, validate it's a Gmail address
            if (!email.endsWith('@gmail.com')) {
                $('#errorMsg').text('Please use a valid Gmail address');
                return;
            }

            // AJAX call to verify email in login_admin table
            $.ajax({
                type: 'POST',
                url: 'verify_admin_email.php',
                data: { email: email },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#emailSection').hide();
                        $('#codeSection').show();
                    } else {
                        $('#errorMsg').text(response.message || 'Email not found');
                    }
                },
                error: function() {
                    $('#errorMsg').text('Error verifying email. Please try again.');
                }
            });
        });

        // Code Verification Form Submission
        $('#codeForm').on('submit', function(e) {
            e.preventDefault();
            const email = $('#adminEmail').val().trim();
            const code = $('#verificationCode').val().trim();

            $.ajax({
                type: 'POST',
                url: 'verify_admin_code.php',
                data: { 
                    email: email, 
                    verification_code: code 
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Redirect to admin dashboard
                        window.location.href = 'admin_dashboard.php';
                    } else {
                        $('#errorMsg2').text(response.message || 'Invalid verification code');
                    }
                },
                error: function() {
                    $('#errorMsg2').text('Error verifying code. Please try again.');
                }
            });
        });

        // Resend Code Feature
        $('#resendCode').on('click', function(e) {
            e.preventDefault();
            const email = $('#adminEmail').val().trim();

            $.ajax({
                type: 'POST',
                url: 'resend_verification_code.php',
                data: { email: email },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('New verification code sent to your email');
                    } else {
                        $('#errorMsg2').text(response.message || 'Failed to resend code');
                    }
                },
                error: function() {
                    $('#errorMsg2').text('Error resending code. Please try again.');
                }
            });
        });
    });
    </script>
</body>
</html>