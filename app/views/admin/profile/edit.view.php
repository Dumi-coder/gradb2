<?php require '../app/views/partials/admin_header.php'; ?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Edit Profile Form -->
        <section class="edit-form-section">
            <h2 class="section-title">Edit Admin Profile</h2>

            <!-- Success/Error Messages -->
            <?php if (isset($errors['success'])): ?>
                <div class="alert alert-success"><?= esc($errors['success']) ?></div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= esc($errors['general']) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-preview">
                        <?php if (!empty($profile->picture_path)): ?>
                            <img src="<?= esc($profile->picture_path) ?>" alt="Profile Picture" id="profilePreview">
                        <?php else: ?>
                            <span id="profileInitials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <label for="profile_picture" class="btn btn-primary" style="cursor: pointer;">
                            <i class="fas fa-camera"></i>
                            <span>Change Photo</span>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)" style="display: none;">
                        </label>
                        <?php if (!empty($profile->picture_path)): ?>
                            <button type="button" onclick="deleteProfilePicture()" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                <span>Delete Photo</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($errors['profile_picture'])): ?>
                        <div class="error-message"><?= esc($errors['profile_picture']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-grid">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-input" value="<?= esc($profile->name) ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="error-message"><?= esc($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?= esc($profile->email) ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="error-message"><?= esc($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" id="role" class="form-input" value="Faculty Administrator" disabled>
                        <small class="form-help">Role cannot be changed</small>
                    </div>

                    <!-- Faculty -->
                    <div class="form-group">
                        <label for="faculty" class="form-label">Faculty</label>
                        <input type="text" id="faculty" class="form-input" value="<?= esc($profile->faculty_name ?? 'Not Assigned') ?>" disabled>
                        <small class="form-help">Faculty assignment cannot be changed</small>
                    </div>

                    <!-- Bio -->
                    <div class="form-group full-width">
                        <label for="bio" class="form-label">Professional Bio</label>
                        <textarea id="bio" name="bio" class="form-textarea" placeholder="Tell us about your professional background..."><?= esc($profile->bio ?? '') ?></textarea>
                        <?php if (isset($errors['bio'])): ?>
                            <div class="error-message"><?= esc($errors['bio']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Social Media Links Section -->
                <div class="social-links-section">
                    <h3 class="form-section-title">Social Media Links</h3>
                    <div class="social-form-grid">
                        <div class="form-group">
                            <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                            <input type="url" id="linkedin_url" name="linkedin_url" class="form-input" value="<?= esc($profile->linkedin_url ?? '') ?>" placeholder="https://linkedin.com/in/yourprofile">
                            <?php if (isset($errors['linkedin_url'])): ?>
                                <div class="error-message"><?= esc($errors['linkedin_url']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="github_url" class="form-label">GitHub URL</label>
                            <input type="url" id="github_url" name="github_url" class="form-input" value="<?= esc($profile->github_url ?? '') ?>" placeholder="https://github.com/yourusername">
                            <?php if (isset($errors['github_url'])): ?>
                                <div class="error-message"><?= esc($errors['github_url']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="twitter_url" class="form-label">Twitter URL</label>
                            <input type="url" id="twitter_url" name="twitter_url" class="form-input" value="<?= esc($profile->twitter_url ?? '') ?>" placeholder="https://twitter.com/yourusername">
                            <?php if (isset($errors['twitter_url'])): ?>
                                <div class="error-message"><?= esc($errors['twitter_url']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="personalweb_url" class="form-label">Personal Website</label>
                            <input type="url" id="personalweb_url" name="personalweb_url" class="form-input" value="<?= esc($profile->personalweb_url ?? '') ?>" placeholder="https://yourwebsite.com">
                            <?php if (isset($errors['personalweb_url'])): ?>
                                <div class="error-message"><?= esc($errors['personalweb_url']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?= ROOT ?>/admin/profile" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>

<!-- Delete Photo Form (Hidden) -->
<form id="deletePhotoForm" action="<?= ROOT ?>/admin/profile?action=delete_photo" method="POST" style="display: none;">
    <input type="hidden" name="delete_photo" value="1">
</form>

<!-- Unified Profile JavaScript -->
<script src="<?=ROOT?>/assets/js/profile.js"></script>
<script>
function deleteProfilePicture() {
    if (confirm('Are you sure you want to delete your profile picture?')) {
        document.getElementById('deletePhotoForm').submit();
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('profilePreview');
            var initials = document.getElementById('profileInitials');
            
            if (preview) {
                preview.src = e.target.result;
            } else if (initials) {
                // Replace initials with image
                var container = initials.parentElement;
                initials.remove();
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile Picture';
                img.id = 'profilePreview';
                container.appendChild(img);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
