/**
 * UNIFIED PROFILE JAVASCRIPT
 * For Admin, Super Admin, Alumni, and Student Profiles
 */

/**
 * Preview image before upload
 * Used in all profile edit forms
 */
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePreview');
            const initials = document.getElementById('profileInitials');
            const profileImage = document.getElementById('profileImage');
            
            // Handle img element preview
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            // Handle profileImage specifically (for student/alumni)
            if (profileImage) {
                profileImage.src = e.target.result;
                profileImage.style.display = 'block';
            }
            
            // Hide initials/placeholder
            if (initials) {
                initials.style.display = 'none';
            }
            
            // Handle default avatar icon
            const defaultAvatar = document.getElementById('defaultAvatar');
            if (defaultAvatar) {
                defaultAvatar.style.display = 'none';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function normalizeMobileValue(value) {
    let mobile = String(value || '').trim();

    if (mobile === '') {
        return '';
    }

    mobile = mobile.replace(/[^0-9+]/g, '');

    if (mobile.startsWith('+94')) {
        mobile = mobile.slice(3);
    } else if (mobile.startsWith('94') && mobile.length === 11) {
        mobile = mobile.slice(2);
    }

    if (mobile.length === 10 && mobile.startsWith('0')) {
        mobile = mobile.slice(1);
    }

    return mobile.replace(/[^0-9]/g, '');
}

function validateEmailValue(value) {
    const email = String(value || '').trim();

    if (email === '') {
        return 'Email is required';
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return 'Please enter a valid email address';
    }

    return '';
}

function validateMobileValue(value) {
    const mobile = normalizeMobileValue(value);

    if (String(value || '').trim() === '') {
        return { message: '', normalized: '' };
    }

    if (!/^7[0-9]{8}$/.test(mobile)) {
        return {
            message: 'Mobile number must be 9 digits starting with 7',
            normalized: mobile,
        };
    }

    return { message: '', normalized: mobile };
}

function validateSocialUrlValue(value, platform = 'website') {
    const original = String(value || '').trim();

    if (original === '') {
        return { message: '', normalized: '' };
    }

    let normalized = original;
    if (!/^https?:\/\//i.test(normalized)) {
        normalized = 'https://' + normalized;
    }

    let parsedUrl;
    try {
        parsedUrl = new URL(normalized);
    } catch (error) {
        return {
            message: 'Please enter a valid URL',
            normalized: original,
        };
    }

    const host = parsedUrl.hostname.toLowerCase();
    const allowedHosts = {
        linkedin: ['linkedin.com', 'www.linkedin.com'],
        github: ['github.com', 'www.github.com'],
        twitter: ['twitter.com', 'www.twitter.com', 'x.com', 'www.x.com', 'mobile.twitter.com'],
    };

    if (platform !== 'website') {
        const hosts = allowedHosts[platform] || [];
        if (hosts.length > 0 && !hosts.includes(host)) {
            const labels = {
                linkedin: 'LinkedIn',
                github: 'GitHub',
                twitter: 'Twitter/X',
            };

            return {
                message: `Please enter a valid ${labels[platform] || 'website'} URL`,
                normalized: original,
            };
        }
    }

    if (parsedUrl.protocol !== 'http:' && parsedUrl.protocol !== 'https:') {
        return {
            message: 'URL must start with http:// or https://',
            normalized: original,
        };
    }

    return {
        message: '',
        normalized: normalized,
    };
}

function attachLiveValidation(input, validateFn) {
    if (!input) {
        return;
    }

    const formGroup = input.closest('.form-group') || input.parentElement;
    if (!formGroup) {
        return;
    }

    let messageElement = formGroup.querySelector('.js-live-validation-message');
    if (!messageElement) {
        messageElement = formGroup.querySelector('.error-message');
    }

    if (!messageElement) {
        messageElement = document.createElement('span');
        messageElement.className = 'error-message js-live-validation-message';
        messageElement.style.display = 'none';
        formGroup.appendChild(messageElement);
    } else {
        messageElement.classList.add('js-live-validation-message');
    }

    messageElement.setAttribute('aria-live', 'polite');

    let touched = false;

    function applyState(forceDisplay = false) {
        const result = validateFn(input.value);
        const currentValue = String(input.value || '').trim();
        const shouldDisplay = forceDisplay || touched || currentValue !== '';

        if (result.message) {
            input.classList.add('error');
            input.setAttribute('aria-invalid', 'true');
            input.setCustomValidity(result.message);

            if (shouldDisplay) {
                messageElement.textContent = result.message;
                messageElement.style.display = 'block';
            }

            return false;
        }

        input.classList.remove('error');
        input.removeAttribute('aria-invalid');
        input.setCustomValidity('');
        messageElement.textContent = '';
        messageElement.style.display = 'none';

        return true;
    }

    input.addEventListener('input', function() {
        touched = true;
        applyState(true);
    });

    input.addEventListener('blur', function() {
        touched = true;

        const result = validateFn(input.value);
        if (!result.message && typeof result.normalized === 'string' && result.normalized !== '' && result.normalized !== input.value) {
            input.value = result.normalized;
        }

        applyState(true);
    });

    input.addEventListener('invalid', function(event) {
        event.preventDefault();
        touched = true;
        applyState(true);
    });
}

/**
 * Initialize on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    const profileForms = document.querySelectorAll('form[data-password-modal="true"]');

    profileForms.forEach(function(form) {
        attachLiveValidation(form.querySelector('#email'), function(value) {
            return validateEmailValue(value);
        });

        attachLiveValidation(form.querySelector('#mobile'), function(value) {
            return validateMobileValue(value);
        });

        attachLiveValidation(form.querySelector('#linkedin_url'), function(value) {
            return validateSocialUrlValue(value, 'linkedin');
        });

        attachLiveValidation(form.querySelector('#github_url'), function(value) {
            return validateSocialUrlValue(value, 'github');
        });

        attachLiveValidation(form.querySelector('#twitter_url'), function(value) {
            return validateSocialUrlValue(value, 'twitter');
        });

        attachLiveValidation(form.querySelector('#personal_website'), function(value) {
            return validateSocialUrlValue(value, 'website');
        });

        attachLiveValidation(form.querySelector('#personalweb_url'), function(value) {
            return validateSocialUrlValue(value, 'website');
        });
    });

    console.log('Profile page loaded');
});

