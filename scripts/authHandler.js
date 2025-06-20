const loginForm = document.getElementById('loginForm')
const loginResponse = document.getElementById('loginResponse')

const signupForm = document.getElementById('signupForm')
const signupResponse = document.getElementById('signupResponse')

loginForm.addEventListener('submit', handleLogin)
signupForm.addEventListener('submit', handleSignup);

async function handleLogin(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const username = formData.get('username');
    const password = formData.get('password');
    const email = formData.get('email');

    loginResponse.style.color = 'firebrick';
    loginResponse.textContent = '';

    try {
        if (!username) throw { message: 'Username is required.' };
        if (username.length < 4 || username.length > 20) {
            throw { message: 'Username must be 4-15 characters long.' };
        }
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            throw { message: 'Username can only contain letters, numbers, and underscores.' };
        }

        // Password checks
        if (!password) throw { message: 'Password is required.' };
        if (password.length < 6) {
            throw { message: 'Password must be at least 6 characters long.' };
        }
        if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
            throw { message: 'Password must contain both letters and numbers.' };
        }

        const response = await fetch('./utils/loginController.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json()

        if (!response.ok) throw new Error(result.message);


        loginResponse.style.color = 'green'
        loginResponse.textContent = result.message;

        loginResponse.classList.remove('show');
        void loginResponse.offsetWidth; // Force reflow
        loginResponse.classList.add('show');

        setTimeout(() => {
            window.location.href = './home.php'
        }, 150)

    } catch (error) {
        loginResponse.textContent = error.message;
        loginResponse.classList.remove('show');
        void loginResponse.offsetWidth; // Force reflow
        loginResponse.classList.add('show');
    }
}

async function handleSignup(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const username = formData.get('username')?.trim();
    const password = formData.get('password')?.trim();
    const email = formData.get('email')?.trim();

    signupResponse.style.color = 'firebrick';
    signupResponse.textContent = '';

    try {
        // Username checks
        if (!username) throw { message: 'Username is required.' };
        if (username.length < 4 || username.length > 20) {
            throw { message: 'Username must be 4-15 characters long.' };
        }
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            throw { message: 'Username can only contain letters, numbers, and underscores.' };
        }

        // Email checks
        if (!email) throw { message: 'Email is required.' };
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            throw { message: 'Email format is invalid.' };
        }

        // Password checks
        if (!password) throw { message: 'Password is required.' };
        if (password.length < 6) {
            throw { message: 'Password must be at least 6 characters long.' };
        }
        if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
            throw { message: 'Password must contain both letters and numbers.' };
        }


        const response = await fetch('./utils/signupController.php', {
            method: 'POST',
            body: formData,
        })

        const result = await response.json()

        if (!response.ok) throw new Error(result.message);

        signupResponse.style.color = 'green'
        signupResponse.textContent = result.message;
        e.target.reset();
    } catch (error) {
        signupResponse.textContent = error.message || 'Signup failed.';
        signupResponse.classList.remove('show');
        void signupResponse.offsetWidth;
        signupResponse.classList.add('show');
    }
}