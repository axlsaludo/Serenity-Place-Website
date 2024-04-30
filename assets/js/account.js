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
