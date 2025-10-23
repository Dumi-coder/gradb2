<?php 
$page_title = "Edit Profile";
$page_subtitle = "Update your information";
require '../app/views/partials/student_header.php'; 
?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/profile.css">

<div class="dashboard-container">
        <?php require '../app/views/partials/student_sidebar.php'; ?>
        
        <main class="main-content">
            <section class="edit-form-section">
                <h2 class="section-title">Edit Profile Information</h2>
                
                <form method="POST" class="profile-edit-form" enctype="multipart/form-data">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert <?= isset($errors['success']) ? 'alert-success' : 'alert-danger' ?>">
                            <?php foreach ($errors as $field => $error): ?>
                                <p><?= esc($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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
                            <label for="profile_picture" class="btn btn-primary" style="cursor: pointer;">
                                <i class="fas fa-camera"></i>
                                <span>Change Photo</span>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)" style="display: none;">
                            </label>
                            <?php if (!empty($profile->profile_photo_url)): ?>
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
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                class="form-input <?= isset($errors['name']) ? 'error' : '' ?>"
                                value="<?= esc($profile->name) ?>"
                                required
                            >
                            <?php if (isset($errors['name'])): ?>
                                <span class="error-message"><?= esc($errors['name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="faculty" class="form-label">Faculty *</label>
                            <select
                                name="faculty" 
                                id="faculty"
                                class="form-input <?= isset($errors['faculty']) ? 'error' : '' ?>"
                                required
                            >
                                <option value="">Select Faculty</option>
                                <option value="UCSC" <?= $profile->faculty == 'UCSC' ? 'selected' : '' ?>>UCSC</option>
                                <option value="FOS" <?= $profile->faculty == 'FOS' ? 'selected' : '' ?>>FOS</option>
                                <option value="FOM" <?= $profile->faculty == 'FOM' ? 'selected' : '' ?>>FOM</option>
                                <option value="FOMF" <?= $profile->faculty == 'FOMF' ? 'selected' : '' ?>>FOMF</option>
                                <option value="FOL" <?= $profile->faculty == 'FOL' ? 'selected' : '' ?>>FOL</option>
                                <option value="FOE" <?= $profile->faculty == 'FOE' ? 'selected' : '' ?>>FOE</option>
                                <option value="FOT" <?= $profile->faculty == 'FOT' ? 'selected' : '' ?>>FOT</option>
                            </select>
                            <?php if (isset($errors['faculty'])): ?>
                                <span class="error-message"><?= esc($errors['faculty']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="academic_year" class="form-label">Academic Year *</label>
                            <select 
                                id="academic_year"
                                name="academic_year" 
                                class="form-input <?= isset($errors['academic_year']) ? 'error' : '' ?>"
                                required
                            >
                                <option value="">Select Year</option>
                                <option value="1" <?= $profile->academic_year == 1 ? 'selected' : '' ?>>Year 1</option>
                                <option value="2" <?= $profile->academic_year == 2 ? 'selected' : '' ?>>Year 2</option>
                                <option value="3" <?= $profile->academic_year == 3 ? 'selected' : '' ?>>Year 3</option>
                                <option value="4" <?= $profile->academic_year == 4 ? 'selected' : '' ?>>Year 4</option>
                            </select>
                            <?php if (isset($errors['academic_year'])): ?>
                                <span class="error-message"><?= esc($errors['academic_year']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="student_id" class="form-label">Student ID *</label>
                            <input 
                                type="text" 
                                id="student_id"
                                name="student_id" 
                                class="form-input"
                                value="<?= esc($profile->student_id) ?>"
                                required
                            >
                            <?php if (isset($errors['student_id'])): ?>
                                <span class="error-message"><?= esc($errors['student_id']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                class="form-input <?= isset($errors['email']) ? 'error' : '' ?>"
                                value="<?= esc($profile->email) ?>"
                                required
                            >
                            <?php if (isset($errors['email'])): ?>
                                <span class="error-message"><?= esc($errors['email']) ?></span>
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

                        <div class="form-group full-width">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea 
                                id="bio"
                                name="bio" 
                                class="form-textarea <?= isset($errors['bio']) ? 'error' : '' ?>"
                                rows="4"
                                placeholder="Tell us about yourself, your interests, and goals..."
                            ><?= esc($profile->bio) ?></textarea>
                            <?php if (isset($errors['bio'])): ?>
                                <span class="error-message"><?= esc($errors['bio']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Social Media Links Section -->
                    <div class="social-links-section">
                        <h3 class="form-section-title">Social Media Links</h3>
                        <div class="social-form-grid">
                            <div class="form-group">
                                <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" class="form-input" value="<?= esc($profile->LinkedIn ?? '') ?>" placeholder="https://linkedin.com/in/yourprofile">
                                <?php if (isset($errors['linkedin_url'])): ?>
                                    <div class="error-message"><?= esc($errors['linkedin_url']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="github_url" class="form-label">GitHub URL</label>
                                <input type="url" id="github_url" name="github_url" class="form-input" value="<?= esc($profile->GitHub ?? '') ?>" placeholder="https://github.com/yourusername">
                                <?php if (isset($errors['github_url'])): ?>
                                    <div class="error-message"><?= esc($errors['github_url']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="form_submitted" value="1">
                    
                    <div class="form-actions">
                        <a href="<?= ROOT ?>/student/profile" class="btn btn-outline">
                            <i class="fas fa-times"></i>
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
                <button onclick="closeDeleteModal()" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
                <button onclick="confirmDeletePhoto()" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
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
            form.action = '<?=ROOT?>/student/profile?action=delete_photo';
            
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
