<?php 
$page_title = isset($data['is_edit']) && $data['is_edit'] ? "Edit Mentorship Request" : "Request Mentorship";
$page_subtitle = isset($data['is_edit']) && $data['is_edit'] ? "Update your mentorship request" : "Connect with alumni mentors";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship-request.css">

<!-- Main Content -->
<main class="main-content">
        <div class="container">
            <div class="mentorship-form-container">
                <div class="mentorship-form-card">
                    <h2 class="form-title">
                        <?= isset($data['is_edit']) && $data['is_edit'] ? 'Edit Mentorship Request' : 'Request Mentorship' ?>
                    </h2>
                    <p class="form-description">
                        <?= isset($data['is_edit']) && $data['is_edit'] 
                            ? 'Update your mentorship request details below' 
                            : 'Connect with alumni who can guide you in your academic and professional journey' ?>
                    </p>
                    
                    <!-- Display success/error messages -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form class="mentorship-form" method="POST" action="<?= isset($data['is_edit']) && $data['is_edit'] 
                        ? ROOT . '/student/MentorshipReq/edit/' . $data['request']['request_id'] 
                        : ROOT . '/student/MentorshipReq/create' ?>">
                        
                        <div class="form-group">
                            <label for="mentorship_category" class="form-label">
                                Select Mentorship Category <span class="required">*</span>
                            </label>
                            <select id="mentorship_category" name="mentorship_category" class="form-select" required>
                                <option value="">Choose a category...</option>
                                <option value="academic" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'academic') ? 'selected' : '' ?>>Academic Guidance</option>
                                <option value="career" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'career') ? 'selected' : '' ?>>Career Development</option>
                                <option value="research" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'research') ? 'selected' : '' ?>>Research & Projects</option>
                                <option value="networking" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'networking') ? 'selected' : '' ?>>Professional Networking</option>
                                <option value="skills" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'skills') ? 'selected' : '' ?>>Technical Skills</option>
                                <option value="leadership" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'leadership') ? 'selected' : '' ?>>Leadership & Management</option>
                                <option value="entrepreneurship" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'entrepreneurship') ? 'selected' : '' ?>>Entrepreneurship</option>
                                <option value="other" <?= (isset($data['request']) && $data['request']['mentorship_category'] === 'other') ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="other-category-group" style="display: none;">
                            <label for="other_mentorship_category" class="form-label">
                                Specify your mentorship category <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="other_mentorship_category" 
                                name="other_mentorship_category" 
                                class="form-input" 
                                placeholder="Please mention your specific mentorship category..."
                                value="<?= (isset($data['request']) && $data['request']['mentorship_category'] === 'other') ? htmlspecialchars($data['request']['other_category']) : '' ?>"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="request_reason" class="form-label">
                                Describe what you need help with <span class="required">*</span>
                            </label>
                            <textarea 
                                id="request_reason" 
                                name="request_reason" 
                                class="form-textarea" 
                                rows="4" 
                                placeholder="I want to understand how to approach the XYZ topic in my project..."
                                required
                            ><?= isset($data['request']) ? htmlspecialchars($data['request']['request_reason']) : '' ?></textarea>
                        </div>
                        
                        <div class="form-actions" style="display: flex; gap: var(--spacing-md); justify-content: center; margin-top: var(--spacing-xl);">
                            <a href="<?=ROOT?>/student/Mentorship" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Mentorship</span>
                            </a>
                            
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span><?= isset($data['is_edit']) && $data['is_edit'] ? 'Update Request' : 'Submit Mentorship Request' ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Handle mentorship category selection
        document.getElementById('mentorship_category').addEventListener('change', function() {
            const otherCategoryGroup = document.getElementById('other-category-group');
            const otherCategoryInput = document.getElementById('other_mentorship_category');
            
            if (this.value === 'other') {
                otherCategoryGroup.style.display = 'block';
                otherCategoryInput.required = true;
            } else {
                otherCategoryGroup.style.display = 'none';
                otherCategoryInput.required = false;
                otherCategoryInput.value = ''; // Clear the input when hiding
            }
        });

        // Initialize the form based on existing data
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('mentorship_category');
            if (categorySelect.value === 'other') {
                document.getElementById('other-category-group').style.display = 'block';
                document.getElementById('other_mentorship_category').required = true;
            }
        });

        // Form validation
        document.querySelector('.mentorship-form').addEventListener('submit', function(e) {
            const requestReason = document.getElementById('request_reason').value.trim();
            const mentorshipCategory = document.getElementById('mentorship_category').value;
            const otherCategory = document.getElementById('other_mentorship_category').value.trim();
            
            if (!mentorshipCategory) {
                e.preventDefault();
                alert('Please select a mentorship category.');
                return false;
            }
            
            if (mentorshipCategory === 'other' && !otherCategory) {
                e.preventDefault();
                alert('Please specify your mentorship category.');
                return false;
            }
            
            if (requestReason.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed description (at least 10 characters).');
                return false;
            }
        });
    </script>
</body>
</html>
