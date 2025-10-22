<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Edit Profile Form -->
        <section class="edit-form-section">
            <h2 class="section-title">Edit Admin Profile</h2>
            
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
                    <label for="profile_picture" class="profile-picture-upload">
                        <i class="fas fa-camera"></i>
                        Change Photo
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)">
                    </label>
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

                    <!-- Department -->
                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" id="department" name="department" class="form-input" value="<?= esc($profile->department ?? 'Educational Technology') ?>">
                        <?php if (isset($errors['department'])): ?>
                            <div class="error-message"><?= esc($errors['department']) ?></div>
                        <?php endif; ?>
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
                            <label for="website_url" class="form-label">Personal Website</label>
                            <input type="url" id="website_url" name="website_url" class="form-input" value="<?= esc($profile->website_url ?? '') ?>" placeholder="https://yourwebsite.com">
                            <?php if (isset($errors['website_url'])): ?>
                                <div class="error-message"><?= esc($errors['website_url']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($success)): ?>
                    <div class="success-message"><?= esc($success) ?></div>
                <?php endif; ?>
                
                <?php if (isset($errors['general'])): ?>
                    <div class="error-message"><?= esc($errors['general']) ?></div>
                <?php endif; ?>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?= ROOT ?>/superadmin/profile" class="btn btn-outline">
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

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePreview');
            const initials = document.getElementById('profileInitials');
            
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            if (initials) {
                initials.style.display = 'none';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
