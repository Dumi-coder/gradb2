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
            
            <div class="sidebar-footer">
                <a href="#" class="nav-link logout-link" data-tooltip="Logout">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Profile Content -->
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
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

<!-- Add CSS and JS links -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">

<style>
/* Additional styles to ensure proper header positioning for alumni dashboard */
body {
    padding-top: 0; /* Remove any default padding */
}

.header {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1001;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

main {
    margin-top: 0; /* Reset any default margins */
}

/* Make header profile section smaller for profile page */
.profile-toggle {
    padding: 4px 8px !important;
    gap: 4px !important;
    font-size: 11px !important;
}

.profile-avatar {
    width: 20px !important;
    height: 20px !important;
}

.profile-icon {
    width: 10px !important;
    height: 10px !important;
}

.profile-name {
    font-size: 10px !important;
}

.dropdown-arrow {
    width: 12px !important;
    height: 12px !important;
}

/* Sidebar minimization styles for profile page */
.sidebar {
    transition: all 0.3s ease;
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar.collapsed .nav-link {
    padding: 0.75rem;
    justify-content: center;
    position: relative;
}

.sidebar.collapsed .nav-icon {
    margin-right: 0;
    width: 24px;
    height: 24px;
}

.sidebar.collapsed .nav-link span {
    display: none;
}

.sidebar.collapsed .sidebar-header {
    padding: 1rem 0.5rem;
    justify-content: flex-start;
}

.sidebar.collapsed .nav-item {
    margin: 0.25rem 0.5rem;
}

/* Tooltip for collapsed sidebar */
.sidebar.collapsed .nav-link:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: #1e293b;
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    white-space: nowrap;
    z-index: 1000;
    opacity: 0;
    animation: tooltipFadeIn 0.2s ease forwards;
}

@keyframes tooltipFadeIn {
    from {
        opacity: 0;
        transform: translateY(-50%) translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }
}

/* Main content adjustment for collapsed sidebar */
.main-content {
    margin-left: 280px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed + .main-content {
    margin-left: 70px;
}

/* Profile specific styles */
.profile-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.profile-header {
    text-align: center;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #007bff;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
    font-weight: bold;
}

.profile-name {
    font-size: 28px;
    color: #333;
    margin-bottom: 10px;
}

.profile-role {
    font-size: 18px;
    color: #007bff;
    margin-bottom: 5px;
}

.profile-batch {
    color: #666;
    font-size: 16px;
}

.profile-section {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-weight: bold;
    color: #666;
    font-size: 14px;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.info-value {
    color: #333;
    font-size: 16px;
}

.badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 15px;
}

.badge-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.badge-item:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.badge-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.badge-label {
    font-size: 12px;
    font-weight: bold;
    color: #333;
}

.contributions-list {
    display: grid;
    gap: 15px;
}

.contribution-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.contribution-icon {
    font-size: 20px;
}

.contribution-details h3 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 16px;
}

.contribution-details p {
    margin: 0 0 8px 0;
    color: #666;
    font-size: 14px;
}

.contribution-count {
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.social-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.social-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
}

.social-icon {
    font-size: 20px;
    margin-bottom: 8px;
}

.social-label {
    font-size: 12px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .profile-container {
        padding: 15px;
        margin: 10px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .badges-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .social-links {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php require '../app/views/partials/footer.php'; ?>

<!-- Profile Page Sidebar Toggle JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function() {
            // Toggle collapsed class on sidebar
            sidebar.classList.toggle('collapsed');
            
            // Update main content margin
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '70px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
            
            console.log('Sidebar toggled:', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        });
        
        console.log('Profile page sidebar toggle initialized successfully!');
    } else {
        console.error('Required elements not found for sidebar toggle');
    }
});
</script>