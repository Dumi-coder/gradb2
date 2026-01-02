/**
 * Student Signup Form Handler with AJAX
 * Handles form submission via AJAX and displays errors dynamically
 */

document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.querySelector('.auth-form');
    
    if (!signupForm) {
        console.error('Student signup form not found!');
        return;
    }
    
    console.log('Student signup form handler initialized');
    
    // Create error container if it doesn't exist - make it very visible
    let errorContainer = document.querySelector('.error-container');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'error-container js-error-container';
        errorContainer.style.display = 'none';
        errorContainer.style.marginBottom = '20px';
        errorContainer.style.padding = '15px';
        errorContainer.style.borderRadius = '8px';
        errorContainer.style.backgroundColor = '#fee2e2';
        errorContainer.style.border = '2px solid #ef4444';
        errorContainer.style.color = '#991b1b';
        errorContainer.style.fontWeight = '500';
        signupForm.insertBefore(errorContainer, signupForm.firstChild);
    }
    
    // Function to display errors
    function showErrors(errors) {
        if (!errors || errors.length === 0) {
            errorContainer.style.display = 'none';
            errorContainer.innerHTML = '';
            return;
        }
        
        // Make sure styles are applied
        errorContainer.className = 'alert alert-danger error-container js-error-container';
        errorContainer.style.display = 'block';
        errorContainer.style.marginBottom = '20px';
        errorContainer.style.padding = '15px';
        errorContainer.style.borderRadius = '8px';
        errorContainer.style.backgroundColor = '#fee2e2';
        errorContainer.style.border = '2px solid #ef4444';
        errorContainer.style.color = '#991b1b';
        errorContainer.style.fontWeight = '500';
        errorContainer.style.fontSize = '14px';
        errorContainer.style.lineHeight = '1.5';
        
        // Create error list with icons or bullets
        errorContainer.innerHTML = '<div style="font-weight: 600; margin-bottom: 8px;"></div>' + 
            errors.map(error => `<div style="margin: 5px 0; padding-left: 5px;">• ${escapeHtml(error)}</div>`).join('');
        
        // Scroll to error container
        setTimeout(() => {
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }
    
    // Function to clear errors
    function clearErrors() {
        errorContainer.style.display = 'none';
        errorContainer.innerHTML = '';
    }
    
    // Function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to show loading state
    function setLoading(isLoading) {
        const submitButton = signupForm.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = isLoading;
            submitButton.textContent = isLoading ? 'Creating Account...' : 'Create Account';
        }
    }
    
    // Prevent any default form submission
    signupForm.onsubmit = function(e) {
        e.preventDefault();
        return false;
    };
    
    // Handle form submission
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        clearErrors();
        setLoading(true);
        
        // Get form data
        const formData = new FormData(signupForm);
        // Add AJAX flag as backup
        formData.append('_ajax', '1');
        
        // Create AJAX request
        const xhr = new XMLHttpRequest();
        // Construct the URL - use current path and ensure it points to student/Auth
        let formAction = window.location.pathname;
        
        // If we're not already on the Auth page, construct the URL
        if (!formAction.includes('/student/Auth') && !formAction.includes('/Student/Auth')) {
            // Remove any trailing parts and add /student/Auth
            const basePath = formAction.split('/').slice(0, -1).join('/');
            formAction = basePath + '/student/Auth';
        }
        
        // Ensure it starts with / if it's a path
        if (!formAction.startsWith('/')) {
            formAction = '/' + formAction;
        }
        
        console.log('Submitting to:', formAction);
        
        xhr.open('POST', formAction, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            setLoading(false);
            
            console.log('Response status:', xhr.status);
            console.log('Response text:', xhr.responseText);
            
            if (xhr.status === 200) {
                try {
                    // Check if response is HTML (means it's not JSON, fallback happened)
                    const contentType = xhr.getResponseHeader('Content-Type') || '';
                    if (contentType.includes('text/html')) {
                        console.error('Received HTML instead of JSON - AJAX detection may have failed');
                        showErrors(['Server error: Please refresh the page and try again.']);
                        return;
                    }
                    
                    const response = JSON.parse(xhr.responseText);
                    console.log('Parsed response:', response);
                    
                    if (response.success) {
                        // Success - redirect to dashboard
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = '/student/dashboard';
                        }
                    } else {
                        // Show errors
                        if (response.errors && response.errors.length > 0) {
                            showErrors(response.errors);
                        } else {
                            showErrors(['An error occurred. Please try again.']);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    console.error('Response was:', xhr.responseText.substring(0, 200));
                    showErrors(['An error occurred. Please try again.']);
                }
            } else {
                showErrors(['Server error. Please try again later.']);
            }
        };
        
        xhr.onerror = function() {
            setLoading(false);
            showErrors(['Network error. Please check your connection and try again.']);
        };
        
        xhr.send(formData);
    });
    
    // Clear errors when user starts typing
    const inputs = signupForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (errorContainer.style.display === 'block') {
                clearErrors();
            }
        });
    });
});

