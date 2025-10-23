/**
 * Password Validation and Strength Indicator
 * Provides real-time password validation with visual feedback
 */

class PasswordValidator {
    constructor() {
        this.requirements = {
            minLength: 8,
            hasLetter: /[a-zA-Z]/,
            hasNumber: /[0-9]/,
            hasSpecial: /[!@#$%^&*()_+\-=\[\]{}|;':",./<>?]/
        };
        
        this.strengthLevels = {
            weak: { score: 0, color: '#e74c3c', text: 'Weak' },
            fair: { score: 1, color: '#f39c12', text: 'Fair' },
            good: { score: 2, color: '#3498db', text: 'Good' },
            strong: { score: 3, color: '#27ae60', text: 'Strong' }
        };
    }

    /**
     * Validate password against all requirements
     * @param {string} password - The password to validate
     * @returns {object} - Validation result with errors and strength
     */
    validatePassword(password) {
        const errors = [];
        const checks = {
            length: password.length >= this.requirements.minLength,
            letter: this.requirements.hasLetter.test(password),
            number: this.requirements.hasNumber.test(password),
            special: this.requirements.hasSpecial.test(password)
        };

        if (!checks.length) {
            errors.push(`Password must be at least ${this.requirements.minLength} characters long`);
        }
        if (!checks.letter) {
            errors.push('Password must contain at least one letter');
        }
        if (!checks.number) {
            errors.push('Password must contain at least one number');
        }
        if (!checks.special) {
            errors.push('Password must contain at least one special character');
        }

        const strength = this.calculateStrength(checks);
        
        return {
            valid: errors.length === 0,
            errors: errors,
            strength: strength,
            checks: checks
        };
    }

    /**
     * Calculate password strength based on requirements met
     * @param {object} checks - Object with boolean checks
     * @returns {object} - Strength level information
     */
    calculateStrength(checks) {
        const score = Object.values(checks).filter(Boolean).length;
        
        if (score === 0) return this.strengthLevels.weak;
        if (score === 1) return this.strengthLevels.fair;
        if (score === 2) return this.strengthLevels.good;
        return this.strengthLevels.strong;
    }

    /**
     * Create password strength indicator HTML
     * @param {string} containerId - ID of container to add indicator to
     */
    createStrengthIndicator(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const indicator = document.createElement('div');
        indicator.className = 'password-strength-indicator';
        indicator.innerHTML = `
            <div class="strength-bar">
                <div class="strength-fill"></div>
            </div>
            <div class="strength-text">Enter a password</div>
            <div class="requirements-list">
                <div class="requirement" data-check="length">
                    <span class="check-icon">✗</span>
                    <span class="requirement-text">At least 8 characters</span>
                </div>
                <div class="requirement" data-check="letter">
                    <span class="check-icon">✗</span>
                    <span class="requirement-text">At least one letter</span>
                </div>
                <div class="requirement" data-check="number">
                    <span class="check-icon">✗</span>
                    <span class="requirement-text">At least one number</span>
                </div>
                <div class="requirement" data-check="special">
                    <span class="check-icon">✗</span>
                    <span class="requirement-text">At least one special character</span>
                </div>
            </div>
        `;

        // Add CSS styles
        const style = document.createElement('style');
        style.textContent = `
            .password-strength-indicator {
                margin-top: 8px;
                font-size: 0.9em;
            }
            
            .strength-bar {
                width: 100%;
                height: 4px;
                background-color: #ecf0f1;
                border-radius: 2px;
                overflow: hidden;
                margin-bottom: 8px;
            }
            
            .strength-fill {
                height: 100%;
                width: 0%;
                transition: all 0.3s ease;
                border-radius: 2px;
            }
            
            .strength-text {
                font-weight: 500;
                margin-bottom: 8px;
            }
            
            .requirements-list {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            
            .requirement {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.85em;
            }
            
            .check-icon {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            
            .requirement.valid .check-icon {
                background-color: #27ae60;
                color: white;
            }
            
            .requirement.invalid .check-icon {
                background-color: #e74c3c;
                color: white;
            }
            
            .requirement-text {
                color: #7f8c8d;
            }
            
            .requirement.valid .requirement-text {
                color: #27ae60;
            }
        `;
        
        document.head.appendChild(style);
        container.appendChild(indicator);
    }

    /**
     * Update password strength indicator
     * @param {string} containerId - ID of container with indicator
     * @param {object} validation - Validation result from validatePassword
     */
    updateStrengthIndicator(containerId, validation) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const indicator = container.querySelector('.password-strength-indicator');
        if (!indicator) return;

        const strengthFill = indicator.querySelector('.strength-fill');
        const strengthText = indicator.querySelector('.strength-text');
        const requirements = indicator.querySelectorAll('.requirement');

        // Update strength bar
        const percentage = (validation.strength.score / 3) * 100;
        strengthFill.style.width = `${percentage}%`;
        strengthFill.style.backgroundColor = validation.strength.color;
        strengthText.textContent = validation.strength.text;
        strengthText.style.color = validation.strength.color;

        // Update individual requirements
        requirements.forEach(req => {
            const checkType = req.dataset.check;
            const checkIcon = req.querySelector('.check-icon');
            const requirementText = req.querySelector('.requirement-text');
            
            const isValid = validation.checks[checkType];
            
            req.classList.remove('valid', 'invalid');
            req.classList.add(isValid ? 'valid' : 'invalid');
            
            checkIcon.textContent = isValid ? '✓' : '✗';
        });
    }

