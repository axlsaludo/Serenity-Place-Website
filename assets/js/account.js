document.addEventListener('DOMContentLoaded', function() {
    const createAccountLink = document.querySelector('.create-account-link');
    const card = document.querySelector('.card');
    const cardContent = card.querySelector('.card-content');

    createAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        cardContent.innerHTML = `
            <h2>Create Account</h2>
            <form id="create-account-form" action="/signup" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <input type="submit" value="Create Account">
            </form>
            <a href="#" class="back-to-login-link">Back to Login</a>
        `;
        card.classList.add('create-account');
    });

    // Handling back to login click
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('back-to-login-link')) {
            event.preventDefault();
            cardContent.innerHTML = `
                <h2>Login</h2>
                <form id="login-form" action="#" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="Login">
                </form>
                <a href="#" class="create-account-link">Create an account</a>
            `;
            card.classList.remove('create-account');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const loginCard = document.querySelector('.login-card');
    const createAccountCard = document.querySelector('.create-account-card');
    const createAccountLink = document.querySelector('.create-account');
    const backToLoginLink = document.querySelector('.back-to-login');

    createAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        loginCard.classList.add('expanded');
        createAccountCard.classList.remove('inactive');
        setTimeout(() => {
            createAccountCard.classList.add('expanded');
        }, 100); // Adjust as needed to match the transition duration
    });

    backToLoginLink.addEventListener('click', function(event) {
        event.preventDefault();
        createAccountCard.classList.remove('expanded');
        setTimeout(() => {
            createAccountCard.classList.add('inactive');
            loginCard.classList.remove('expanded');
        }, 100); // Adjust as needed to match the transition duration
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const createAccountLink = document.querySelector('.create-account');
    const createAccountCard = document.querySelector('.create-account-card');

    createAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        createAccountCard.classList.add('expanded');
    });
});
