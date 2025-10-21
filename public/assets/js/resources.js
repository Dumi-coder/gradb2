// resources.js - Resources Page Functionality

// Global variables
let currentFilter = 'all';
const ALLOWED_EXTENSIONS = ['pdf','doc','docx','txt','zip','rar','ppt','pptx','xls','xlsx','png','jpg','jpeg','gif'];

// DOM elements
const uploadModal = document.getElementById('uploadModal');
const uploadForm = document.querySelector('.upload-form');
const fileUploadArea = document.querySelector('.file-upload-area');
const resourceFileInput = document.getElementById('resourceFile');
const resourceIdInput = document.getElementById('resourceId');
const resourceModeInput = document.getElementById('resourceMode');

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

    // Delegate edit button clicks from My Resources cards
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-action="edit"]');
        if (btn) {
            const card = btn.closest('.my-resource-card');
            if (card) openEditModalFromCard(card);
        }
    });
    
    // Delegate delete button clicks
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-action="delete"]');
        if (btn) {
            const card = btn.closest('.my-resource-card');
            if (card) {
                const resourceId = card.dataset.id;
                const title = card.dataset.title || 'this resource';
                confirmDeleteResource(resourceId, title, card);
            }
        }
    });
}

// Modal functions
function openUploadModal() {
    // default to create mode
    if (resourceModeInput) resourceModeInput.value = 'create';
    if (resourceIdInput) resourceIdInput.value = '';
    
    // Make file required for create mode
    if (resourceFileInput) {
        resourceFileInput.setAttribute('required', 'required');
    }
    
    uploadModal.classList.add('show');
    uploadModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    setTimeout(() => {
        document.getElementById('resourceTitle').focus();
    }, 100);
}

function closeUploadModal() {
    uploadModal.classList.remove('show');
    uploadModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetUploadForm();
}

function closeAllModals() {
    closeUploadModal();
}

