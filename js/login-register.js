document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('container');
    
    // Desktop buttons
    const registerBtn = document.getElementById('registerBtn');
    const loginBtn = document.getElementById('loginBtn');
    
    // Mobile links
    const mobileRegisterBtn = document.getElementById('mobileRegisterBtn');
    const mobileLoginBtn = document.getElementById('mobileLoginBtn');

    function showRegister() {
        container.classList.add("active");
    }

    function showLogin() {
        container.classList.remove("active");
    }

    // Event listeners
    if (registerBtn) registerBtn.addEventListener('click', showRegister);
    if (loginBtn) loginBtn.addEventListener('click', showLogin);
    if (mobileRegisterBtn) mobileRegisterBtn.addEventListener('click', showRegister);
    if (mobileLoginBtn) mobileLoginBtn.addEventListener('click', showLogin);

    // Password toggle logic
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');
    togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const passwordInput = icon.parentElement.querySelector('.password-input');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.src = 'images/icons/eye-off.svg';
            } else {
                passwordInput.type = 'password';
                icon.src = 'images/icons/eye.svg';
            }
        });
    });
});