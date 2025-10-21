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
});

