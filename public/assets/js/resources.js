// resources.js - Resources Page Functionality

// Global variables
let currentFilter = 'all';

// DOM elements
const uploadModal = document.getElementById('uploadModal');
const uploadForm = document.querySelector('.upload-form');
const fileUploadArea = document.querySelector('.file-upload-area');
const resourceFileInput = document.getElementById('resourceFile');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeResources();
});

function initializeResources() {
    // Add event listeners
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleUploadSubmit);
    }
    
    if (fileUploadArea) {
        fileUploadArea.addEventListener('dragover', handleDragOver);
        fileUploadArea.addEventListener('dragleave', handleDragLeave);
        fileUploadArea.addEventListener('drop', handleDrop);
    }
    
    if (resourceFileInput) {
        resourceFileInput.addEventListener('change', handleFileSelect);
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeAllModals();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllModals();
        }
    });
}

// Modal functions
function openUploadModal() {
    uploadModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    setTimeout(() => {
        document.getElementById('resourceTitle').focus();
    }, 100);
}

function closeUploadModal() {
    uploadModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetUploadForm();
}

function closeAllModals() {
    closeUploadModal();
}

// File upload handling
function handleDragOver(event) {
    event.preventDefault();
    fileUploadArea.style.borderColor = 'var(--primary)';
    fileUploadArea.style.backgroundColor = 'var(--secondary)';
}

function handleDragLeave(event) {
    event.preventDefault();
    fileUploadArea.style.borderColor = 'var(--border)';
    fileUploadArea.style.backgroundColor = 'var(--background)';
}

function handleDrop(event) {
    event.preventDefault();
    fileUploadArea.style.borderColor = 'var(--border)';
    fileUploadArea.style.backgroundColor = 'var(--background)';
    
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        resourceFileInput.files = files;
        updateFileDisplay(files[0]);
    }
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        updateFileDisplay(file);
    }
}

function updateFileDisplay(file) {
    const placeholder = fileUploadArea.querySelector('.upload-placeholder');
    if (placeholder) {
        placeholder.innerHTML = `
            <i class="fas fa-file"></i>
            <p>${file.name}</p>
            <small>${formatFileSize(file.size)}</small>
        `;
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form handling
function handleUploadSubmit(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(uploadForm);
    const resourceData = {
        title: formData.get('resourceTitle'),
        category: formData.get('resourceCategory'),
        description: formData.get('resourceDescription'),
        file: formData.get('resourceFile'),
        timestamp: new Date().toISOString()
    };
    
    // Validate form
    if (!validateResourceData(resourceData)) {
        return;
    }
    
    // Submit resource (simulate API call)
    submitResource(resourceData);
}

function validateResourceData(resourceData) {
    if (!resourceData.title.trim()) {
        showNotification('Please enter a resource title', 'error');
        return false;
    }
    
    if (!resourceData.category) {
        showNotification('Please select a category', 'error');
        return false;
    }
    
    if (!resourceData.file || resourceData.file.size === 0) {
        showNotification('Please select a file to upload', 'error');
        return false;
    }
    
    if (resourceData.title.length < 5) {
        showNotification('Title must be at least 5 characters long', 'error');
        return false;
    }
    
    // Check file size (max 10MB)
    if (resourceData.file.size > 10 * 1024 * 1024) {
        showNotification('File size must be less than 10MB', 'error');
        return false;
    }
    
    return true;
}

function submitResource(resourceData) {
    // Show loading state
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showNotification('Resource uploaded successfully!', 'success');
        closeUploadModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Add resource to resources list
        console.log('Resource data:', resourceData);
        
    }, 2000);
}

function resetUploadForm() {
    if (uploadForm) {
        uploadForm.reset();
    }
    
    // Reset file display
    const placeholder = fileUploadArea.querySelector('.upload-placeholder');
    if (placeholder) {
        placeholder.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to select file or drag and drop</p>
            <small>Supported: PDF, DOC, DOCX, TXT, ZIP, RAR</small>
        `;
    }
}

// Filter and search functionality
function filterResources(category) {
    currentFilter = category;
    
    // Update active category
    document.querySelectorAll('.category-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Add active class to clicked category
    event.currentTarget.classList.add('active');
    
    // TODO: Implement actual filtering
    console.log('Filtering resources by category:', category);
    showNotification(`Filtering by ${category}`, 'info');
}

function viewAllResources() {
    // TODO: Navigate to full resources page or expand list
    console.log('Viewing all resources');
    showNotification('Loading all resources...', 'info');
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
window.openUploadModal = openUploadModal;
window.closeUploadModal = closeUploadModal;
window.filterResources = filterResources;
window.viewAllResources = viewAllResources;