    /**
     * Initialize password validation for a form
     * @param {string} passwordFieldId - ID of password input field
     * @param {string} confirmFieldId - ID of confirm password field (optional)
     * @param {string} containerId - ID of container for strength indicator
     */
    initPasswordValidation(passwordFieldId, confirmFieldId = null, containerId = null) {
        const passwordField = document.getElementById(passwordFieldId);
        if (!passwordField) return;

        // Create strength indicator if container provided
        if (containerId) {
            this.createStrengthIndicator(containerId);
        }

        // Real-time validation on password input
        passwordField.addEventListener('input', (e) => {
            const password = e.target.value;
            const validation = this.validatePassword(password);
            
            // Update strength indicator
            if (containerId) {
                this.updateStrengthIndicator(containerId, validation);
            }
            
            // Update field validation state
            this.updateFieldValidation(passwordField, validation);
            
            // Update confirm password if it exists
            if (confirmFieldId) {
                const confirmField = document.getElementById(confirmFieldId);
                if (confirmField) {
                    this.validateConfirmPassword(password, confirmField);
                }
            }
        });

        // Validate confirm password if field exists
        if (confirmFieldId) {
            const confirmField = document.getElementById(confirmFieldId);
            if (confirmField) {
                confirmField.addEventListener('input', (e) => {
                    const password = document.getElementById(passwordFieldId).value;
                    this.validateConfirmPassword(password, confirmField);
                });
            }
        }

        // Form submission validation
        const form = passwordField.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const password = passwordField.value;
                const validation = this.validatePassword(password);
                
                if (!validation.valid) {
                    e.preventDefault();
                    this.showValidationErrors(validation.errors);
                    return false;
                }
                
                // Check confirm password if exists
                if (confirmFieldId) {
                    const confirmField = document.getElementById(confirmFieldId);
                    if (confirmField && password !== confirmField.value) {
                        e.preventDefault();
                        this.showValidationErrors(['Passwords do not match']);
                        return false;
                    }
                }
            });
        }
    }

    /**
     * Update field validation visual state
     * @param {HTMLElement} field - Password input field
     * @param {object} validation - Validation result
     */
    updateFieldValidation(field, validation) {
        field.classList.remove('valid', 'invalid');
        
        if (field.value.length > 0) {
            field.classList.add(validation.valid ? 'valid' : 'invalid');
        }
    }

    /**
     * Validate confirm password field
     * @param {string} password - Original password
     * @param {HTMLElement} confirmField - Confirm password field
     */
    validateConfirmPassword(password, confirmField) {
        const confirmPassword = confirmField.value;
        const isValid = password === confirmPassword && password.length > 0;
        
        confirmField.classList.remove('valid', 'invalid');
        if (confirmPassword.length > 0) {
            confirmField.classList.add(isValid ? 'valid' : 'invalid');
        }
    }

    /**
     * Show validation errors to user
     * @param {array} errors - Array of error messages
     */
    showValidationErrors(errors) {
        // Remove existing error messages
        const existingErrors = document.querySelectorAll('.password-validation-errors');
        existingErrors.forEach(error => error.remove());
        
        // Create new error message
        const errorContainer = document.createElement('div');
        errorContainer.className = 'password-validation-errors';
        errorContainer.style.cssText = `
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 10px;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 0.9em;
        `;
        
        const errorList = document.createElement('ul');
        errorList.style.margin = '0';
        errorList.style.paddingLeft = '20px';
        
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });
        
        errorContainer.appendChild(errorList);
        
        // Insert after password field
        const passwordField = document.querySelector('input[type="password"]');
        if (passwordField) {
            passwordField.parentNode.insertBefore(errorContainer, passwordField.nextSibling);
        }
    }
}

// Global instance
window.passwordValidator = new PasswordValidator();

// Auto-initialize for common password fields
document.addEventListener('DOMContentLoaded', function() {
    // Initialize for student signup
    if (document.getElementById('student-password')) {
        window.passwordValidator.initPasswordValidation(
            'student-password',
            'student-confirm-password',
            'student-password-container'
        );
    }
    
    // Initialize for alumni signup
    if (document.getElementById('alumni-password')) {
        window.passwordValidator.initPasswordValidation(
            'alumni-password',
            'alumni-confirm-password',
            'alumni-password-container'
        );
    }
    
    // Initialize for admin forms
    if (document.getElementById('admin-password')) {
        window.passwordValidator.initPasswordValidation(
            'admin-password',
            'admin-confirm-password',
            'admin-password-container'
        );
    }
});
