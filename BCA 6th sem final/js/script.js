function validateLogin() {
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;
    const error = document.getElementById('login-error');

    if (!email || !password) {
        error.textContent = 'Please fill in all fields';
        return false;
    }

    error.textContent = '';
    return true;
}

function validateRegister() {
    const name = document.getElementById('register-name').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;
    const error = document.getElementById('register-error');

    if (!name || !email || !password) {
        error.textContent = 'Please fill in all fields';
        return false;
    }

    error.textContent = '';
    return true;
}
