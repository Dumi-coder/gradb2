<?php require '../app/views/partials/alumni_header.php'; ?>
<?php 
$current_page = 'dashboard';
require '../app/views/partials/alumni_sidebar.php'; 
?>
<!-- Main Content Area -->
<main class="main-content">
        <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Welcome back! Here's what's happening in your alumni community.</p>
            </div>

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
                                <span class="badge badge-mentor">
                                    <i class="fas fa-user-graduate"></i> Mentor
                                </span>
                                <span class="badge badge-donor">
                                    <i class="fas fa-heart"></i> Donor
                                </span>
                                <span class="badge badge-volunteer">
                                    <i class="fas fa-hands-helping"></i> Volunteer
                                </span>
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
                        <button class="btn btn-outline btn-md view-all-btn" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">
                            <i class="fas fa-external-link-alt"></i>
                            View All
                        </button>
                    </div>
                    <div class="request-card-container">
                        <div class="request-card">
                            <div class="request-header">
                                <div class="student-info-header">
                                    <h4 class="student-name-header">Sarah Johnson</h4>
                                    <span class="status-badge-new">New</span>
                                </div>
                                <p class="student-university">Stanford University</p>
                            </div>
                            <div class="request-body">
                                <p class="request-description">Looking for guidance on career transition to product management</p>
                            </div>
                            <div class="request-actions">
                                <button class="btn btn-accept btn-sm" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Profile</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Aid Requests -->
                <section class="dashboard-section" id="aid-section">
                    <div class="section-header">
                        <h2 class="section-title">Aid Requests</h2>
                        <button class="btn btn-outline btn-md view-all-btn" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">
                            <i class="fas fa-external-link-alt"></i>
                            View All
                        </button>
                    </div>
                    <div class="aid-card-container">
                        <div class="aid-card urgent-card">
                            <div class="urgent-indicator"></div>
                            <div class="aid-card-content">
                                <div class="aid-header">
                                    <div>
                                        <h4 class="student-name-aid">Emily Rodriguez</h4>
                                        <p class="aid-type">Financial Aid</p>
                                    </div>
                                    <span class="status-badge-urgent">Urgent</span>
                                </div>
                                <p class="aid-description">Emergency medical expenses for family member</p>
                                <div class="aid-actions">
                                    <button class="btn btn-primary btn-sm" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Provide Aid</button>
                                    <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Discussion Forum -->
                <section class="dashboard-section" id="forum-section">
                    <div class="section-header">
                        <h2 class="section-title">Discussion Forum</h2>
                        <button class="btn btn-outline btn-md" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">
                            <i class="fas fa-external-link-alt"></i>
                            Go to Forum
                        </button>
                    </div>
                    <div class="forum-container">
                        <div class="forum-post-card">
                            <div class="forum-post-header">
                                <h4 class="forum-title">Career advice for recent graduates</h4>
                                <span class="post-time-badge">2 hours ago</span>
                            </div>
                            <p class="forum-excerpt">I'm graduating next month and would love to hear from alumni about their career paths...</p>
                            <div class="forum-stats" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span><i class="fas fa-comment"></i> 12 replies</span>
                                </div>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                            </div>
                        </div>
                        <div class="forum-post-card">
                            <div class="forum-post-header">
                                <h4 class="forum-title">Networking event in San Francisco</h4>
                                <span class="post-time-badge">1 day ago</span>
                            </div>
                            <p class="forum-excerpt">Anyone interested in organizing a networking event for Bay Area alumni?</p>
                            <div class="forum-stats" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span><i class="fas fa-comment"></i> 8 replies</span>
                                </div>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                            </div>
                        </div>
                        <div class="forum-post-card">
                            <div class="forum-post-header">
                                <h4 class="forum-title">Industry insights: Tech trends 2024</h4>
                                <span class="post-time-badge">3 days ago</span>
                            </div>
                            <p class="forum-excerpt">Let's discuss the latest trends in technology and how they affect our careers...</p>
                            <div class="forum-stats" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span><i class="fas fa-comment"></i> 15 replies</span>
                                </div>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Fundraising Campaigns -->
                <section class="dashboard-section" id="fundraiser-section">
                    <div class="section-header">
                        <h2 class="section-title">Fundraising Campaigns</h2>
                        <button class="btn btn-outline btn-md view-all-btn" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">
                            <i class="fas fa-external-link-alt"></i>
                            View All
                        </button>
                    </div>
                    <div class="fundraiser-container">
                        <div class="fundraiser-campaign-card">
                            <div class="campaign-header">
                                <h4 class="campaign-title">Student Emergency Fund</h4>
                                <span class="campaign-badge urgent">URGENT</span>
                            </div>
                            <p class="campaign-description">Supporting students facing financial hardship during their studies.</p>
                            <div class="progress-container">
                                <div class="progress-bar-wrapper">
                                    <div class="progress-bar-fill" style="width: 65%"></div>
                                </div>
                                <div class="progress-info">
                                    <span class="amount-raised">$6,500 raised</span>
                                    <span class="amount-goal">of $10,000</span>
                                </div>
                            </div>
                            <div class="campaign-actions">
                                <button class="btn btn-primary btn-sm" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Donate</button>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                            </div>
                        </div>
                        <div class="fundraiser-campaign-card">
                            <div class="campaign-header">
                                <h4 class="campaign-title">Research Equipment Fund</h4>
                                <span class="campaign-badge active">ACTIVE</span>
                            </div>
                            <p class="campaign-description">Funding for new laboratory equipment to enhance research capabilities.</p>
                            <div class="progress-container">
                                <div class="progress-bar-wrapper">
                                    <div class="progress-bar-fill" style="width: 40%"></div>
                                </div>
                                <div class="progress-info">
                                    <span class="amount-raised">$8,000 raised</span>
                                    <span class="amount-goal">of $20,000</span>
                                </div>
                            </div>
                            <div class="campaign-actions">
                                <button class="btn btn-primary btn-sm" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Donate</button>
                                <button class="btn btn-outline btn-sm" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Alumni Events -->
                <section class="dashboard-section" id="events-section">
                    <div class="section-header">
                        <h2 class="section-title">Upcoming Events</h2>
                        <button class="btn btn-outline btn-md view-all-btn" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">
                            <i class="fas fa-external-link-alt"></i>
                            View All
                        </button>
                    </div>
                    <div class="events-container">
                        <div class="event-card-new">
                            <div class="event-content">
                                <h4 class="event-title-new">Mentorship Workshop</h4>
                                <p class="event-description-new">Learn how to be an effective mentor and guide students</p>
                                <div class="event-meta">
                                    <span><i class="fas fa-clock"></i> 2:00 PM</span>
                                    <span><i class="fas fa-map-marker-alt"></i> Engineering Building</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Your Badges -->
                <section class="dashboard-section" id="badges-section">
                    <div class="section-header">
                        <h2 class="section-title">Your Badges</h2>
                    </div>
                    <div class="badges-grid-container">
                        <div class="badge-card">
                            <div class="badge-icon-circle blue">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <h4 class="badge-title">Mentor</h4>
                            <p class="badge-desc">Helped 5+ students</p>
                        </div>
                        <div class="badge-card">
                            <div class="badge-icon-circle green">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h4 class="badge-title">Donor</h4>
                            <p class="badge-desc">Contributed $1000+</p>
                        </div>
                        <div class="badge-card">
                            <div class="badge-icon-circle purple">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <h4 class="badge-title">Volunteer</h4>
                            <p class="badge-desc">50+ hours served</p>
                        </div>
                        <div class="badge-card">
                            <div class="badge-icon-circle orange">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <h4 class="badge-title">Speaker</h4>
                            <p class="badge-desc">Presented at events</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>