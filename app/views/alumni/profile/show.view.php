<?php 
$page_title = "Welcome, " . esc($profile->name);
$page_subtitle = esc($profile->degree) . " • Batch " . esc($profile->graduation_year);
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Unified Profile Styles -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
        <!-- Sidebar -->
        <?php require '../app/views/partials/alumni_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Success message after an update -->
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Profile updated successfully!</div>
            <?php endif; ?>

            <!-- Alumni Profile Section -->
            <section class="dashboard-section profile-section">
                <div class="section-header">
                    <h2 class="section-title">Alumni Profile</h2>
                    <a href="<?=ROOT?>/alumni/profile?action=edit&id=<?= $profile->alumni_id ?>" class="btn btn-outline btn-md">
                        <i class="fas fa-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                </div>
                
                <div class="profile-card">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <?php if (!empty($profile->profile_photo_url)): ?>
                                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture">
                            <?php else: ?>
                                <span class="avatar-initials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="profile-details">
                            <h3><?= esc($profile->name) ?></h3>
                            <p><?= esc($profile->current_job ?? 'Alumni') ?></p>
                            <p>Alumni ID: ALU<?= str_pad($profile->user_id, 3, '0', STR_PAD_LEFT) ?></p>
                            <div class="profile-meta">
                                <p><strong>Email:</strong> <?= esc($profile->email) ?></p>
                                <p><strong>Graduation Year:</strong> <?= esc($profile->graduation_year) ?></p>
                                <p><strong>Degree:</strong> <?= esc($profile->degree) ?></p>
                                <?php if (!empty($profile->location)): ?>
                                    <p><strong>Location:</strong> <?= esc($profile->location) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Alumni Statistics Section -->
            <section class="dashboard-section">
                <h2 class="section-title">Alumni Achievements</h2>
                <div class="stats-grid">
                    <div class="stats-column">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">5</div>
                                <div class="stat-label">Mentorships Provided</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-donate"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">3</div>
                                <div class="stat-label">Donations Made</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">12</div>
                                <div class="stat-label">Events Attended</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-column">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">28</div>
                                <div class="stat-label">Forum Contributions</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">7</div>
                                <div class="stat-label">Resources Shared</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">Active</div>
                                <div class="stat-label">Member Status</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Alumni Bio & Experience Section -->
            <section class="dashboard-section">
                <h2 class="section-title">Professional Background</h2>
                <div class="bio-section">
                    <div class="bio-card">
                        <div class="bio-header">
                            <div class="bio-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="bio-title">
                                <h3>About <?= esc($profile->name) ?></h3>
                                <p><?= esc($profile->current_job ?? 'Alumni Member') ?></p>
                            </div>
                        </div>
                        <div class="bio-content">
                            <p><?= esc($profile->bio ?? 'Experienced professional committed to mentoring and supporting the next generation of graduates. Active contributor to the alumni community.') ?></p>
                        </div>
                    </div>
                    
                    <div class="experience-grid">
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Education</h4>
                                <p><?= esc($profile->degree) ?></p>
                                <span class="experience-period"><?= esc($profile->graduation_year) ?></span>
                            </div>
                        </div>
                        
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Current Position</h4>
                                <p><?= esc($profile->current_job ?? 'Professional') ?></p>
                                <span class="experience-period">Present</span>
                            </div>
                        </div>
                        
                        <div class="experience-card">
                            <div class="experience-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="experience-content">
                                <h4>Location</h4>
                                <p><?= esc($profile->location ?? 'Not specified') ?></p>
                                <span class="experience-period">Current</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Contact & Social Links Section -->
            <section class="dashboard-section">
                <h2 class="section-title">Contact & Social Links</h2>
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
                        <p>No social media links added yet. <a href="<?=ROOT?>/alumni/profile?action=edit&id=<?= $profile->alumni_id ?>">Edit your profile</a> to add social media links.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
