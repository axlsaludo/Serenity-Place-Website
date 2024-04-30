// JavaScript code to trigger the animations
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
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    const checkIconName = document.getElementById('name-check');
    const checkIconUsername = document.getElementById('username-check');
    const checkIconPassword = document.getElementById('password-check');
    const checkIconConfirmPassword = document.getElementById('confirm-password-check');
    const confirmPasswordMessage = document.createElement('span'); // Create a new span element for the confirmation message

    const passwordRequirements = document.querySelector('.password-requirements');

    function checkInputs() {
        const nameValue = nameInput.value.trim();
        const usernameValue = usernameInput.value.trim();
        const passwordValue = passwordInput.value;
        const confirmPasswordValue = confirmPasswordInput.value;

        checkIconName.classList.toggle('hidden', nameValue === '');
        checkIconUsername.classList.toggle('hidden', usernameValue === '');
        checkIconConfirmPassword.classList.toggle('hidden', confirmPasswordValue === '');

        const hasLowercase = /[a-z]/.test(passwordValue);
        const hasUppercase = /[A-Z]/.test(passwordValue);
        const hasNumber = /\d/.test(passwordValue);
        const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(passwordValue);
        const isPasswordValid = passwordValue.length >= 8 && hasLowercase && hasUppercase && hasNumber && hasSpecialChar;

        checkIconPassword.classList.toggle('hidden', passwordValue === '');
        checkIconPassword.classList.toggle('invalid', passwordValue !== '' && !isPasswordValid);
        checkIconPassword.classList.toggle('valid', isPasswordValid);

        if (confirmPasswordValue !== '') {
            checkIconConfirmPassword.classList.toggle('hidden', passwordValue !== confirmPasswordValue);
            if (passwordValue === confirmPasswordValue && isPasswordValid) {
                // Passwords match and meet requirements
                checkIconConfirmPassword.classList.remove('invalid');
                checkIconConfirmPassword.classList.add('valid');
                checkIconConfirmPassword.setAttribute('title', ''); // Clear any previous message
                confirmPasswordMessage.textContent = ''; // Clear any previous message
            } else {
                // Passwords don't match or one doesn't meet requirements
                checkIconConfirmPassword.classList.remove('valid');
                checkIconConfirmPassword.classList.add('invalid');
                checkIconConfirmPassword.setAttribute('title', 'Password not matched. Please confirm your password.');
                confirmPasswordMessage.textContent = 'Password not matched. Please confirm your password.'; // Add confirmation message
            }
        } else {
            checkIconConfirmPassword.classList.add('hidden');
            confirmPasswordMessage.textContent = ''; // Clear any previous message
        }

        // Append the confirmation message to the confirm password section
        confirmPasswordInput.parentNode.appendChild(confirmPasswordMessage);

        // Show/hide password requirements with animation
        if (passwordValue !== '' && document.activeElement === passwordInput) {
            passwordRequirements.style.display = 'block';
            passwordRequirements.classList.remove('hidden');
        } else {
            passwordRequirements.classList.add('hidden');
            setTimeout(() => {
                passwordRequirements.style.display = 'none';
            }, 300); // Adjust the duration of the animation (300ms matches the animation duration)
        }

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

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        checkInputs();
        // Additional form submission handling can go here
        container.classList.remove('active');
        container.classList.add('inactive');
        setTimeout(() => {
            form.submit();
        }, 500); // Adjust as needed to match the transition duration
    });

    nameInput.addEventListener('input', checkInputs);
    usernameInput.addEventListener('input', checkInputs);
    passwordInput.addEventListener('input', checkInputs);
    confirmPasswordInput.addEventListener('input', checkInputs);

    passwordInput.addEventListener('focusout', function() {
        if (passwordInput.value === '') {
            passwordRequirements.classList.add('hidden');
            setTimeout(() => {
                passwordRequirements.style.display = 'none';
            }, 300); // Adjust the duration of the animation (300ms matches the animation duration)
        }
    });
});
