<?php 
$page_title = "Welcome, " . esc($profile->name);
$page_subtitle = esc($profile->faculty) . " • Year " . esc($profile->academic_year);
require '../app/views/partials/student_header.php'; 
?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
        <!-- Sidebar -->
        <?php require '../app/views/partials/student_sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Success message after an update -->
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Profile updated successfully!</div>
            <?php endif; ?>

            <!-- Student Profile Section -->
            <section class="dashboard-section profile-section">
                <div class="section-header">
                    <h2 class="section-title">Student Profile</h2>
                    <a href="<?= ROOT ?>/student/profile/?action=edit&id=<?= $profile->student_id ?>" class="btn btn-outline">
                        <i class="fas fa-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                </div>
                        
                <div class="profile-card">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <?php if (!empty($profile->profile_photo_url)): ?>
                                <img src="<?= ROOT ?>/assets/uploads/profiles/<?= esc($profile->profile_photo_url) ?>" 
                                     alt="Profile Picture"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <span class="avatar-initials" style="display:none;"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                            <?php else: ?>
                                <span class="avatar-initials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="profile-details">
                            <h3><?= esc($profile->name) ?></h3>
                            <!-- <p>Student</p> -->
                            <p>Student ID: <?= esc($profile->student_id) ?></p>
                            <div class="profile-meta">
                                <p><strong>Faculty:</strong> <?= esc($profile->faculty) ?></p>
                                <p><strong>Academic Year:</strong> Year <?= esc($profile->academic_year) ?></p>
                                <p><strong>Email:</strong> <?= esc($profile->email) ?></p>
                                <?php if (!empty($profile->mobile)): ?>
                                    <p><strong>Mobile:</strong> <?= esc($profile->mobile) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Student Statistics Section -->
            <section class="dashboard-section">
                <h2 class="section-title">Academic Progress</h2>
                <div class="stats-grid">
                    <div class="stats-column">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">8</div>
                                <div class="stat-label">Courses Enrolled</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">2</div>
                                <div class="stat-label">Mentorship Requests</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">5</div>
                                <div class="stat-label">Events Attended</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-column">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">3</div>
                                <div class="stat-label">Aid Requests</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">15</div>
                                <div class="stat-label">Forum Posts</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">23</div>
                                <div class="stat-label">Resources Downloaded</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Student Bio & Interests Section -->
            <section class="dashboard-section">
                <h2 class="section-title">About Me</h2>
                <div class="bio-section">
                    <div class="bio-card">
                        <div class="bio-header">
                            <div class="bio-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="bio-title">
                                <h3>About <?= esc($profile->name) ?></h3>
                                <p>Year <?= esc($profile->academic_year) ?> Student</p>
                            </div>
                        </div>
                        <div class="bio-content">
                            <p><?= esc($profile->bio ?? 'Dedicated student pursuing academic excellence and actively participating in campus activities. Looking forward to connecting with alumni and peers for mentorship and collaboration opportunities.') ?></p>
                        </div>
                    </div>
                    
                    <div class="experience-grid">
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Current Program</h4>
                                <p><?= esc($profile->faculty) ?></p>
                                <span class="experience-period">Year <?= esc($profile->academic_year) ?></span>
                            </div>
                        </div>
                        
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Student ID</h4>
                                <p><?= esc($profile->student_id) ?></p>
                                <span class="experience-period">Active</span>
                            </div>
                        </div>
                        
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Contact</h4>
                                <p><?= esc($profile->email) ?></p>
                                <span class="experience-period">Primary</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Contact & Social Links Section -->
            <section class="dashboard-section">
                <h2 class="section-title">Connect With Me</h2>
                <div class="social-links-grid">
                    <?php if (!empty($profile->linkedin_url)): ?>
                    <a href="<?= esc($profile->linkedin_url) ?>" target="_blank" class="social-link-card">
                        <div class="social-link-icon linkedin">
                            <i class="fab fa-linkedin"></i>
                        </div>
                        <div class="social-link-label">LinkedIn</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($profile->github_url)): ?>
                    <a href="<?= esc($profile->github_url) ?>" target="_blank" class="social-link-card">
                        <div class="social-link-icon github">
                            <i class="fab fa-github"></i>
                        </div>
                        <div class="social-link-label">GitHub</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($profile->twitter_url)): ?>
                    <a href="<?= esc($profile->twitter_url) ?>" target="_blank" class="social-link-card">
                        <div class="social-link-icon twitter">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <div class="social-link-label">Twitter</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($profile->website_url)): ?>
                    <a href="<?= esc($profile->website_url) ?>" target="_blank" class="social-link-card">
                        <div class="social-link-icon website">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="social-link-label">Website</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (empty($profile->linkedin_url) && empty($profile->github_url) && empty($profile->twitter_url) && empty($profile->website_url)): ?>
                    <div class="no-social-links">
                        <p>No social media links added yet. <a href="<?= ROOT ?>/student/profile/?action=edit&id=<?= $profile->student_id ?>">Edit your profile</a> to add social media links.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
