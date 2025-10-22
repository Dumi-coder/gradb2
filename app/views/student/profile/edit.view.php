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
                            <?php if (!empty($profile->profile_photo_url) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/profiles/' . basename($profile->profile_photo_url))): ?>
                                <img src="<?= ROOT . '/assets/uploads/profiles/' . basename($profile->profile_photo_url) ?>" 
                                     alt="Current Profile Picture" 
                                     id="profilePreview">
                            <?php else: ?>
                                <span id="profileInitials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                            <?php endif; ?>
                        </div>
                        <label for="profile_picture" class="profile-picture-upload">
                            <i class="fas fa-camera"></i>
                            Change Photo
                            <input type="file" 
                                   id="profile_picture"
                                   name="profile_picture" 
                                   accept="image/*"
                                   onchange="previewImage(this)">
                        </label>
                        <?php if (isset($errors['profile_picture'])): ?>
                            <span class="error-message"><?= esc($errors['profile_picture']) ?></span>
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

                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input
                                type="text"
                                id="mobile"
                                name="mobile" 
                                class="form-input <?= isset($errors['mobile']) ? 'error' : '' ?>"
                                value="<?= esc($profile->mobile) ?>"
                            >
                            <?php if (isset($errors['mobile'])): ?>
                                <span class="error-message"><?= esc($errors['mobile']) ?></span>
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
                                <label for="website_url" class="form-label">Personal Website</label>
                                <input type="url" id="website_url" name="website_url" class="form-input" value="<?= esc($profile->website_url ?? '') ?>" placeholder="https://yourwebsite.com">
                                <?php if (isset($errors['website_url'])): ?>
                                    <div class="error-message"><?= esc($errors['website_url']) ?></div>
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
    
    <!-- Unified Profile JavaScript -->
    <script src="<?=ROOT?>/assets/js/profile.js"></script>
    
</body>
</html>
