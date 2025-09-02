<?php require '../app/views/partials/alumni_header.php'; ?>

<!-- Alumni Dashboard Content -->
<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?=ROOT?>/alumni/dashboard" class="nav-link" data-tooltip="Dashboard">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="<?=ROOT?>/alumni/profile" class="nav-link" data-tooltip="My Profile">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="mentorship" data-tooltip="Mentorship">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01 1l-4.7 6.28c-.37.5-.58 1.11-.58 1.73V20h-2v-2h-2v2H4v-2.5c0-1.1.9-2 2-2h3c1.1 0 2-.9 2-2V7h4c1.1 0 2 .9 2 2v6h2v4z"/>
                        </svg>
                        <span>Mentorship</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="aid-requests" data-tooltip="Aid Requests">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span>Aid Requests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="forum" data-tooltip="Forum">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span>Forum</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="fundraiser" data-tooltip="Fundraiser">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                        </svg>
                        <span>Fundraiser</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="events" data-tooltip="Events">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        <span>Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="resources" data-tooltip="Resources">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                        <span>Resources</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="faq" data-tooltip="FAQ">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM12 6c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                        </svg>
                        <span>FAQ</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Profile Content -->
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header" style="position: relative;">
                    <a href="<?=ROOT?>/alumni/profile/edit" class="btn btn-primary edit-profile-btn" style="position: absolute; top: 10px; right: 10px; font-size: 14px; padding: 8px 12px; text-decoration:none; display:inline-flex; align-items:center;">
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
                        <a href="#" class="social-link">
                            <div class="social-icon">🔗</div>
                            <div class="social-label">LinkedIn</div>
                        </a>
                        <a href="#" class="social-link">
                            <div class="social-icon">🐙</div>
                            <div class="social-label">GitHub</div>
                        </a>
                        <a href="#" class="social-link">
                            <div class="social-icon">🐦</div>
                            <div class="social-label">Twitter</div>
                        </a>
                        <a href="#" class="social-link">
                            <div class="social-icon">🌐</div>
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

<?php require '../app/views/partials/footer.php'; ?>
<!-- Profile page extracted JS -->
<script src="<?=ROOT?>/assets/js/alumni_profile.js"></script>