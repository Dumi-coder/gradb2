// aid-request-form.js - Aid Request Form Page Functionality

// Global variables
let currentStep = 1;
const totalSteps = 4;
let formData = {};

// DOM elements
const form = document.getElementById('aidRequestForm');
const progressSteps = document.querySelector('.progress-steps');
const formSteps = document.querySelectorAll('.form-step');
const stepElements = document.querySelectorAll('.step');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const submitBtn = document.getElementById('submitBtn');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeAidRequestForm();
});

function initializeAidRequestForm() {
    // Add event listeners
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
    
    // File upload handlers
    initializeFileUploads();
    
    // Form validation
    initializeFormValidation();
    
    // Update progress on load
    updateProgress();
}

// Step navigation
function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep < 1 || newStep > totalSteps) {
        return;
    }
    
    // Validate current step before proceeding
    if (direction > 0 && !validateCurrentStep()) {
        return;
    }
    
    // Update current step
    currentStep = newStep;
    
    // Update UI
    updateStepDisplay();
    updateProgress();
    updateNavigationButtons();
    
    // If moving to review step, populate review content
    if (currentStep === 4) {
        populateReviewContent();
    }
}

function updateStepDisplay() {
    formSteps.forEach((step, index) => {
        step.classList.toggle('active', index + 1 === currentStep);
    });
}

function updateProgress() {
    // Update progress bar
    progressSteps.setAttribute('data-current-step', currentStep);
    
    // Update step indicators
    stepElements.forEach((step, index) => {
        const stepNumber = index + 1;
        step.classList.remove('active', 'completed');
        
        if (stepNumber < currentStep) {
            step.classList.add('completed');
        } else if (stepNumber === currentStep) {
            step.classList.add('active');
        }
    });
}

function updateNavigationButtons() {
    // Previous button
    if (currentStep === 1) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'inline-flex';
    }
    
    // Next/Submit button
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.style.display = 'inline-flex';
        submitBtn.style.display = 'none';
    }
}

// Form validation
function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showNotification('Please fill in all required fields', 'error');
    }
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const formGroup = field.closest('.form-group');
    
    // Remove previous validation classes
    formGroup.classList.remove('error', 'success');
    
    // Check if field is required and empty
    if (field.hasAttribute('required') && !value) {
        formGroup.classList.add('error');
        showFieldError(formGroup, 'This field is required');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            formGroup.classList.add('error');
            showFieldError(formGroup, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Phone validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
            formGroup.classList.add('error');
            showFieldError(formGroup, 'Please enter a valid phone number');
            return false;
        }
    }
    
    // GPA validation
    if (field.name === 'gpa' && value) {
        const gpa = parseFloat(value);
        if (gpa < 0 || gpa > 4) {
            formGroup.classList.add('error');
            showFieldError(formGroup, 'GPA must be between 0 and 4');
            return false;
        }
    }
    
    // Amount validation
    if (field.name === 'amountRequested' && value) {
        const amount = parseFloat(value);
        if (amount < 1) {
            formGroup.classList.add('error');
            showFieldError(formGroup, 'Amount must be at least $1');
            return false;
        }
    }
    
    // If we get here, field is valid
    if (value) {
        formGroup.classList.add('success');
    }
    
    return true;
}

function showFieldError(formGroup, message) {
    // Remove existing error message
    const existingError = formGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    formGroup.appendChild(errorDiv);
}

// File upload handling
function initializeFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleFileSelect(this);
        });
        
        // Drag and drop
        const uploadArea = input.closest('.file-upload-area');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
        }
    });
}

function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        updateFileDisplay(input, file);
    }
}

function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.style.borderColor = 'var(--primary)';
    event.currentTarget.style.backgroundColor = 'var(--secondary)';
}

function handleDragLeave(event) {
    event.preventDefault();
    event.currentTarget.style.borderColor = 'var(--border)';
    event.currentTarget.style.backgroundColor = 'var(--background)';
}

function handleDrop(event) {
    event.preventDefault();
    event.currentTarget.style.borderColor = 'var(--border)';
    event.currentTarget.style.backgroundColor = 'var(--background)';
    
    const files = event.dataTransfer.files;
    const input = event.currentTarget.querySelector('input[type="file"]');
    
    if (files.length > 0) {
        input.files = files;
        updateFileDisplay(input, files[0]);
    }
}

