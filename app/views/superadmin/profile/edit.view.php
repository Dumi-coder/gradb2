<?php require '../app/views/partials/superadmin_header.php'; ?>

<?php $hasPasswordErrors = isset($errors['password_current']) || isset($errors['password_new']) || isset($errors['password_confirm']); ?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Edit Profile Form -->
        <section class="edit-form-section">
            <h2 class="section-title">Edit Super Admin Profile</h2>

            <!-- Success/Error Messages -->
            <?php if (isset($errors['success'])): ?>
                <div class="alert alert-success"><?= esc($errors['success']) ?></div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= esc($errors['general']) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" data-password-modal="true">
                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-preview">
                        <?php if (!empty($profile->picture_path)): ?>
                            <img src="<?= esc($profile->picture_path) ?>" alt="Profile Picture" id="profilePreview">
                        <?php else: ?>
                            <span id="profileInitials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="profile-picture-actions">
                        <label for="profile_picture" class="btn btn-primary profile-photo-trigger">
                            <i class="fas fa-camera"></i>
                            <span>Change Photo</span>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)" class="profile-file-input-hidden">
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
                        <input type="text" id="role" class="form-input" value="Super Administrator" disabled>
                        <small class="form-help">Role cannot be changed</small>
                    </div>

                    <!-- Admin Level -->
                    <div class="form-group">
                        <label for="admin_level" class="form-label">Admin Level</label>
                        <input type="text" id="admin_level" class="form-input" value="<?= esc($profile->admin_level ?? 'Super Admin') ?>" disabled>
                        <small class="form-help">Admin level cannot be changed</small>
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

                <input type="hidden" name="change_password" value="0">

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-outline js-open-password-modal" aria-expanded="false">
                        <i class="fas fa-key"></i>
                        Change Password
                    </button>
                    <a href="<?= ROOT ?>/superadmin/profile" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>

                <div class="change-password-modal" aria-hidden="true" data-has-password-errors="<?= $hasPasswordErrors ? '1' : '0' ?>">
                    <div class="change-password-modal-content" role="dialog" aria-modal="true" aria-labelledby="superadminChangePasswordTitle">
                        <div class="change-password-modal-header">
                            <h3 class="change-password-modal-title" id="superadminChangePasswordTitle">Change Password</h3>
                            <button type="button" class="btn btn-outline js-close-password-modal">Close</button>
                        </div>
                        <div class="change-password-modal-body">
                            <div class="form-group">
                                <label for="superadmin_password_current" class="form-label">Current Password</label>
                                <input type="password" id="superadmin_password_current" name="password_current" class="form-input" autocomplete="current-password">
                                <?php if (isset($errors['password_current'])): ?>
                                    <div class="error-message"><?= esc($errors['password_current']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="superadmin_password_new" class="form-label">New Password</label>
                                <input type="password" id="superadmin_password_new" name="password_new" class="form-input" autocomplete="new-password">
                                <?php if (isset($errors['password_new'])): ?>
                                    <div class="error-message"><?= esc($errors['password_new']) ?></div>
                                <?php endif; ?>
                                <div class="js-password-strength-container" id="superadmin-password-strength-widget"></div>
                            </div>
                            <div class="form-group">
                                <label for="superadmin_password_confirm" class="form-label">Confirm New Password</label>
                                <input type="password" id="superadmin_password_confirm" name="password_confirm" class="form-input" autocomplete="new-password">
                                <?php if (isset($errors['password_confirm'])): ?>
                                    <div class="error-message"><?= esc($errors['password_confirm']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="change-password-modal-footer">
                                <button type="button" class="btn btn-outline js-done-password-modal">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>

<!-- Delete Photo Form (Hidden) -->
<form id="deletePhotoForm" action="<?= ROOT ?>/superadmin/profile?action=delete_photo" method="POST" class="profile-delete-form-hidden">
    <input type="hidden" name="delete_photo" value="1">
</form>

<!-- Unified Profile JavaScript -->
<script src="<?=ROOT?>/assets/js/profile.js"></script>
<script src="<?=ROOT?>/assets/js/password-validation.js"></script>
<script src="<?=ROOT?>/assets/js/profile-password-modal.js"></script>
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
