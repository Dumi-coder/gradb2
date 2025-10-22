<?php 
$page_title = "Edit Profile";
$page_subtitle = "Update your profile information";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
        <!-- Sidebar -->
        <?php require '../app/views/partials/alumni_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Edit Profile Form -->
            <section class="edit-form-section">
                <h2 class="section-title">Edit Profile Information</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <!-- Profile Picture Section -->
                    <div class="profile-picture-section">
                        <div class="profile-picture-preview">
                            <?php if (!empty($profile->profile_photo_url)): ?>
                                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture" id="profilePreview">
                            <?php else: ?>
                                <span id="profileInitials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <label for="profile_picture" class="profile-picture-upload">
                                <i class="fas fa-camera"></i>
                                Change Photo
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)">
                            </label>
                            <?php if (!empty($profile->profile_photo_url)): ?>
                                <button type="button" onclick="deleteProfilePicture()" class="btn-delete-photo" style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-trash"></i>
                                    Delete Photo
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

                        <!-- Degrees -->
                        <div class="form-group">
                            <label for="degrees" class="form-label">Degree *</label>
                            <input type="text" id="degrees" name="degrees" class="form-input" value="<?= esc($profile->degrees ?? '') ?>" required>
                            <?php if (isset($errors['degrees'])): ?>
                                <div class="error-message"><?= esc($errors['degrees']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Graduation Year -->
                        <div class="form-group">
                            <label for="graduation_year" class="form-label">Graduation Year *</label>
                            <input type="number" id="graduation_year" name="graduation_year" class="form-input" value="<?= esc($profile->graduation_year ?? '') ?>" min="1950" max="<?= date('Y') ?>" required>
                            <?php if (isset($errors['graduation_year'])): ?>
                                <div class="error-message"><?= esc($errors['graduation_year']) ?></div>
                            <?php endif; ?>
                            <!-- Client-side validation error placeholder -->
                            <div id="graduation_year_error" class="error-message" style="display:none;"></div>
                        </div>

                        <!-- Current Workplace -->
                        <div class="form-group">
                            <label for="current_workplace" class="form-label">Current Workplace</label>
                            <input type="text" id="current_workplace" name="current_workplace" class="form-input" value="<?= esc($profile->current_workplace ?? '') ?>">
                            <?php if (isset($errors['current_workplace'])): ?>
                                <div class="error-message"><?= esc($errors['current_workplace']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Current Job Title -->
                        <div class="form-group">
                            <label for="current_job" class="form-label">Current Job Title</label>
                            <input type="text" id="current_job" name="current_job" class="form-input" value="<?= esc($profile->current_job ?? '') ?>">
                            <?php if (isset($errors['current_job'])): ?>
                                <div class="error-message"><?= esc($errors['current_job']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Expertise Area -->
                        <div class="form-group">
                            <label for="expertise_area" class="form-label">Expertise Area</label>
                            <input type="text" id="expertise_area" name="expertise_area" class="form-input" value="<?= esc($profile->expertise_area ?? '') ?>" placeholder="e.g., Web Development, Data Science">
                            <?php if (isset($errors['expertise_area'])): ?>
                                <div class="error-message"><?= esc($errors['expertise_area']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Mobile Number -->
                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="padding: 12px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 5px; font-weight: 500;">+94</span>
                                <input type="tel" id="mobile" name="mobile" class="form-input" style="flex: 1;" value="<?= esc($profile->mobile ?? '') ?>" placeholder="771234567" maxlength="9">
                            </div>
                            <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Enter 9 digits without leading 0 (e.g., 771234567)</small>
                            <?php if (isset($errors['mobile'])): ?>
                                <div class="error-message"><?= esc($errors['mobile']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Bio -->
                        <div class="form-group full-width">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea id="bio" name="bio" class="form-textarea" placeholder="Tell us about yourself..."><?= esc($profile->bio ?? '') ?></textarea>
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
                                <label for="personal_website" class="form-label">Personal Website</label>
                                <input type="url" id="personal_website" name="personal_website" class="form-input" value="<?= esc($profile->personal_website ?? '') ?>" placeholder="https://yourwebsite.com">
                                <?php if (isset($errors['personal_website'])): ?>
                                    <div class="error-message"><?= esc($errors['personal_website']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if (isset($errors['success'])): ?>
                        <div class="success-message"><?= esc($errors['success']) ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['general'])): ?>
                        <div class="error-message"><?= esc($errors['general']) ?></div>
                    <?php endif; ?>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= ROOT ?>/alumni/profile" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Cancel
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


    <!-- Delete Confirmation Modal -->
    <div id="deletePhotoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 10px; padding: 30px; max-width: 400px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            <div style="font-size: 50px; color: #e74c3c; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 24px;">Delete Profile Picture?</h3>
            <p style="color: #7f8c8d; margin: 0 0 30px 0;">Are you sure you want to delete your profile picture? This action cannot be undone.</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="closeDeleteModal()" style="padding: 12px 30px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: 500;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button onclick="confirmDeletePhoto()" style="padding: 12px 30px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: 500;">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Unified Profile JavaScript -->
    <script src="<?=ROOT?>/assets/js/profile.js"></script>
    <script>
        function deleteProfilePicture() {
            // Show the custom modal
            document.getElementById('deletePhotoModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            // Hide the modal
            document.getElementById('deletePhotoModal').style.display = 'none';
        }

        function confirmDeletePhoto() {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?=ROOT?>/alumni/profile?action=delete_photo';
            
            // Add hidden input for delete action
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_photo';
            deleteInput.value = '1';
            form.appendChild(deleteInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('deletePhotoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

    
</body>
</html>

<script>
// Graduation year client-side validation
(function(){
    const gradInput = document.getElementById('graduation_year');
    const errorEl = document.getElementById('graduation_year_error');
    const form = document.querySelector('form[method="POST"]');
    const currentYear = new Date().getFullYear();

    if (gradInput) {
        // Ensure max attribute matches current year
        gradInput.max = currentYear;

        function validateYear() {
            const val = parseInt(gradInput.value, 10);
            if (!isNaN(val) && val > currentYear) {
                errorEl.style.display = 'block';
                errorEl.textContent = 'Graduation year cannot be in the future (max ' + currentYear + ').';
                return false;
            }
            errorEl.style.display = 'none';
            errorEl.textContent = '';
            return true;
        }

        gradInput.addEventListener('input', validateYear);
        gradInput.addEventListener('change', validateYear);

        if (form) {
            form.addEventListener('submit', function(e){
                if (!validateYear()) {
                    e.preventDefault();
                    gradInput.focus();
                }
            });
        }
    }
})();
</script>