function updateFileDisplay(input, file) {
    const uploadArea = input.closest('.file-upload-area');
    const placeholder = uploadArea.querySelector('.upload-placeholder');
    
    // Validate file size (5MB limit)
    if (file.size > 5 * 1024 * 1024) {
        showNotification('File size must be less than 5MB', 'error');
        input.value = '';
        return;
    }
    
    // Update display
    uploadArea.classList.add('uploaded');
    placeholder.innerHTML = `
        <i class="fas fa-file-check"></i>
        <p>${file.name}</p>
        <small>${formatFileSize(file.size)}</small>
    `;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form validation initialization
function initializeFormValidation() {
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            // Clear error state on input
            const formGroup = this.closest('.form-group');
            formGroup.classList.remove('error');
            const errorMessage = formGroup.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        });
    });
}

// Review content population
function populateReviewContent() {
    // Personal Information
    const personalData = {
        'Name': `${getFieldValue('firstName')} ${getFieldValue('lastName')}`,
        'Student ID': getFieldValue('studentId'),
        'Email': getFieldValue('email'),
        'Phone': getFieldValue('phone'),
        'Major': getFieldValue('major'),
        'Academic Year': getFieldValue('year'),
        'GPA': getFieldValue('gpa') || 'Not provided'
    };
    
    populateReviewSection('personalReview', personalData);
    
    // Aid Details
    const aidData = {
        'Aid Type': getFieldValue('aidType'),
        'Amount Requested': `$${getFieldValue('amountRequested')}`,
        'Urgency': getFieldValue('urgency'),
        'Reason': getFieldValue('reason'),
        'Previous Aid': getFieldValue('previousAid') || 'None'
    };
    
    populateReviewSection('aidReview', aidData);
    
    // Documents
    const documentData = getDocumentSummary();
    populateReviewSection('documentsReview', documentData);
}

function getFieldValue(fieldName) {
    const field = document.querySelector(`[name="${fieldName}"]`);
    return field ? field.value : '';
}

function populateReviewSection(containerId, data) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    Object.entries(data).forEach(([key, value]) => {
        const p = document.createElement('p');
        p.innerHTML = `<strong>${key}:</strong> ${value}`;
        container.appendChild(p);
    });
}

function getDocumentSummary() {
    const summary = {};
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        const label = input.closest('.upload-item').querySelector('label').textContent;
        if (input.files.length > 0) {
            summary[label] = Array.from(input.files).map(file => file.name).join(', ');
        } else {
            summary[label] = 'Not uploaded';
        }
    });
    
    return summary;
}

// Form submission
function handleFormSubmit(event) {
    event.preventDefault();
    
    // Final validation
    if (!validateCurrentStep()) {
        return;
    }
    
    // Check agreements
    const termsAgreement = document.getElementById('termsAgreement');
    const privacyAgreement = document.getElementById('privacyAgreement');
    
    if (!termsAgreement.checked || !privacyAgreement.checked) {
        showNotification('Please agree to the terms and privacy policy', 'error');
        return;
    }
    
    // Collect form data
    const formData = new FormData(form);
    const applicationData = {
        personalInfo: {
            firstName: formData.get('firstName'),
            lastName: formData.get('lastName'),
            studentId: formData.get('studentId'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            major: formData.get('major'),
            year: formData.get('year'),
            gpa: formData.get('gpa')
        },
        aidDetails: {
            type: formData.get('aidType'),
            amount: formData.get('amountRequested'),
            urgency: formData.get('urgency'),
            reason: formData.get('reason'),
            previousAid: formData.get('previousAid')
        },
        documents: {
            financialStatement: formData.get('financialStatement'),
            enrollmentProof: formData.get('enrollmentProof'),
            additionalDocs: formData.get('additionalDocs')
        },
        timestamp: new Date().toISOString()
    };
    
    // Submit application
    submitApplication(applicationData);
}

function submitApplication(data) {
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showSuccessMessage();
        
        // Reset button
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
        submitBtn.disabled = false;
        
        console.log('Application data:', data);
        
    }, 2000);
}

function showSuccessMessage() {
    const formSection = document.querySelector('.form-section');
    formSection.innerHTML = `
        <div class="form-submitted">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3>Application Submitted Successfully!</h3>
            <p>Your aid request has been received and is being reviewed. You will receive an email confirmation shortly with your application reference number.</p>
            <p>Our team will review your application and get back to you within 5-7 business days. For urgent requests, we aim to respond within 24-48 hours.</p>
            <button class="btn btn-primary btn-md" onclick="window.location.href='aid-requests.html'">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Aid Requests</span>
            </button>
        </div>
    `;
}

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        case 'info': return 'info-circle';
        default: return 'info-circle';
    }
}

// Export functions for global access
window.changeStep = changeStep;

