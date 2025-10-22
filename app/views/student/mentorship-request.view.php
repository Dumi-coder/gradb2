<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with alumni mentors";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship-request.css">

<!-- Main Content -->
<main class="main-content">
        <div class="container">
            <div class="mentorship-form-container">
                <div class="mentorship-form-card">
                    <h2 class="form-title">Request mentorship</h2>
                    <p class="form-description">
                        Connect with alumni who can guide you in your academic and professional journey
                    </p>
                    
                    <form class="mentorship-form">
                        <div class="form-group">
                            <label for="mentorship-area" class="form-label">
                                Select Mentorship area <span class="required">*</span>
                            </label>
                            <select id="mentorship-area" name="mentorship-area" class="form-select" required>
                                <option value="">Choose an area...</option>
                                <option value="academic">Academic Guidance</option>
                                <option value="career">Career Development</option>
                                <option value="research">Research & Projects</option>
                                <option value="networking">Professional Networking</option>
                                <option value="skills">Technical Skills</option>
                                <option value="leadership">Leadership & Management</option>
                                <option value="entrepreneurship">Entrepreneurship</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="other-area-group" style="display: none;">
                            <label for="other-mentorship-area" class="form-label">
                                Specify your mentorship area <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="other-mentorship-area" 
                                name="other-mentorship-area" 
                                class="form-input" 
                                placeholder="Please mention your specific mentorship need..."
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Briefly describe what you need help with <span class="required">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-textarea" 
                                rows="4" 
                                placeholder="Ex: I want to understand how to approach the XYZ topic in my project..."
                                required
                            ></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Submit Mentorship Request</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Handle mentorship area selection
        document.getElementById('mentorship-area').addEventListener('change', function() {
            const otherAreaGroup = document.getElementById('other-area-group');
            const otherAreaInput = document.getElementById('other-mentorship-area');
            
            if (this.value === 'other') {
                otherAreaGroup.style.display = 'block';
                otherAreaInput.required = true;
            } else {
                otherAreaGroup.style.display = 'none';
                otherAreaInput.required = false;
                otherAreaInput.value = ''; // Clear the input when hiding
            }
        });
    </script>
</body>
</html>
