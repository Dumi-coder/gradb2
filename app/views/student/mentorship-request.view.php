<?php 
$page_title = "Request Mentorship";
$page_subtitle = "Connect with your selected mentor";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship-request.css">

<!-- Main Content -->
<main class="main-content">
        <div class="container">
            <div class="mentorship-form-container">
                <div class="mentorship-form-card">
                    <h2 class="form-title">Request Mentorship</h2>
                    <p class="form-description">Send your request directly to this mentor.</p>

                    <?php if (!empty($data['mentor'])): ?>
                        <div class="selected-mentor-box">
                            <h3 class="selected-mentor-name"><?= esc($data['mentor']['mentor_name']) ?></h3>
                            <p class="selected-mentor-meta">
                                <?= esc($data['mentor']['faculty_name'] ?: 'Faculty N/A') ?>
                                <?php if (!empty($data['mentor']['current_job'])): ?>
                                    | <?= esc($data['mentor']['current_job']) ?>
                                <?php endif; ?>
                            </p>
                            <p class="selected-mentor-expertise"><?= esc($data['mentor']['expertise_area'] ?: 'General Mentorship') ?></p>
                            <p class="selected-mentor-meta">Email: <?= esc($data['mentor']['mentor_email'] ?? 'N/A') ?></p>
                            <?php if (!empty($data['mentor']['mentor_mobile'])): ?>
                                <p class="selected-mentor-meta">Mobile: +94 <?= esc($data['mentor']['mentor_mobile']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($data['mentor']['mentor_linkedin_url'])): ?>
                                <p class="selected-mentor-meta">LinkedIn: <a href="<?= esc($data['mentor']['mentor_linkedin_url']) ?>" target="_blank" rel="noopener">View profile</a></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
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
                    
                    <form class="mentorship-form" method="POST" action="<?=ROOT?>/student/Mentorship/sendRequest">
                        <input type="hidden" name="mentor_user_id" value="<?= (int)($data['mentor']['mentor_user_id'] ?? 0) ?>">
                        
                        <div class="form-group">
                            <label for="topic" class="form-label">
                                Topic <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="topic"
                                name="topic"
                                class="form-input"
                                placeholder="e.g., Internships, Career Advice, CV Review"
                                value="<?= esc($_POST['topic'] ?? '') ?>"
                                required
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
                                placeholder="Describe what you need help with and what outcome you expect."
                                required
                            ><?= esc($_POST['request_reason'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-actions actions-row">
                            <a href="<?=ROOT?>/student/Mentorship" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Mentorship</span>
                            </a>
                            
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Send Request</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('.mentorship-form').addEventListener('submit', function(e) {
            const requestReason = document.getElementById('request_reason').value.trim();
            const topic = document.getElementById('topic').value.trim();
            
            if (!topic) {
                e.preventDefault();
                alert('Please provide a topic.');
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
