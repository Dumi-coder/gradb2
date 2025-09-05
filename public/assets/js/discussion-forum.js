// discussion-forum.js - Discussion Forum Page Functionality

// Global variables
let selectedTags = [];
let currentModal = null;

// DOM elements
const newPostModal = document.getElementById('newPostModal');
const quickTagsModal = document.getElementById('quickTagsModal');
const tagInput = document.getElementById('tagInput');
const tagsDisplay = document.getElementById('tagsDisplay');
const newPostForm = document.querySelector('.new-post-form');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeDiscussionForum();
});

function initializeDiscussionForum() {
    // Add event listeners
    if (tagInput) {
        tagInput.addEventListener('keydown', handleTagInput);
        tagInput.addEventListener('blur', handleTagInputBlur);
    }
    
    if (newPostForm) {
        newPostForm.addEventListener('submit', handleNewPostSubmit);
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
function openNewPostModal() {
    newPostModal.style.display = 'block';
    currentModal = 'newPost';
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    setTimeout(() => {
        document.getElementById('postTitle').focus();
    }, 100);
}

function closeNewPostModal() {
    newPostModal.style.display = 'none';
    currentModal = null;
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetNewPostForm();
}

function openQuickTagsModal() {
    quickTagsModal.style.display = 'block';
    currentModal = 'quickTags';
    document.body.style.overflow = 'hidden';
}

function closeQuickTagsModal() {
    quickTagsModal.style.display = 'none';
    currentModal = null;
    document.body.style.overflow = 'auto';
}

function closeAllModals() {
    if (currentModal === 'newPost') {
        closeNewPostModal();
    } else if (currentModal === 'quickTags') {
        closeQuickTagsModal();
    }
}

// Tag management functions
function handleTagInput(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const tagValue = tagInput.value.trim();
        
        if (tagValue) {
            addTag(tagValue);
            tagInput.value = '';
        }
    }
}

function handleTagInputBlur() {
    const tagValue = tagInput.value.trim();
    if (tagValue) {
        addTag(tagValue);
        tagInput.value = '';
    }
}

function addTag(tagText) {
    // Clean tag text (remove # if present, trim whitespace)
    let cleanTag = tagText.replace(/^#/, '').trim().toLowerCase();
    
    // Validate tag
    if (cleanTag.length < 2) {
        showNotification('Tag must be at least 2 characters long', 'error');
        return;
    }
    
    if (cleanTag.length > 20) {
        showNotification('Tag must be less than 20 characters', 'error');
        return;
    }
    
    // Check if tag already exists
    if (selectedTags.includes(cleanTag)) {
        showNotification('Tag already added', 'warning');
        return;
    }
    
    // Add tag
    selectedTags.push(cleanTag);
    renderTags();
    
    // Show success notification
    showNotification(`Tag "#${cleanTag}" added`, 'success');
}

function addQuickTag(tagName) {
    if (!selectedTags.includes(tagName)) {
        selectedTags.push(tagName);
        renderTags();
        showNotification(`Tag "#${tagName}" added`, 'success');
    } else {
        showNotification('Tag already added', 'warning');
    }
}

function removeTag(tagName) {
    const index = selectedTags.indexOf(tagName);
    if (index > -1) {
        selectedTags.splice(index, 1);
        renderTags();
        showNotification(`Tag "#${tagName}" removed`, 'info');
    }
}

function renderTags() {
    if (!tagsDisplay) return;
    
    tagsDisplay.innerHTML = '';
    
    selectedTags.forEach(tag => {
        const tagElement = document.createElement('span');
        tagElement.className = 'tag-item';
        tagElement.innerHTML = `
            #${tag}
            <button class="remove-tag" onclick="removeTag('${tag}')" title="Remove tag">
                <i class="fas fa-times"></i>
            </button>
        `;
        tagsDisplay.appendChild(tagElement);
    });
    
    // Update tag input placeholder
    if (tagInput) {
        tagInput.placeholder = selectedTags.length === 0 
            ? 'Type a tag and press Enter (e.g., #python, #machine-learning)'
            : 'Add more tags...';
    }
}

// Form handling
function handleNewPostSubmit(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(newPostForm);
    const postData = {
        title: formData.get('postTitle'),
        category: formData.get('postCategory'),
        content: formData.get('postContent'),
        priority: formData.get('postPriority'),
        tags: selectedTags,
        timestamp: new Date().toISOString()
    };
    
    // Validate form
    if (!validatePostData(postData)) {
        return;
    }
    
    // Submit post (simulate API call)
    submitPost(postData);
}

function validatePostData(postData) {
    if (!postData.title.trim()) {
        showNotification('Please enter a post title', 'error');
        return false;
    }
    
    if (!postData.category) {
        showNotification('Please select a category', 'error');
        return false;
    }
    
    if (!postData.content.trim()) {
        showNotification('Please enter post content', 'error');
        return false;
    }
    
    if (postData.title.length < 10) {
        showNotification('Title must be at least 10 characters long', 'error');
        return false;
    }
    
    if (postData.content.length < 50) {
        showNotification('Post content must be at least 50 characters long', 'error');
        return false;
    }
    
    return true;
}

function submitPost(postData) {
    // Show loading state
    const submitBtn = newPostForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showNotification('Post published successfully!', 'success');
        closeNewPostModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Add post to discussions list
        console.log('Post data:', postData);
        
    }, 1500);
}

function resetNewPostForm() {
    if (newPostForm) {
        newPostForm.reset();
    }
    selectedTags = [];
    renderTags();
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

// Search and filter functionality
function searchDiscussions(query) {
    // TODO: Implement search functionality
    console.log('Searching for:', query);
}

function filterDiscussions(category) {
    // TODO: Implement filter functionality
    console.log('Filtering by category:', category);
}

// Export functions for global access
window.openNewPostModal = openNewPostModal;
window.closeNewPostModal = closeNewPostModal;
window.openQuickTagsModal = openQuickTagsModal;
window.closeQuickTagsModal = closeQuickTagsModal;
window.addQuickTag = addQuickTag;
window.removeTag = removeTag;

