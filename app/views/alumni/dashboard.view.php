<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alumni Dashboard - GradBridge</title>
    <meta name="description" content="Alumni Dashboard for GradBridge - Manage your mentorship, aid requests, and community engagement." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    
  </head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Welcome, <span class="alumni-name"><?= esc($profile->name) ?></span></h1>
            <p class="alumni-role"><?= esc($profile->faculty) ?> • Class of <?= esc($profile->graduated_year) ?></p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell" style="font-size: 1.1rem;"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/alumni/logout" class="btn btn-primary logout-btn">Logout</a>
          </div>
        </div>
      </div>
    </header>

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
                <div class="profile-avatar" id="profileAvatar">
                  <img src="" alt="Profile Picture" id="profileImage" style="display: none;">
                  <span class="avatar-text"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                </div>
                <div class="avatar-upload-overlay">
                  <label for="profilePictureInput" class="avatar-upload-btn" title="Change Profile Picture">
                    <i class="fas fa-camera"></i>
                  </label>
                  <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
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