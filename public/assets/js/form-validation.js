

// Simple real-time validation for your existing forms
document.addEventListener('DOMContentLoaded', function() {
    
    // Validation patterns
    const patterns = {
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/,
        studentId: /^[A-Z]{2}\d{4}[A-Z]?$/,
        alumniId: /^[A-Z]{2}\d{4}[A-Z]?$/,
        name: /^[a-zA-Z\s]{2,50}$/
    };

    // Show error
    function showError(input, message) {
        input.style.borderColor = '#e74c3c';
        let error = input.parentNode.querySelector('.error-text');
        if (!error) {
            error = document.createElement('span');
            error.className = 'error-text';
            input.parentNode.appendChild(error);
        }
        error.textContent = message;
        error.style.display = 'block';
    }

    // Show success
    function showSuccess(input) {
        input.style.borderColor = '#27ae60';
        let error = input.parentNode.querySelector('.error-text');
        if (error) error.style.display = 'none';
    }

    // Clear validation
    function clearValidation(input) {
        input.style.borderColor = '#ddd';
        let error = input.parentNode.querySelector('.error-text');
        if (error) error.style.display = 'none';
    }

    // Validate field
    function validateField(input, pattern, message) {
        const value = input.value.trim();
        
        if (input.required && !value) {
            showError(input, 'This field is required');
            return false;
        }
        
        if (value && pattern && !pattern.test(value)) {
            showError(input, message);
            return false;
        }
        
        if (value) {
            showSuccess(input);
        } else {
            clearValidation(input);
        }
        return true;
    }

    // Setup validation for all forms
    document.querySelectorAll('.login-form').forEach(form => {
        
        // Name validation
        const nameInput = form.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.addEventListener('blur', () => {
                validateField(nameInput, patterns.name, 'Name should contain only letters and spaces (2-50 chars)');
            });
        }

        // Email validation
        const emailInput = form.querySelector('input[name="email"]');
        if (emailInput) {
            emailInput.addEventListener('blur', () => {
                validateField(emailInput, patterns.email, 'Please enter a valid email address');
            });
        }

        // Student ID validation
        const studentIdInput = form.querySelector('input[name="student_id"]');
        if (studentIdInput) {
            studentIdInput.addEventListener('blur', () => {
                validateField(studentIdInput, patterns.studentId, 'Format: AB1234 or AB1234C');
            });
        }

        // Alumni ID validation
        const alumniIdInput = form.querySelector('input[name="alumni_id"]');
        if (alumniIdInput) {
            alumniIdInput.addEventListener('blur', () => {
                validateField(alumniIdInput, patterns.alumniId, 'Format: AB1234 or AB1234C');
            });
        }

        // Password validation
        const passwordInput = form.querySelector('input[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('blur', () => {
                validateField(passwordInput, patterns.password, 'Password needs: 8+ chars, uppercase, lowercase, number');
            });
        }

        // Confirm password validation
        const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');
        if (confirmPasswordInput && passwordInput) {
            confirmPasswordInput.addEventListener('blur', () => {
                if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    showError(confirmPasswordInput, 'Passwords do not match');
                } else if (confirmPasswordInput.value) {
                    showSuccess(confirmPasswordInput);
                }
            });
        }

        // Faculty validation
        const facultySelect = form.querySelector('select[name="faculty"]');
        if (facultySelect) {
            facultySelect.addEventListener('change', () => {
                if (!facultySelect.value) {
                    showError(facultySelect, 'Please select a faculty');
                } else {
                    showSuccess(facultySelect);
                }
            });
        }

        // Graduation year validation
        const gradYearInput = form.querySelector('input[name="graduation_year"]');
        if (gradYearInput) {
            gradYearInput.addEventListener('blur', () => {
                const year = parseInt(gradYearInput.value);
                const currentYear = new Date().getFullYear();
                if (gradYearInput.value && (year < 1900 || year > currentYear)) {
                    showError(gradYearInput, 'Please enter a valid graduation year');
                } else if (gradYearInput.value) {
                    showSuccess(gradYearInput);
                }
            });
        }
    });

    // Add simple CSS for error styling
    const style = document.createElement('style');
    style.textContent = `
        .error-text {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 5px;
            display: none;
        }
    `;
    document.head.appendChild(style);
});