document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginform");

  // Toggle password visibility
  document.querySelectorAll('.togglePassword').forEach(btn => {
      btn.addEventListener('click', () => {
          const targetId = btn.dataset.target;
          const input = document.getElementById(targetId);
          const icon = btn.querySelector('i');

          if (input.type === 'password') {
              input.type = 'text';
              icon.classList.remove('bi-eye');
              icon.classList.add('bi-eye-slash');
          } else {
              input.type = 'password';
              icon.classList.remove('bi-eye-slash');
              icon.classList.add('bi-eye');
          }
      });
  });

  // The form will be submitted to PHP via the POST method without AJAX
  // We don't need to intercept the submit event anymore since PHP will handle the submission.
});
