<?php require '../app/views/partials/header.php'; ?>

<!-- Alumni Dashboard Content -->
<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Alumni Hub</h2>
            <button class="sidebar-toggle" id="sidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item active">
                    <a href="#" class="nav-link" data-section="dashboard">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="mentorship">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01 1l-4.7 6.28c-.37.5-.58 1.11-.58 1.73V20h-2v-2h-2v2H4v-2.5c0-1.1.9-2 2-2h3c1.1 0 2-.9 2-2V7h4c1.1 0 2 .9 2 2v6h2v4z"/>
                        </svg>
                        <span>Mentorship</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="aid-requests">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span>Aid Requests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="forum">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span>Forum</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="events">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        <span>Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="resources">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                        <span>Resources</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-section="faq">
                        <svg class="nav-icon" viewBox="0 0 24 24">
                            <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM12 6c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                        </svg>
                        <span>FAQ</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <a href="#" class="nav-link logout-link">
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
            <!-- Header -->
            <header class="dashboard-header">
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back! Here's what's happening in your alumni community.</p>
            </header>

            <!-- Profile Snapshot -->
            <section class="profile-section">
                <div class="profile-card">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            <span class="avatar-text">JD</span>
                        </div>
                        <div class="profile-details">
                            <h3 class="profile-name">John Doe</h3>
                            <p class="profile-role">Software Engineer at TechCorp</p>
                            <p class="profile-year">Class of 2020</p>
                            <div class="profile-badges">
                                <span class="badge badge-mentor">Mentor</span>
                                <span class="badge badge-donor">Donor</span>
                                <span class="badge badge-volunteer">Volunteer</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary edit-profile-btn">
                        <svg class="btn-icon" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        Edit Profile
                    </button>
                </div>
            </section>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Mentorship Requests -->
                <section class="dashboard-section" id="mentorship-section">
                    <div class="section-header">
                        <h2 class="section-title">Mentorship Requests</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="cards-container" id="mentorship-cards">
                        <!-- Cards will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Aid Requests -->
                <section class="dashboard-section" id="aid-section">
                    <div class="section-header">
                        <h2 class="section-title">Aid Requests</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="cards-container" id="aid-cards">
                        <!-- Cards will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Discussion Forum -->
                <section class="dashboard-section" id="forum-section">
                    <div class="section-header">
                        <h2 class="section-title">Discussion Forum</h2>
                        <div class="section-actions">
                            <button class="btn btn-primary">New Post</button>
                            <button class="btn btn-secondary">All Forums</button>
                        </div>
                    </div>
                    <div class="forum-posts" id="forum-posts">
                        <!-- Posts will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Alumni Events -->
                <section class="dashboard-section" id="events-section">
                    <div class="section-header">
                        <h2 class="section-title">Alumni Events</h2>
                        <div class="section-actions">
                            <button class="btn btn-primary">Publish Event</button>
                            <button class="btn btn-secondary">View All</button>
                        </div>
                    </div>
                    <div class="cards-container" id="events-cards">
                        <!-- Cards will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Shared Resources -->
                <section class="dashboard-section" id="resources-section">
                    <div class="section-header">
                        <h2 class="section-title">Shared Resources</h2>
                        <button class="btn btn-primary">
                            <svg class="btn-icon" viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                            Upload
                        </button>
                    </div>
                    <div class="resources-list" id="resources-list">
                        <!-- Resources will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Badges & Engagement -->
                <section class="dashboard-section" id="badges-section">
                    <div class="section-header">
                        <h2 class="section-title">Your Badges</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="badges-grid" id="badges-grid">
                        <!-- Badges will be populated by JavaScript -->
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>

<!-- Add CSS and JS links -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">

<?php require '../app/views/partials/footer.php'; ?>

<!-- Add JavaScript before closing body -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>