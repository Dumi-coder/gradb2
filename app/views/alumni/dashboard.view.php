<?php require '../app/views/partials/alumni_header.php'; ?>

<!-- Alumni Dashboard Content -->
<div class="dashboard-container">
    <?php 
    $current_page = 'dashboard';
    require '../app/views/partials/alumni_sidebar.php'; 
    ?>

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
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="forum-posts" id="forum-posts">
                        <!-- Posts will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Fundraiser -->
                <section class="dashboard-section" id="fundraiser-section">
                    <div class="section-header">
                        <h2 class="section-title">Fundraising Campaigns</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="fundraiser-grid" id="fundraiser-grid">
                        <div class="fundraiser-card">
                            <div class="card-header">
                                <h3 class="card-title">Student Emergency Fund</h3>
                                <span class="card-badge urgent">Urgent</span>
                            </div>
                            <div class="card-content">
                                <p class="card-description">Supporting students facing financial hardship during their studies.</p>
                                <div class="progress-section">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 65%"></div>
                                    </div>
                                    <div class="progress-stats">
                                        <span class="raised">$6,500 raised</span>
                                        <span class="goal">of $10,000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-primary">Donate</button>
                                <button class="btn btn-text">View Details</button>
                            </div>
                        </div>
                        <div class="fundraiser-card">
                            <div class="card-header">
                                <h3 class="card-title">Research Equipment Fund</h3>
                                <span class="card-badge active">Active</span>
                            </div>
                            <div class="card-content">
                                <p class="card-description">Funding for new laboratory equipment to enhance research capabilities.</p>
                                <div class="progress-section">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 40%"></div>
                                    </div>
                                    <div class="progress-stats">
                                        <span class="raised">$8,000 raised</span>
                                        <span class="goal">of $20,000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-primary">Donate</button>
                                <button class="btn btn-text">View Details</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Alumni Events -->
                <section class="dashboard-section" id="events-section">
                    <div class="section-header">
                        <h2 class="section-title">Alumni Events</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="cards-container" id="events-cards">
                        <!-- Cards will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Shared Resources -->
                <section class="dashboard-section" id="resources-section">
                    <div class="section-header">
                        <h2 class="section-title">Shared Resources</h2>
                        <button class="btn btn-text">View All</button>
                    </div>
                    <div class="resources-list" id="resources-list">
                        <!-- Resources will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Badges & Engagement -->
                <section class="dashboard-section" id="badges-section">
                    <div class="section-header">
                        <h2 class="section-title">Your Badges</h2>
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
</style>

<?php require '../app/views/partials/footer.php'; ?>

<!-- Add JavaScript before closing body -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>