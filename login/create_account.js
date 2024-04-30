document.addEventListener('DOMContentLoaded', function() {
    // Find the container and description elements
    const container = document.querySelector('.container');
    const description = container.querySelector('h2');

    // Add event listener to the description to toggle animation
    description.addEventListener('click', function() {
        container.classList.toggle('active');
    });

    // Add event listener to the form submission to toggle container animation
    const form = document.getElementById('signup-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const nameInput = document.getElementById('name');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const checkIconName = document.getElementById('name-check');
        const checkIconUsername = document.getElementById('username-check');
        const checkIconPassword = document.getElementById('password-check');
        const checkIconConfirmPassword = document.getElementById('confirm-password-check');
        const confirmPasswordMessage = document.createElement('span');
        const passwordRequirements = document.querySelector('.password-requirements');

        // Additional form submission handling can go here
        checkInputs();

        container.classList.remove('active');
        container.classList.add('inactive');
        setTimeout(() => {
            container.classList.remove('inactive');
            container.classList.add('expanded');
        }, 1000); // Adjust as needed to match the transition duration

        setTimeout(() => {
            form.submit();
        }, 1500); // Adjust as needed to match the transition duration

        function checkInputs() {
            const nameValue = nameInput.value.trim();
            const usernameValue = usernameInput.value.trim();
            const passwordValue = passwordInput.value;
            const confirmPasswordValue = confirmPasswordInput.value;

            // Your existing code for input validation goes here...

            // Update password requirement icons
            updateRequirementIcon(document.querySelector('.length .icon'), passwordValue.length >= 8);
            updateRequirementIcon(document.querySelector('.lowercase .icon'), hasLowercase);
            updateRequirementIcon(document.querySelector('.uppercase .icon'), hasUppercase);
            updateRequirementIcon(document.querySelector('.number .icon'), hasNumber);
            updateRequirementIcon(document.querySelector('.special-character .icon'), hasSpecialChar);

            // Hide password requirements if all requirements are met
            if (isPasswordValid && document.activeElement !== confirmPasswordInput) {
                passwordRequirements.classList.add('hidden');
                setTimeout(() => {
                    passwordRequirements.style.display = 'none';
                }, 300);
            }
        }

        function updateRequirementIcon(iconElement, isValid) {
            iconElement.classList.remove('invalid', 'valid');
            if (isValid) {
                iconElement.classList.add('valid');
            } else {
                iconElement.classList.add('invalid');
            }
        }
    });

    // Add input event listeners to trigger input validation
    const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            checkInputs();
        });
    });

    // Add focusout event listener to hide password requirements when focus is lost
    const passwordInput = document.getElementById('password');
    passwordInput.addEventListener('focusout', function() {
        if (passwordInput.value === '') {
            passwordRequirements.classList.add('hidden');
            setTimeout(() => {
                passwordRequirements.style.display = 'none';
            }, 300); // Adjust the duration of the animation (300ms matches the animation duration)
        }
    });
});
