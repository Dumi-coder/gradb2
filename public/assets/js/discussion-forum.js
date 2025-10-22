// Discussion Forum JavaScript with REAL AJAX

// Global variables
let currentTags = [];
let editTags = [];
let currentEditPostId = null;

// Modal functions
function openNewPostModal() {
    document.getElementById('newPostModal').style.display = 'block';
    currentTags = [];
    document.getElementById('tagsDisplay').innerHTML = '';
}

function closeNewPostModal() {
    document.getElementById('newPostModal').style.display = 'none';
    document.querySelector('.new-post-form').reset();
    currentTags = [];
    document.getElementById('tagsDisplay').innerHTML = '';
}

function openEditPostModal() {
    document.getElementById('editPostModal').style.display = 'block';
}

function closeEditPostModal() {
    document.getElementById('editPostModal').style.display = 'none';
    document.getElementById('editPostForm').reset();
    editTags = [];
    document.getElementById('editTagsDisplay').innerHTML = '';
    currentEditPostId = null;
}

function openQuickTagsModal() {
    document.getElementById('quickTagsModal').style.display = 'block';
}

function closeQuickTagsModal() {
    document.getElementById('quickTagsModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const newPostModal = document.getElementById('newPostModal');
    const editPostModal = document.getElementById('editPostModal');
    const quickTagsModal = document.getElementById('quickTagsModal');
    
    if (event.target === newPostModal) closeNewPostModal();
    if (event.target === editPostModal) closeEditPostModal();
    if (event.target === quickTagsModal) closeQuickTagsModal();
}

// Tag Management
function renderTags(container, tags, type) {
    container.innerHTML = '';
    tags.forEach((tag, index) => {
        const tagItem = document.createElement('div');
        tagItem.className = 'tag-item';
        tagItem.innerHTML = `
            #${tag}
            <button type="button" class="remove-tag" onclick="removeTag(${index}, '${type}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(tagItem);
    });
}

function removeTag(index, type) {
    if (type === 'new') {
        currentTags.splice(index, 1);
        renderTags(document.getElementById('tagsDisplay'), currentTags, 'new');
    } else if (type === 'edit') {
        editTags.splice(index, 1);
        renderTags(document.getElementById('editTagsDisplay'), editTags, 'edit');
    }
}

function addQuickTag(tag) {
    if (!currentTags.includes(tag)) {
        currentTags.push(tag);
        renderTags(document.getElementById('tagsDisplay'), currentTags, 'new');
    }
    closeQuickTagsModal();
}

// When page loads
document.addEventListener('DOMContentLoaded', function() {
    
    // Tag input for new post
    const tagInput = document.getElementById('tagInput');
    const tagsDisplay = document.getElementById('tagsDisplay');
    
    if (tagInput) {
        tagInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = this.value.trim().replace('#', '');
                if (tag && !currentTags.includes(tag)) {
                    currentTags.push(tag);
                    renderTags(tagsDisplay, currentTags, 'new');
                    this.value = '';
                }
            }
        });
    }

    // Tag input for edit post
    const editTagInput = document.getElementById('editTagInput');
    const editTagsDisplay = document.getElementById('editTagsDisplay');
    
    if (editTagInput) {
        editTagInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = this.value.trim().replace('#', '');
                if (tag && !editTags.includes(tag)) {
                    editTags.push(tag);
                    renderTags(editTagsDisplay, editTags, 'edit');
                    this.value = '';
                }
            }
        });
    }

    // Form submission handlers
    const newPostForm = document.querySelector('.new-post-form');
    if (newPostForm) {
        newPostForm.addEventListener('submit', handleNewPostSubmit);
    }

    const editPostForm = document.getElementById('editPostForm');
    if (editPostForm) {
        editPostForm.addEventListener('submit', handleEditPostSubmit);
    }
});

// CREATE NEW POST (REAL AJAX)
async function handleNewPostSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('title', document.getElementById('postTitle').value);
    formData.append('content', document.getElementById('postContent').value);
    formData.append('category', document.getElementById('postCategory').value);
    formData.append('priority', document.getElementById('postPriority').value);
    formData.append('tags', currentTags.join(','));
    
    try {
        // FIXED URL with /public/Student/DiscussionForum/
        const response = await fetch(window.location.origin + '/gradb2/public/Student/DiscussionForum/create', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('success', result.message);
            closeNewPostModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('error', result.message);
        }
    } catch (error) {
        showNotification('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    }
}

// EDIT POST (Load data)
async function editPost(postId) {
    currentEditPostId = postId;
    
    try {
        // FIXED URL
        const response = await fetch(window.location.origin + `/gradb2/public/Student/DiscussionForum/get?post_id=${postId}`);
        const result = await response.json();
        
        if (result.success) {
            const post = result.post;
            
            document.getElementById('editPostId').value = post.post_id;
            document.getElementById('editPostTitle').value = post.title;
            document.getElementById('editPostContent').value = post.content;
            document.getElementById('editPostCategory').value = post.category;
            document.getElementById('editPostPriority').value = post.priority;
            
            editTags = post.tags ? post.tags.split(',').filter(t => t.trim()) : [];
            renderTags(document.getElementById('editTagsDisplay'), editTags, 'edit');
            
            openEditPostModal();
        } else {
            showNotification('error', result.message);
        }
    } catch (error) {
        showNotification('error', 'Failed to load post data.');
        console.error('Error:', error);
    }
}

// UPDATE POST (REAL AJAX)
async function handleEditPostSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('post_id', currentEditPostId);
    formData.append('title', document.getElementById('editPostTitle').value);
    formData.append('content', document.getElementById('editPostContent').value);
    formData.append('category', document.getElementById('editPostCategory').value);
    formData.append('priority', document.getElementById('editPostPriority').value);
    formData.append('tags', editTags.join(','));
    
    try {
        // FIXED URL
        const response = await fetch(window.location.origin + '/gradb2/public/Student/DiscussionForum/update', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('success', result.message);
            closeEditPostModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('error', result.message);
        }
    } catch (error) {
        showNotification('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    }
}

// DELETE POST (REAL AJAX)
async function deletePost(postId) {
    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('post_id', postId);
    
    try {
        // FIXED URL
        const response = await fetch(window.location.origin + '/gradb2/public/Student/DiscussionForum/delete', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('success', result.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('error', result.message);
        }
    } catch (error) {
        showNotification('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    }
}

// NOTIFICATION SYSTEM
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type} show`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}