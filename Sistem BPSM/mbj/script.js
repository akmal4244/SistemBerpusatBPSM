document.getElementById('loginForm').addEventListener('submit', function(event) {
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    // Simple client-side validation
    if (username === '' || password === '') {
        event.preventDefault();
        document.getElementById('errorMessage').textContent = 'All fields are required.';
    }
});
