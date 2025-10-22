<?php 
$page_title = "Welcome, " . esc($profile->name);
$page_subtitle = esc($profile->faculty) . " • Class of " . esc($profile->graduated_year);
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">

<body class="alumni-dashboard">
<div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Alumni Profile Section -->
        <section class="dashboard-section profile-section">
          <div class="section-header">
            <h2 class="section-title">Alumni Profile</h2>
          </div>
                <div class="profile-card">
                    <div class="profile-info">
              <div class="profile-avatar-container">
                <div class="profile-avatar" id="profileAvatar" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #3498db;">
                  <?php if (!empty($profile->profile_photo_url)): ?>
                    <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture" id="profileImage" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <span class="avatar-text" style="font-size: 40px; color: white; font-weight: bold;"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              
                        <div class="profile-details">
                <h3 class="profile-name"><?= esc($profile->name) ?></h3>
                <p class="profile-faculty"><?= esc($profile->faculty) ?></p>
                <p class="profile-year">Class of <?= esc($profile->graduated_year) ?></p>
                <p class="profile-email"><?= esc($profile->email) ?></p>
                <p class="profile-mobile">mobile <?= esc($profile->mobile) ?></p>
              </div>
            </div>
            <div class="profile-stats">
              <div class="stat-item">
                <span class="stat-number"><?= $stats['mentorship_requests'] ?></span>
                <span class="stat-label">Active Mentorships</span>
              </div>
              <div class="stat-item">
                <span class="stat-number"><?= $stats['aid_requests'] ?></span>
                <span class="stat-label">Resources Shared</span>
              </div>
              <div class="stat-item">
                <span class="stat-number"><?= $stats['events_attended'] ?></span>
                <span class="stat-label">Events Attended</span>
                            </div>
                        </div>
                </div>
            </section>

        <!-- Mentorship Requests Section -->
        <section class="dashboard-section mentorship-section">
                    <div class="section-header">
                        <h2 class="section-title">Mentorship Requests</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              View All
            </button>
          </div>
          
          <div class="mentorship-grid">
            <div class="mentorship-card">
              <div class="mentor-info">
                <div class="mentor-avatar">
                  <i class="fas fa-user-graduate"></i>
                </div>
                <div class="mentor-details">
                  <h4 class="mentor-name">Sarah Johnson</h4>
                  <p class="mentor-role">Stanford University</p>
                  <p class="mentor-specialty">Looking for guidance on career transition to product management</p>
                </div>
              </div>
              <div class="mentorship-status">
                <span class="status-badge status-new">NEW</span>
                <div class="mentorship-actions">
                  <button class="btn btn-primary btn-sm">Accept</button>
                  <button class="btn btn-outline btn-sm">View Profile</button>
                </div>
              </div>
                    </div>
                    </div>
                </section>

        <!-- Aid Requests Section -->
        <section class="dashboard-section aid-requests-section">
                    <div class="section-header">
                        <h2 class="section-title">Aid Requests</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              View All
            </button>
          </div>
          
          <div class="aid-requests-grid">
            <div class="aid-request-card urgent">
              <div class="aid-request-header">
                <div class="request-info">
                  <h4 class="request-name">Emily Rodriguez</h4>
                  <p class="request-type">Financial Aid</p>
                  <p class="request-description">Emergency medical expenses for family member</p>
                </div>
                <span class="status-badge status-urgent">URGENT</span>
              </div>
              <div class="aid-request-actions">
                <button class="btn btn-primary btn-sm">Provide Aid</button>
                <button class="btn btn-outline btn-sm">View Details</button>
              </div>
                    </div>
                    </div>
                </section>

        <!-- Discussion Forum Section -->
        <section class="dashboard-section forum-section">
                    <div class="section-header">
                        <h2 class="section-title">Discussion Forum</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              Go to Forum
            </button>
          </div>
          
          <div class="forum-preview">
            <div class="forum-post">
              <div class="post-header">
                <h4 class="post-title">Career advice for recent graduates</h4>
                <span class="post-time">2 hours ago</span>
              </div>
              <p class="post-excerpt">I'm graduating next month and would love to hear from alumni about their career paths...</p>
              <div class="post-stats">
                <span><i class="fas fa-comment"></i> 12 replies</span>
                <button class="btn btn-outline btn-sm">View Details</button>
              </div>
            </div>
            
            <div class="forum-post">
              <div class="post-header">
                <h4 class="post-title">Networking event in San Francisco</h4>
                <span class="post-time">1 day ago</span>
              </div>
              <p class="post-excerpt">Anyone interested in organizing a networking event</p>
              <div class="post-stats">
                <span><i class="fas fa-comment"></i> 8 replies</span>
                <button class="btn btn-outline btn-sm">View Details</button>
                        </div>
                    </div>
                    </div>
                </section>

        <!-- Fundraising Campaigns Section -->
        <section class="dashboard-section fundraiser-section">
                    <div class="section-header">
                        <h2 class="section-title">Fundraising Campaigns</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              View All
            </button>
                        </div>
          
          <div class="fundraiser-grid">
                        <div class="fundraiser-card">
              <div class="fundraiser-header">
                <h4 class="fundraiser-title">Student Emergency Fund</h4>
                <span class="status-badge status-urgent">URGENT</span>
                            </div>
              <p class="fundraiser-description">Supporting students facing financial hardship during their studies.</p>
              
              <div class="fundraiser-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 65%"></div>
                                    </div>
                                    <div class="progress-stats">
                                        <span class="raised">$6,500 raised</span>
                                        <span class="goal">of $10,000</span>
                                    </div>
                                </div>
              
              <div class="fundraiser-actions">
                <button class="btn btn-primary btn-sm">Donate</button>
                <button class="btn btn-outline btn-sm">View Details</button>
                            </div>
                        </div>
            
                        <div class="fundraiser-card">
              <div class="fundraiser-header">
                <h4 class="fundraiser-title">Research Equipment Fund</h4>
                <span class="status-badge status-active">ACTIVE</span>
                            </div>
              <p class="fundraiser-description">Funding for new laboratory equipment to enhance research capabilities.</p>
              
              <div class="fundraiser-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 40%"></div>
                                    </div>
                                    <div class="progress-stats">
                                        <span class="raised">$8,000 raised</span>
                                        <span class="goal">of $20,000</span>
                                    </div>
                                </div>
              
              <div class="fundraiser-actions">
                <button class="btn btn-primary btn-sm">Donate</button>
                <button class="btn btn-outline btn-sm">View Details</button>
                            </div>
                        </div>
                    </div>
                </section>

        <!-- Upcoming Events Section -->
        <section class="dashboard-section events-section">
                    <div class="section-header">
            <h2 class="section-title">Upcoming Events</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              View All
            </button>
                        </div>
          
          <div class="events-grid">
            <div class="event-card">
              <div class="event-date">
                <span class="event-day">15</span>
                <span class="event-month">Dec</span>
                    </div>
              <div class="event-details">
                <h4 class="event-title">Mentorship Workshop</h4>
                <p class="event-time"><i class="fas fa-clock"></i> 2:00 PM</p>
                <p class="event-location"><i class="fas fa-map-marker-alt"></i> Engineering Building</p>
                <p class="event-description">Learn how to be an effective mentor and guide students</p>
                    </div>
              <button class="btn btn-primary btn-sm event-register-btn">Register</button>
                    </div>
                    </div>
                </section>

        <!-- Your Badges Section -->
        <section class="dashboard-section badges-section">
                    <div class="section-header">
                        <h2 class="section-title">Your Badges</h2>
            <button class="btn btn-outline btn-md">
              <i class="fas fa-external-link-alt"></i>
              View All
            </button>
          </div>
          
          <div class="badges-grid">
            <div class="badge-card">
              <div class="badge-icon mentor">
                <i class="fas fa-user-tie"></i>
              </div>
              <h4 class="badge-title">Mentor</h4>
              <p class="badge-description">Helped 5+ students</p>
            </div>
            
            <div class="badge-card">
              <div class="badge-icon donor">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <h4 class="badge-title">Donor</h4>
              <p class="badge-description">Contributed $1000+</p>
            </div>
            
            <div class="badge-card">
              <div class="badge-icon volunteer">
                <i class="fas fa-heart"></i>
              </div>
              <h4 class="badge-title">Volunteer</h4>
              <p class="badge-description">50+ hours served</p>
                    </div>
            
            <div class="badge-card">
              <div class="badge-icon speaker">
                <i class="fas fa-microphone"></i>
                    </div>
              <h4 class="badge-title">Speaker</h4>
              <p class="badge-description">Presented at events</p>
            </div>
        </div>
        </section>
    </main>
</div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/dashboard.js"></script>
  </body>
</html>