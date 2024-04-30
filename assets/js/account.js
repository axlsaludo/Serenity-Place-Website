document.addEventListener('DOMContentLoaded', function() {
    const createAccountLink = document.querySelector('.create-account-link');
    const createAccountPopup = document.querySelector('.create-account-popup');
    const closePopup = document.querySelector('.close-popup');
    const body = document.querySelector('body');

    // Show create account pop-up
    createAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        createAccountPopup.style.display = 'block';
        body.classList.add('create-account-active'); // Apply the class to dim or blur the background
    });

    // Close create account pop-up
    closePopup.addEventListener('click', function(event) {
        event.preventDefault();
        createAccountPopup.style.display = 'none';
        body.classList.remove('create-account-active'); // Remove the class to restore the background
    });
});
