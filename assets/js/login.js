// login.js
document.querySelectorAll('.togglePassword').forEach(item => {
    item.addEventListener('click', function() {
        const passwordInput = document.getElementById(this.dataset.target);
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        
        // Toggle the icon between eye and eye-slash
        const icon = this.querySelector('i');
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
});
