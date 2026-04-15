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

/**
 * Initialize on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    // Any additional profile initialization can go here
    console.log('Profile page loaded');

    const autoOpenModal = document.querySelector('.password-modal-backdrop[data-open="1"]');
    if (autoOpenModal) {
        openPasswordModal(autoOpenModal.id);
    }
});

function openPasswordModal(modalId = 'passwordChangeModal') {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    const firstInput = modal.querySelector('input, textarea, select') || modal.querySelector('button');
    if (firstInput) {
        firstInput.focus();
    }
}

function closePasswordModal(modalId = 'passwordChangeModal') {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') {
        return;
    }

    const openModal = document.querySelector('.password-modal-backdrop[style*="display: flex"]');
    if (openModal) {
        closePasswordModal(openModal.id);
    }
});

document.addEventListener('click', function(event) {
    const modal = event.target.closest('.password-modal-backdrop');
    if (modal && event.target === modal) {
        closePasswordModal(modal.id);
    }
});

window.openPasswordModal = openPasswordModal;
window.closePasswordModal = closePasswordModal;

