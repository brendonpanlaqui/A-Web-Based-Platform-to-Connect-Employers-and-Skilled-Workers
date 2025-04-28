document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("signupform");

  // Realtime Validation
  const firstname = document.getElementById('first_name');
  const lastname = document.getElementById('last_name');
  const email = document.getElementById('email');
  const password = document.getElementById('passwordInput');
  const passwordConfirmation = document.getElementById('passwordConfirmation');
  const role = document.getElementById('type');

  firstname.addEventListener('input', validateFirstName);
  lastname.addEventListener('input', validateLastName);
  email.addEventListener('input', validateEmailField);
  password.addEventListener('input', validatePassword);
  passwordConfirmation.addEventListener('input', validatePasswordConfirmation);
  role.addEventListener('input', validateRole);

  // Toggle password visibility (your existing code)
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

  // Validate individual fields
  function validateFirstName() {
    const error = document.getElementById('first_name_error');
    if (firstname.value.trim() === '') {
      showError(error, 'This is a required field.');
    } else {
      showSuccess(error, 'Valid input.');
    }
  }

  function validateLastName() {
    const error = document.getElementById('last_name_error');
    if (lastname.value.trim() === '') {
      showError(error, 'This is a required field.');
    } else {
      showSuccess(error, 'Valid input.');
    }
  }

  function validateEmailField() {
    const error = document.getElementById('email_error');
    if (email.value.trim() === '') {
      showError(error, 'This is a required field.');
    } else if (!validateEmail(email.value)) {
      showError(error, 'Please use a valid email address.');
    } else {
      showSuccess(error, 'Valid input.');
    }
  }

  function validatePassword() {
    const error = document.getElementById('password_error');
    if (password.value === '') {
      showError(error, 'This is a required field.');
    } else if (password.value.length < 8 || password.value.length > 16) {
      showError(error, 'Password must be 8-16 characters.');
    } else {
      showSuccess(error, 'Valid input.');
    }
    validatePasswordConfirmation(); // Always revalidate confirmation too
  }

  function validatePasswordConfirmation() {
    const error = document.getElementById('password_confirmation_error');
    if (passwordConfirmation.value === '') {
      showError(error, 'This is a required field.');
    } else if (password.value !== passwordConfirmation.value) {
      showError(error, 'Passwords do not match.');
    } else {
      showSuccess(error, 'Valid input.');
    }
  }

  function validateRole() {
    const error = document.getElementById('select_error');
    if (role.value === '') {
      showError(error, 'This is a required field.');
    } else {
      showSuccess(error, 'Valid input.');
    }
  }

  // Helpers
  function showError(element, message) {
    element.innerText = message;
    element.classList.remove('text-success');
    element.classList.add('text-danger');
  }

  function showSuccess(element, message) {
    element.innerText = message;
    element.classList.remove('text-danger');
    element.classList.add('text-success');
  }

  function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailPattern.test(email);
  }

  // Your form submission (keep as is but call validateForm())
  form.addEventListener('submit', async function (event) {
    event.preventDefault(); 

    if (!validateForm()) {
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch('../controllers/RegisterController.php', {
          method: 'POST',
          body: formData,
      });

      const data = await response.json();

      if (data.success) {
          window.location.href = data.redirectUrl;
      } else {
          console.error('Server error:', data.error);
      }
    } catch (error) {
      console.error('Error during form submission:', error);
    }
  });

  function validateForm() {
    validateFirstName();
    validateLastName();
    validateEmailField();
    validatePassword();
    validatePasswordConfirmation();
    validateRole();
  
    const errorElements = document.querySelectorAll('span[id$="_error"]'); // all error spans
    let valid = true;
  
    errorElements.forEach(error => {
      if (error.classList.contains('text-danger') && error.innerText !== 'Valid input.') {
        valid = false;
      }
    });
  
    return valid;
  }
  

});
