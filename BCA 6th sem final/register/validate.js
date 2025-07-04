function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email.trim());
}

function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    return phoneRegex.test(phone.trim());
}

// Strong Password Validation (Min 8 chars, Uppercase, Lowercase, Number, Special Char)
function validatePasswordStrength(password) {
    // At least one uppercase, one lowercase, one number, one special char, min 8 chars
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return passwordRegex.test(password);
}

// Check if Passwords Match
function validatePasswordMatch(password, confirmPassword) {
    return password === confirmPassword;
}

$(document).ready(function () {
    // Create error message elements if they don't exist
    $('.input-group').each(function() {
        if (!$(this).find('.error-message').length) {
            $(this).append('<div class="error-message" style="color: red; font-size: 0.8em; margin-top: 5px; display: none;"></div>');
        }
    });

    function showError(inputId, message) {
        $(`#${inputId}`).addClass('error');
        $(`#${inputId}`).next('.error-message').text(message).show();
    }

    function clearError(inputId) {
        $(`#${inputId}`).removeClass('error');
        $(`#${inputId}`).next('.error-message').text('').hide();
    }

    // Real-time validation for all fields
    function validateField(inputId, validationFn, errorMessage) {
        const value = $(`#${inputId}`).val();
        if (!validationFn(value)) {
            showError(inputId, errorMessage);
            return false;
        } else {
            clearError(inputId);
            return true;
        }
    }

    // Email Validation
    $('#email').on('input blur', function () {
        validateField('email', validateEmail, 'Please enter a valid email address.');
    });

    // Phone Number Validation
    $('#phone').on('input blur', function () {
        validateField('phone', validatePhone, 'Phone must be exactly 10 digits (numbers only).');
    });

    // Password Strength Validation
    $('#password').on('input blur', function () {
        if (!validatePasswordStrength($(this).val())) {
            showError('password', 
                'Password must contain: \n- At least 8 characters\n- One uppercase letter\n- One lowercase letter\n- One number\n- One special character');
        } else {
            clearError('password');
        }
        
        // Also validate confirm password when password changes
        if ($('#confirm_password').val()) {
            $('#confirm_password').trigger('input');
        }
    });

    // Confirm Password Validation
    $('#confirm_password').on('input blur', function () {
        if (!validatePasswordMatch($('#password').val(), $(this).val())) {
            showError('confirm_password', 'Passwords do not match.');
        } else {
            clearError('confirm_password');
        }
    });

    // Validate required fields on blur
    $('input[required], textarea[required]').on('blur', function() {
        if (!$(this).val().trim()) {
            showError($(this).attr('id'), 'This field is required.');
        }
    });

    // Final Form Validation Before Submission
    $('form').on('submit', function (e) {
        let isValid = true;
        const $form = $(this);

        // Validate all required fields
        $form.find('input[required], textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                showError($(this).attr('id'), 'This field is required.');
                isValid = false;
            }
        });

        // Validate email
        if (!validateField('email', validateEmail, 'Please enter a valid email address.')) {
            isValid = false;
        }

        // Validate phone
        if (!validateField('phone', validatePhone, 'Phone must be exactly 10 digits (numbers only).')) {
            isValid = false;
        }

        // Validate password strength
        if (!validatePasswordStrength($('#password').val())) {
            showError('password', 
                'Password must contain: \n- At least 8 characters\n- One uppercase letter\n- One lowercase letter\n- One number\n- One special character');
            isValid = false;
        }

        // Validate password match
        if (!validatePasswordMatch($('#password').val(), $('#confirm_password').val())) {
            showError('confirm_password', 'Passwords do not match.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll to the first error
            $('html, body').animate({
                scrollTop: $('.error').first().offset().top - 100
            }, 500);
        }
    });

    // Clear errors when user starts typing
    $('input, textarea').on('input', function() {
        if ($(this).val().trim()) {
            clearError($(this).attr('id'));
        }
    });
});
