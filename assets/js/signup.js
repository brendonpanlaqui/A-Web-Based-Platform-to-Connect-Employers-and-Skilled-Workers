document.addEventListener("DOMContentLoaded",function(){
    const form= document.getElementById("signupform");

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

      
    form.addEventListener('submit',async function(event){
        event.preventDefault();
        event.stopPropagation();

        if(!form.checkValidity()){
            form.classList.add('was-validated');
            return;
        }
        else{
            const data={
                firstname: form.firstname.value.trim(),
                lastname: form.lastname.value.trim(),
                email: form.email.value.trim(),
                password: form.password.value.trim(),
                type: form.type.value
            };
            try{
                const response= await fetch('https://your-backend.com/api/signup', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result= await response.json();

                if(response.ok){
                    if(data.type=='employer'){
                        window.location.href = 'employerdashboard.html';
                    }
                    else{
                        window.location.href = 'employeedashboard.html';
                    }
                }
                else{
                    alert(result.message || 'Signup failed. Try again.');
                }
            }
            catch(error){
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            }
        }
    });
})