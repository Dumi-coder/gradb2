<?php require '../app/views/partials/alumni_header.php'; ?>

<!-- Alumni Dashboard Content -->
<div class="dashboard-container">
    <?php 
    $current_page = 'profile';
    require '../app/views/partials/alumni_sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Profile Content -->
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header" style="position: relative;">
                    <a href="<?=ROOT?>/alumni/profile/edit" class="btn btn-primary edit-profile-btn" style="position: absolute; top: 10px; right: 10px; font-size: 14px; padding: 8px 12px; text-decoration:none; display:inline-flex; align-items:center; background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">
                        <svg class="btn-icon" viewBox="0 0 24 24" style="width: 16px; height: 16px; margin-right: 5px;">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        Edit Profile
                    </a>
                    <div class="profile-photo">JD</div>
                    <h1 class="profile-name">John Doe</h1>
                    <p class="profile-role">Software Engineer</p>
                    <p class="profile-batch">Batch 2018</p>
                </div>

                <!-- Personal Information -->
                <div class="profile-section">
                    <h2 class="section-title">Personal Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">John Michael Doe</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Graduation Year</span>
                            <span class="info-value">2018</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Degree</span>
                            <span class="info-value">Computer Science</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Current Job</span>
                            <span class="info-value">Senior Software Engineer</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Location</span>
                            <span class="info-value">San Francisco, CA</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">john.doe@email.com</span>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="profile-section">
                    <h2 class="section-title">Bio</h2>
                    <div class="bio-text" style="background:#ffffff;border:1px solid #e2e8f0;padding:1.25rem;border-radius:0.75rem;line-height:1.55;color:#475569;font-size:0.95rem;">
                        Experienced software engineer with expertise in full-stack development. Passionate about mentoring students and contributing to the alumni community.
                    </div>
                </div>

                <!-- Achievements & Badges -->
                <div class="profile-section">
                    <h2 class="section-title">Achievements & Badges</h2>
                    <div class="badges-grid">
                        <div class="badge-item">
                            <div class="badge-icon">🎓</div>
                            <div class="badge-label">Mentor</div>
                        </div>
                        <div class="badge-item">
                            <div class="badge-icon">💝</div>
                            <div class="badge-label">Donor</div>
                        </div>
                        <div class="badge-item">
                            <div class="badge-icon">⭐</div>
                            <div class="badge-label">Active</div>
                        </div>
                        <div class="badge-item">
                            <div class="badge-icon">🤝</div>
                            <div class="badge-label">Volunteer</div>
                        </div>
                        <div class="badge-item">
                            <div class="badge-icon">👑</div>
                            <div class="badge-label">Leader</div>
                        </div>
                        <div class="badge-item">
                            <div class="badge-icon">💡</div>
                            <div class="badge-label">Innovator</div>
                        </div>
                    </div>
                </div>

                <!-- Contributions -->
                <div class="profile-section">
                    <h2 class="section-title">Contributions</h2>
                    <div class="contributions-list">
                        <div class="contribution-item">
                            <div class="contribution-icon">👨‍🏫</div>
                            <div class="contribution-details">
                                <h3>Mentorship Provided</h3>
                                <p>Mentored 15+ students in software development</p>
                                <span class="contribution-count">15 students</span>
                            </div>
                        </div>
                        <div class="contribution-item">
                            <div class="contribution-icon">💰</div>
                            <div class="contribution-details">
                                <h3>Aid Given</h3>
                                <p>Contributed to student emergency fund</p>
                                <span class="contribution-count">$5,000+</span>
                            </div>
                        </div>
                        <div class="contribution-item">
                            <div class="contribution-icon">🎉</div>
                            <div class="contribution-details">
                                <h3>Events Participated</h3>
                                <p>Active participant in alumni events</p>
                                <span class="contribution-count">8 events</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="profile-section">
                    <h2 class="section-title">Contact & Social Links</h2>
                    <div class="social-links">
                        <a href="#" class="social-link" style="display: flex; align-items: center; padding: 1rem; background: #ffffff; border: none; border-radius: 8px; text-decoration: none; color: #000000; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                            <div class="social-icon" style="font-size: 1.5rem; margin-right: 0.75rem;">🔗</div>
                            <div class="social-label">LinkedIn</div>
                        </a>
                        <a href="#" class="social-link" style="display: flex; align-items: center; padding: 1rem; background: #ffffff; border: none; border-radius: 8px; text-decoration: none; color: #000000; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                            <div class="social-icon" style="font-size: 1.5rem; margin-right: 0.75rem;">🐙</div>
                            <div class="social-label">GitHub</div>
                        </a>
                        <a href="#" class="social-link" style="display: flex; align-items: center; padding: 1rem; background: #ffffff; border: none; border-radius: 8px; text-decoration: none; color: #000000; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                            <div class="social-icon" style="font-size: 1.5rem; margin-right: 0.75rem;">🐦</div>
                            <div class="social-label">Twitter</div>
                        </a>
                        <a href="#" class="social-link" style="display: flex; align-items: center; padding: 1rem; background: #ffffff; border: none; border-radius: 8px; text-decoration: none; color: #000000; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                            <div class="social-icon" style="font-size: 1.5rem; margin-right: 0.75rem;">🌐</div>
                            <div class="social-label">Website</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add CSS links -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_profile_extracted.css">

<!-- Override any blue hover effects on badges and add shadow hover -->
<style>
.badge-item:hover {
    border-color: transparent !important;
    border: 2px solid transparent !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px) !important;
}
</style>

<!-- Profile page extracted JS -->
<script src="<?=ROOT?>/assets/js/alumni_profile.js"></script>