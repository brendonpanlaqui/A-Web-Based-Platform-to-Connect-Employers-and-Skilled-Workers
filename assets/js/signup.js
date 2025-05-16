document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("signupform");

  const firstname = document.getElementById('first_name');
  const lastname = document.getElementById('last_name');
  const email = document.getElementById('email');
  const password = document.getElementById('passwordInput');
  const passwordConfirmation = document.getElementById('passwordConfirmation');
  const role = document.getElementById('type');

  function formatName(name) {
    return name.trim().toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
  }

  firstname.addEventListener('blur', () => {
    firstname.value = formatName(firstname.value);
  });

  lastname.addEventListener('blur', () => {
    lastname.value = formatName(lastname.value);
  });

  firstname.addEventListener('input', validateFirstName);
  lastname.addEventListener('input', validateLastName);
  email.addEventListener('input', validateEmailField);
  password.addEventListener('input', validatePassword);
  passwordConfirmation.addEventListener('input', validatePasswordConfirmation);
  role.addEventListener('input', validateRole);

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

  // Modified validation functions to return true/false
  function validateFirstName() {
    const error = document.getElementById('first_name_error');
    if (firstname.value.trim() === '') {
      showError(error, 'This is a required field.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

  function validateLastName() {
    const error = document.getElementById('last_name_error');
    if (lastname.value.trim() === '') {
      showError(error, 'This is a required field.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

  function validateEmailField() {
    const error = document.getElementById('email_error');
    if (email.value.trim() === '') {
      showError(error, 'This is a required field.');
      return false;
    } else if (!isValidEmail(email.value)) {
      showError(error, 'Please use a valid email address.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

  function validatePassword() {
    const error = document.getElementById('password_error');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&^_-]).+$/;
    if (password.value === '') {
      showError(error, 'This is a required field.');
      return false;
    } else if (password.value.length < 8 || password.value.length > 16) {
      showError(error, 'Password must be 8-16 characters.');
      return false;
    } else if (!passwordRegex.test(password.value)) {
      showError(error, 'Password must include uppercase, lowercase, number, and special character.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

  function validatePasswordConfirmation() {
    const error = document.getElementById('password_confirmation_error');
    if (passwordConfirmation.value === '') {
      showError(error, 'This is a required field.');
      return false;
    } else if (password.value !== passwordConfirmation.value) {
      showError(error, 'Passwords do not match.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

  function validateRole() {
    const error = document.getElementById('select_error');
    if (role.value === '') {
      showError(error, 'This is a required field.');
      return false;
    } else {
      showSuccess(error, 'Valid input.');
      return true;
    }
  }

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

  function isValidEmail(email) {
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.(com|net|org|ph|com\.ph|edu\.ph|gov\.ph)$/;
  return emailPattern.test(email.toLowerCase());
  }


  // Fixed validateForm that checks booleans
  function validateForm() {
    const firstNameValid = validateFirstName();
    const lastNameValid = validateLastName();
    const emailValid = validateEmailField();
    const passwordValid = validatePassword();
    const passwordConfirmationValid = validatePasswordConfirmation();
    const roleValid = validateRole();

    return (
      firstNameValid &&
      lastNameValid &&
      emailValid &&
      passwordValid &&
      passwordConfirmationValid &&
      roleValid
    );
  }

  form.addEventListener('submit', async function (event) {
    event.preventDefault();

    if (!validateForm()) {
      return; // stop submission
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
        console.error('Server error(s):', data.errors);
        alert(data.errors.join('\n'));
      }
    } catch (error) {
      console.error('Error during form submission:', error);
    }
  });
});
