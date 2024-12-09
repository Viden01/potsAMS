$('#forgotPasswordForm').submit(function (e) {
  e.preventDefault();
  const email = $('#forgotEmail').val().trim();
  const oldPassword = $('#oldPassword').val().trim();
  const newPassword = $('#newPassword').val().trim();

  if (!email || !oldPassword || !newPassword) {
    $('#forgotPasswordMsg').html('<p class="text-danger">Please fill in all fields.</p>');
    return;
  }

  // Request reCAPTCHA token
  grecaptcha.ready(function() {
    grecaptcha.execute('your_site_key', { action: 'submit' }).then(function(recaptchaToken) {
      // Send form data including reCAPTCHA token
      $.ajax({
        type: 'POST',
        url: 'public/reset_password_process.php',
        data: {
          forgotEmail: email,
          oldPassword: oldPassword,
          newPassword: newPassword,
          recaptchaToken: recaptchaToken
        },
        success: function(response) {
          $('#forgotPasswordMsg').html(response.message);
        },
        error: function() {
          $('#forgotPasswordMsg').html('<p class="text-danger">Error processing your request. Please try again later.</p>');
        }
      });
    });
  });
});
