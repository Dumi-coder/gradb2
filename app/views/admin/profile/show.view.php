<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Admin Profile Section -->
        <section class="dashboard-section profile-section">
            <div class="section-header">
                <h2 class="section-title">Admin Profile</h2>
                <a href="<?=ROOT?>/admin/profile?action=edit" class="btn btn-outline btn-md">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
            
            <div class="profile-card">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <span class="avatar-initials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                    </div>
                    <div class="profile-details">
                        <h3><?= esc($profile->name) ?></h3>
                        <p>Faculty Administrator</p>
                        <p>Admin ID: FAC<?= str_pad($profile->user_id, 3, '0', STR_PAD_LEFT) ?></p>
                        <div class="profile-meta">
                            <p><strong>Email:</strong> <?= esc($profile->email) ?></p>
                            <p><strong>Role:</strong> 
                                <span class="status-badge status-active">
                                    <i class="fas fa-shield-alt"></i> Faculty Admin
                                </span>
                            </p>
                            <p><strong>Member Since:</strong> <?= date('F Y', strtotime($profile->created_at ?? 'now')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Admin Statistics Section -->
        <section class="dashboard-section">
            <h2 class="section-title">Administrative Statistics</h2>
            <div class="stats-grid">
                <div class="stats-column">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['total_students'] ?></div>
                            <div class="stat-label">Total Students Managed</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['total_alumni'] ?></div>
                            <div class="stat-label">Alumni Network</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['pending_requests'] ?></div>
                            <div class="stat-label">Pending Requests</div>
                        </div>
                    </div>
                </div>
                
                <div class="stats-column">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['events_managed'] ?></div>
                            <div class="stat-label">Events Managed</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['mentorship_connections'] ?></div>
                            <div class="stat-label">Mentorship Connections</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['system_uptime'] ?></div>
                            <div class="stat-label">System Uptime</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Admin Bio & Experience Section -->
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
                            <p>Faculty Administrator</p>
                        </div>
                    </div>
                    <div class="bio-content">
                        <p>Experienced faculty administrator with a strong background in educational technology and student services. Committed to fostering meaningful connections between students and alumni while maintaining the highest standards of platform integrity and user experience.</p>
                    </div>
                </div>
                
                <div class="experience-grid">
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="experience-content">
                            <h4>Education Background</h4>
                            <p>Master's in Educational Administration</p>
                            <span class="experience-period">2018 - 2020</span>
                        </div>
                    </div>
                    
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="experience-content">
                            <h4>Professional Experience</h4>
                            <p>5+ years in educational technology</p>
                            <span class="experience-period">2019 - Present</span>
                        </div>
                    </div>
                    
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="experience-content">
                            <h4>Certifications</h4>
                            <p>Certified Educational Technology Specialist</p>
                            <span class="experience-period">2021</span>
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
                    <p>No social media links added yet. <a href="<?=ROOT?>/admin/profile?action=edit">Edit your profile</a> to add social media links.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
