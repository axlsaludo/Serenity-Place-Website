const form = document.getElementById('signup-form');
const nameInput = document.getElementById('name');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');

const checkIconName = document.getElementById('name-check');
const checkIconUsername = document.getElementById('username-check');
const checkIconPassword = document.getElementById('password-check');
const checkIconConfirmPassword = document.getElementById('confirm-password-check');

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

    if (confirmPasswordValue !== '' && isPasswordValid) {
        checkIconConfirmPassword.classList.toggle('hidden', passwordValue !== confirmPasswordValue);
    } else {
        checkIconConfirmPassword.classList.add('hidden');
    }

    // Show/hide password requirements with animation
    if (passwordValue !== '' && document.activeElement !== confirmPasswordInput) {
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
