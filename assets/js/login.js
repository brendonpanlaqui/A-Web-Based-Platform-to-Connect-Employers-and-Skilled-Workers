document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginform");
  
    // Realtime Validation
    const email = document.getElementById('email');
    const password = document.getElementById('passwordInput');
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
    // Your form submission (keep as is but call validateForm())
    form.addEventListener('submit', async function (event) {
      event.preventDefault(); 
  
      if (!validateForm()) {
        return;
      }
  
      const formData = new FormData(form);
  
      try {
        const response = await fetch('../controllers/LoginController.php', {
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
    
  
  });
  