// Open modal for editing with prefilled values
function openEditModalFromCard(card) {
    if (resourceModeInput) resourceModeInput.value = 'edit';
    if (resourceIdInput) resourceIdInput.value = card.dataset.id || '';

    // Prefill fields
    document.getElementById('resourceTitle').value = card.dataset.title || '';
    document.getElementById('resourceCategory').value = card.dataset.category || '';
    document.getElementById('resourceDescription').value = card.dataset.description || '';

    // Make file input optional for edit mode
    if (resourceFileInput) {
        resourceFileInput.removeAttribute('required');
    }

    // Optional file replacement label
    const placeholder = fileUploadArea.querySelector('.upload-placeholder');
    if (placeholder) {
        placeholder.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Replace file (optional)</p>
            <small>Leave empty to keep current file</small>
        `;
    }

    // Change submit button label to Save
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save"></i> <span>Save Changes</span>';

    // Show modal
    uploadModal.classList.add('show');
    uploadModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
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
        const file = files[0];
        if (!isAllowedFile(file)) {
            const ext = getExtension(file.name);
            showNotification(`Unsupported file type: .${ext}. Allowed: ${ALLOWED_EXTENSIONS.join(', ')}`, 'error');
            return;
        }
        resourceFileInput.files = files;
        updateFileDisplay(file);
    }
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        if (!isAllowedFile(file)) {
            const ext = getExtension(file.name);
            showNotification(`Unsupported file type: .${ext}. Allowed: ${ALLOWED_EXTENSIONS.join(', ')}`, 'error');
            // Reset the input so user can re-select
            event.target.value = '';
            resetUploadForm();
            return;
        }
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

function getExtension(name) {
    const idx = name.lastIndexOf('.');
    return idx >= 0 ? name.slice(idx + 1).toLowerCase() : '';
}

function isAllowedFile(file) {
    const ext = getExtension(file.name || '');
    return ALLOWED_EXTENSIONS.includes(ext);
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
    const mode = (resourceModeInput && resourceModeInput.value) || 'create';
    const resourceId = (resourceIdInput && resourceIdInput.value) || '';

    // Validate form
    if (!validateResourceData(resourceData, mode)) {
        return;
    }
    
    // Submit resource to server
    submitResource(resourceData, mode, resourceId);
}

function validateResourceData(resourceData, mode = 'create') {
    if (!resourceData.title.trim()) {
        showNotification('Please enter a resource title', 'error');
        return false;
    }
    
    if (!resourceData.category) {
        showNotification('Please select a category', 'error');
        return false;
    }
    
    if (mode === 'create') {
        if (!resourceData.file || resourceData.file.size === 0) {
            showNotification('Please select a file to upload', 'error');
            return false;
        }
    }
    
    if (resourceData.title.length < 5) {
        showNotification('Title must be at least 5 characters long', 'error');
        return false;
    }
    
    // Check file size (max 10MB)
    if (resourceData.file && resourceData.file.size && resourceData.file.size > 10 * 1024 * 1024) {
        showNotification('File size must be less than 10MB', 'error');
        return false;
    }
    
    return true;
}

function submitResource(resourceData, mode = 'create', resourceId = '') {
    // Show loading state
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;

    // Prepare FormData from the actual form to include the file
    const fd = new FormData(uploadForm);
    if (mode === 'edit') {
        fd.set('resourceId', resourceId);
    }

    // Determine endpoint
    const base = (typeof window.APP_ROOT !== 'undefined') ? window.APP_ROOT : '';
    const url = base + (mode === 'edit' ? '/alumni/resources/update' : '/alumni/resources/upload');

    fetch(url, {
        method: 'POST',
        body: fd
    }).then(async (res) => {
        let data;
        try { data = await res.json(); } catch (e) { data = { success: false, message: 'Invalid server response' }; }
        if (!res.ok || !data.success) throw new Error(data.message || 'Upload failed');
        if (mode === 'edit') {
            updateResourceInGrid(data.resource);
            showNotification('Resource updated successfully!', 'success');
        } else {
            // Success: add to grid
            addResourceToGrid(data.resource);
            showNotification('Resource uploaded successfully!', 'success');
        }
        closeUploadModal();
    }).catch(err => {
        console.error(err);
        showNotification(err.message || 'Upload failed', 'error');
    }).finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        resetUploadForm();
    });
}

function addResourceToGrid(resource) {
    const grid = document.getElementById('my-resources-grid');
    if (!grid) return;
    // Remove empty-state if present
    const empty = document.getElementById('my-resources-empty');
    if (empty && empty.parentElement) {
        empty.parentElement.removeChild(empty);
    }
    const el = document.createElement('div');
    el.className = 'my-resource-card';
        el.setAttribute('data-id', resource.resource_id || '');
        el.dataset.title = resource.title || '';
        el.dataset.description = resource.description || '';
        el.dataset.category = resource.category || '';
        el.dataset.filePath = resource.file_path || '';
        el.dataset.fileSize = resource.file_size || 0;
        el.dataset.createdAt = resource.created_at || new Date().toISOString();
        const sizeText = resource.file_size ? formatFileSize(parseInt(resource.file_size, 10)) : '';
        const created = resource.created_at ? formatDate(resource.created_at) : formatDate(new Date().toISOString());
        const niceCategory = (resource.category || '').toString().replace(/-/g,' ').replace(/\b\w/g, c => c.toUpperCase());
        el.innerHTML = `
                <h3 class="resource-title">${escapeHTML(resource.title || '')}</h3>
                <div class="resource-meta">
                        <span class="resource-category">${escapeHTML(niceCategory)}</span>
                        <span class="resource-size">${sizeText}</span>
                </div>
                <p class="resource-description">${escapeHTML(resource.description || '')}</p>
                <div class="resource-details">
                        <span class="upload-date">Uploaded: ${created}</span>
                </div>
                <div class="resource-actions">
                        <button class="btn btn-primary btn-sm" data-action="edit" data-id="${resource.resource_id || ''}">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </button>
                        <a class="btn btn-outline btn-sm" href="${resource.file_path}" target="_blank" rel="noopener">
                            <i class="fas fa-download"></i>
                            <span>Open</span>
                        </a>
                        <button class="btn btn-outline btn-sm" data-action="delete" data-id="${resource.resource_id || ''}">
                            <i class="fas fa-trash"></i>
                            <span>Delete</span>
                        </button>
                </div>
        `;
    // Prepend to grid
    grid.prepend(el);

    // Update count
    const countEl = document.getElementById('my-resources-count');
    if (countEl) {
        const n = parseInt(countEl.textContent || '0', 10) + 1;
        countEl.textContent = String(n);
    }
}

function updateResourceInGrid(resource) {
    const card = document.querySelector(`.my-resource-card[data-id="${resource.resource_id}"]`);
    if (!card) return;
    // dataset
    if (resource.title) card.dataset.title = resource.title;
    if (resource.description) card.dataset.description = resource.description;
    if (resource.category) card.dataset.category = resource.category;
    if (resource.file_path) card.dataset.filePath = resource.file_path;
    if (resource.file_size) card.dataset.fileSize = resource.file_size;

    // visible
    const titleEl = card.querySelector('.resource-title');
    if (titleEl && resource.title) titleEl.textContent = resource.title;
    const catEl = card.querySelector('.resource-category');
    if (catEl && resource.category) {
        catEl.textContent = resource.category.replace(/-/g,' ').replace(/\b\w/g, c => c.toUpperCase());
    }
    const sizeEl = card.querySelector('.resource-size');
    if (sizeEl && resource.file_size) sizeEl.textContent = formatFileSize(parseInt(resource.file_size, 10));
    const descEl = card.querySelector('.resource-description');
    if (descEl && resource.description !== undefined) descEl.textContent = resource.description || '';
    const openA = card.querySelector('a.btn[href]');
    if (openA && resource.file_path) openA.href = resource.file_path;
}

// Delete resource with confirmation
function confirmDeleteResource(resourceId, title, card) {
    const deleteModal = document.getElementById('deleteModal');
    const deleteResourceName = document.getElementById('deleteResourceName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    // Set the resource name in the modal
    deleteResourceName.textContent = title;
    
    // Show the modal
    deleteModal.classList.add('show');
    
    // Remove any existing click handlers
    const newConfirmBtn = confirmDeleteBtn.cloneNode(true);
    confirmDeleteBtn.parentNode.replaceChild(newConfirmBtn, confirmDeleteBtn);
    
    // Add click handler for confirmation
    newConfirmBtn.addEventListener('click', function() {
        closeDeleteModal();
        deleteResource(resourceId, card);
    });
}

// Close delete modal
function closeDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.classList.remove('show');
}

function deleteResource(resourceId, card) {
    const base = (typeof window.APP_ROOT !== 'undefined') ? window.APP_ROOT : '';
    const url = base + '/alumni/resources/delete';
    
    const fd = new FormData();
    fd.set('resourceId', resourceId);
    
    // Show loading on the card
    if (card) card.style.opacity = '0.5';
    
    fetch(url, {
        method: 'POST',
        body: fd
    }).then(async (res) => {
        let data;
        try { data = await res.json(); } catch (e) { data = { success: false, message: 'Invalid server response' }; }
        if (!res.ok || !data.success) throw new Error(data.message || 'Delete failed');
        
        // Remove card from DOM
        if (card && card.parentElement) {
            card.remove();
        }
        
        // Update count
        const countEl = document.getElementById('my-resources-count');
        if (countEl) {
            const n = Math.max(0, parseInt(countEl.textContent || '0', 10) - 1);
            countEl.textContent = String(n);
        }
        
        // Check if grid is now empty and show empty state
        const grid = document.getElementById('my-resources-grid');
        if (grid && grid.children.length === 0) {
            const emptyCard = document.createElement('div');
            emptyCard.id = 'my-resources-empty';
            emptyCard.className = 'my-resource-card';
            emptyCard.style.opacity = '0.8';
            emptyCard.innerHTML = `
                <h3 class="resource-title">No resources yet</h3>
                <p class="resource-description">Upload your first resource to see it here.</p>
            `;
            grid.appendChild(emptyCard);
        }
        
        showNotification('Resource deleted successfully', 'success');
    }).catch(err => {
        console.error(err);
        if (card) card.style.opacity = '1';
        showNotification(err.message || 'Delete failed', 'error');
    });
}

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return d.toLocaleDateString(undefined, options);
}

function resetUploadForm() {
    if (uploadForm) {
        uploadForm.reset();
    }
    
    // Reset mode to create
    if (resourceModeInput) resourceModeInput.value = 'create';
    if (resourceIdInput) resourceIdInput.value = '';
    
    // Make file required again for create mode
    if (resourceFileInput) {
        resourceFileInput.setAttribute('required', 'required');
    }
    
    // Reset file display
    const placeholder = fileUploadArea.querySelector('.upload-placeholder');
    if (placeholder) {
        placeholder.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to select file or drag and drop</p>
            <small>Supported: PDF, DOC, DOCX, TXT, ZIP, RAR, PPT, PPTX, XLS, XLSX, PNG, JPG, JPEG, GIF</small>
        `;
    }
    
    // Reset submit button text
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-upload"></i> <span>Upload</span>';
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

