document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editPasswordForm");

  const password = document.getElementById('new_password');
  const passwordConfirmation = document.getElementById('new_password_confirmation');
  const contactNumber = document.getElementById("contact_number");

  password.addEventListener('input', validatePassword);
  contactNumber.addEventListener('input', validateContactNumber);
  passwordConfirmation.addEventListener('input', validatePasswordConfirmation);

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

  function validatePassword() {
    const error = document.getElementById('new_password_error');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&^_-]).+$/;
    if (password.value === '') {
      error.innerText = '';
      error.classList.remove('text-danger', 'text-success');
      return true;
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
    const error = document.getElementById('new_password_confirmation_error');
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

  function validateContactNumber() {
    let error = document.getElementById('contact_number_error');

    const val = contactNumber.value.trim();
    const validFormat = /^\d{9}$/; // Exactly 9 digits, no +639 prefix

    if (val === '') {
      error.textContent = '';  // Optional field, no error if empty
      contactNumber.setCustomValidity('');
      return true;
    } else if (!validFormat.test(val)) {
      error.textContent = 'Contact number must be exactly 9 digits.';
      contactNumber.setCustomValidity('Invalid contact number format.');
      return false;
    } else {
      error.textContent = '';
      contactNumber.setCustomValidity('');
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

  function validateForm() {
    const passwordValid = validatePassword();
    const passwordConfirmationValid = validatePasswordConfirmation();
    const contactValid = validateContactNumber();
    return passwordValid && passwordConfirmationValid && contactValid;
  }

  form.addEventListener('submit', function(event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });
});
