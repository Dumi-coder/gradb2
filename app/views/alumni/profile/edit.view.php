<?php require '../app/views/partials/alumni_header.php'; ?>
<?php 
$current_page = 'profile';
require '../app/views/partials/alumni_sidebar.php'; 
?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/edit-profile.css">

<!-- Main Content Area -->
<main class="main-content">
    <div class="profile-edit-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Edit Profile</h1>
            <p class="page-subtitle">Update your personal information and preferences</p>
        </div>

        <!-- Profile Edit Card -->
        <div class="profile-edit-card">
            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
                <div class="profile-avatar-large">
                    <span>JD</span>
                    <div class="avatar-upload-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="avatar-info">
                    <h3>Profile Picture</h3>
                    <p>Upload a new profile picture. JPG, PNG or GIF. Max size 5MB.</p>
                    <div class="avatar-actions">
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>

            <form id="editProfileForm">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Personal Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input" value="John Michael Doe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" class="form-input" value="john.doe@email.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-input" value="+1 (555) 123-4567" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Location
                            </label>
                            <input type="text" class="form-input" value="San Francisco, CA" placeholder="City, State">
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Academic Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-graduation-cap"></i>
                                Graduation Year <span class="required">*</span>
                            </label>
                            <input type="number" class="form-input" value="2018" min="1950" max="2030" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-book"></i>
                                Degree Program <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input" value="Computer Science" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-university"></i>
                                Faculty/Department
                            </label>
                            <input type="text" class="form-input" value="Faculty of Science" placeholder="Enter faculty">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-award"></i>
                                Specialization
                            </label>
                            <input type="text" class="form-input" value="Software Engineering" placeholder="Enter specialization">
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Professional Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-briefcase"></i>
                                Current Position
                            </label>
                            <input type="text" class="form-input" value="Senior Software Engineer" placeholder="Your job title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i>
                                Company/Organization
                            </label>
                            <input type="text" class="form-input" value="TechCorp Inc." placeholder="Where you work">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-industry"></i>
                                Industry
                            </label>
                            <select class="form-select">
                                <option>Technology</option>
                                <option>Healthcare</option>
                                <option>Finance</option>
                                <option>Education</option>
                                <option>Manufacturing</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-linkedin"></i>
                                LinkedIn Profile
                            </label>
                            <input type="url" class="form-input" value="https://linkedin.com/in/johndoe" placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="form-section">
                    <h2 class="section-title">About You</h2>
                    <div class="form-grid single-column">
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Professional Bio
                            </label>
                            <textarea class="form-textarea" id="bioTextarea" maxlength="500">Experienced software engineer with expertise in full-stack development. Passionate about mentoring students and contributing to the alumni community. Specialized in building scalable web applications and leading development teams.</textarea>
                            <div class="char-counter">
                                <span id="charCount">202</span>/500 characters
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-lightbulb"></i>
                                Skills & Expertise
                            </label>
                            <input type="text" class="form-input" value="JavaScript, Python, React, Node.js, Cloud Computing" placeholder="Enter your key skills (comma-separated)">
                            <p class="form-hint">Add skills that you can help students with or use in your profession</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="form-actions-left">
                        <a href="<?=ROOT?>/alumni/profile" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                    <div class="form-actions-right">
                        <button type="button" class="btn btn-outline" onclick="resetForm()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" onclick="showSuccessToast(event)" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Success Toast -->
<div id="successToast" class="success-toast" style="display: none;">
    <i class="fas fa-check-circle"></i>
    <span>Profile updated successfully!</span>
</div>

<?php require '../app/views/partials/alumni_footer.php'; ?>

<script>
// Character counter for bio
const bioTextarea = document.getElementById('bioTextarea');
const charCount = document.getElementById('charCount');
const charCounter = document.querySelector('.char-counter');

bioTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = length;
    
    // Update styling based on character count
    if (length > 450) {
        charCounter.classList.add('danger');
        charCounter.classList.remove('warning');
    } else if (length > 400) {
        charCounter.classList.add('warning');
        charCounter.classList.remove('danger');
    } else {
        charCounter.classList.remove('warning', 'danger');
    }
});

// Show success toast
function showSuccessToast(event) {
    event.preventDefault();
    const toast = document.getElementById('successToast');
    toast.style.display = 'flex';
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.4s ease';
        setTimeout(() => {
            toast.style.display = 'none';
            toast.style.animation = 'slideInRight 0.4s ease';
        }, 400);
    }, 3000);
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.getElementById('editProfileForm').reset();
        charCount.textContent = bioTextarea.value.length;
    }
}
</script>