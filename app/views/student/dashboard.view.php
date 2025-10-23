<?php 
$page_title = "Welcome, " . esc($profile->name);
$page_subtitle = esc($profile->faculty) . " • Year " . esc($profile->academic_year);
require '../app/views/partials/student_header.php'; 
?>

<!-- Dashboard-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/edit-profile.css">

<div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Student Profile Section -->
        <section class="dashboard-section profile-section">
          <div class="section-header">
            <h2 class="section-title">Student Profile</h2>
            <!-- <button class="btn btn-outline  edit-profile-btn">
              <i class="fas fa-edit"></i>
              <span>Edit Profile</span>
            </button> -->
          </div>
          
          <div class="profile-card">
            <div class="profile-info">
              
 <div class="profile-avatar-container">
    <div class="profile-avatar" id="profileAvatar">
        <?php if (!empty($profile->profile_photo_url)): ?>
            <img src="<?= esc($profile->profile_photo_url) ?>" 
                 alt="Profile Picture" 
                 id="profileImage" 
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;"
                 onerror="this.style.display='none'; document.getElementById('defaultAvatar').style.display='inline-block';">
        <?php else: ?>
            <i class="fas fa-user-graduate" id="defaultAvatar" style="font-size: 4rem; color: #ccc;"></i>
        <?php endif; ?>
    </div>
</div>

              
              <!-- <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert-success">Profile updated successfully!</div>
            <?php endif; ?> -->

              <div class="profile-details">
                <!-- <h3 class="profile-name">Sarah Johnson</h3>
                <p class="profile-email">sarah.johnson@university.edu</p>
                <p class="profile-major">Computer Science • Year 3</p>
                <p class="profile-gpa">GPA: 3.8/4.0</p> -->
                <!-- <h4>Hi,  <?=$username?></h4>
               <h4>Hi,  <?=$userrole?></h4>
                 <h4>Hi,  <?=$useremail?></h4> -->
                 <h3 class="profile-name"><?= esc($profile->name) ?></h3>
                <!-- <p class="profile-id"><strong>Student ID:</strong> <?= esc($profile->student_id) ?></p> -->
                <p class="profile-faculty"><strong></strong> <?= esc($profile->faculty) ?></p>
                <p class="profile-year"><strong></strong> <?= esc($profile->academic_year) . " year" ?></p><p>
                <p class="profile-email"><strong></strong> <?= esc($profile->email) ?></p>
                <p class="profile-bio"><strong></strong>mobile <?= esc($profile->mobile) ?></p>
                <!-- <p class="profile-bio"><strong>Bio:</strong> <?= esc($profile->bio) ?></p> -->
              </div>
            </div>
            <div class="profile-stats">
              <div class="stat-item">
                <span class="stat-number">5</span>
                <span class="stat-label">Active Mentorships</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">12</span>
                <span class="stat-label">Resources Shared</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">8</span>
                <span class="stat-label">Events Attended</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Mentorship Section -->
        <section class="dashboard-section mentorship-section">
          <div class="section-header">
            <h2 class="section-title">Mentorship</h2>
            <a href="<?=ROOT?>/student/Mentorship">
              <button class="btn btn-primary  request-mentorship-btn">
              <i class="fas fa-plus"></i>
              Request Mentorship
            </button>
            </a>
          </div>
          
          <div class="mentorship-grid">
            <div class="mentorship-card">
              <div class="mentor-info">
                <div class="mentor-avatar">
                  <i class="fas fa-user-tie"></i>
                </div>
                <div class="mentor-details">
                  <h4 class="mentor-name">Dr. Michael Chen</h4>
                  <p class="mentor-role">Software Engineer at Google</p>
                  <p class="mentor-specialty">AI/ML, Career Guidance</p>
                </div>
              </div>
              <div class="mentorship-status">
                <span class="status-badge status-active">Active</span>
                <p class="next-session">Next Session: Tomorrow, 2:00 PM</p>
              </div>
            </div>
            
            <div class="mentorship-card">
              <div class="mentor-info">
                <div class="mentor-avatar">
                  <i class="fas fa-user-tie"></i>
                </div>
                <div class="mentor-details">
                  <h4 class="mentor-name">Lisa Rodriguez</h4>
                  <p class="mentor-role">Product Manager at Microsoft</p>
                  <p class="mentor-specialty">Product Strategy, Leadership</p>
                </div>
              </div>
              <div class="mentorship-status">
                <span class="status-badge status-pending">Pending</span>
                <p class="next-session">Awaiting Response</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Aid Requests Section -->
        <section class="dashboard-section aid-requests-section">
          <div class="section-header">
            <h2 class="section-title">Aid Requests</h2>
            <a href="<?=ROOT?>/student/AidReqForm">
            <button class="btn btn-primary  new-aid-btn">
              <i class="fas fa-plus"></i>
              New Aid Request
            </button>
            </a>
          </div>
          
          <div class="aid-requests-table">
            <table>
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Counselor Approval</th>
                  <th>Alumni Acceptance</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Tuition Assistance</td>
                  <td><span class="status-badge status-pending">Pending</span></td>
                  <td><span class="status-badge status-approved">Approved</span></td>
                  <td><span class="status-badge status-waiting">Waiting</span></td>
                  <td>
                    <button class="btn btn-outline btn-sm">View Details</button>
                  </td>
                </tr>
                <tr>
                  <td>Book Grant</td>
                  <td><span class="status-badge status-approved">Approved</span></td>
                  <td><span class="status-badge status-approved">Approved</span></td>
                  <td><span class="status-badge status-accepted">Accepted</span></td>
                  <td>
                    <button class="btn btn-outline btn-sm">View Details</button>
                  </td>
                </tr>
                <tr>
                  <td>Emergency Fund</td>
                  <td><span class="status-badge status-completed">Completed</span></td>
                  <td><span class="status-badge status-approved">Approved</span></td>
                  <td><span class="status-badge status-accepted">Accepted</span></td>
                  <td>
                    <button class="btn btn-outline btn-sm">View Details</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Discussion Forum Section -->
        <section class="dashboard-section forum-section">
          <div class="section-header">
            <h2 class="section-title">Discussion Forum</h2>
            <a href="<?=ROOT?>/student/DiscussionForum">
            <button class="btn btn-outline  go-to-forum-btn">
              <i class="fas fa-external-link-alt"></i>
              Go to Forum
            </button>
            </a>
          </div>
          
          <div class="forum-preview">
            <div class="forum-post">
              <div class="post-header">
                <span class="post-author">Alex Thompson</span>
                <span class="post-time">2 hours ago</span>
              </div>
              <h4 class="post-title">Tips for Landing Your First Tech Internship</h4>
              <p class="post-excerpt">Just wanted to share some insights from my recent internship search...</p>
              <div class="post-stats">
                <span><i class="fas fa-comment"></i> 15 replies</span>
                <span><i class="fas fa-heart"></i> 23 likes</span>
              </div>
            </div>
            
            <div class="forum-post">
              <div class="post-header">
                <span class="post-author">Maria Garcia</span>
                <span class="post-time">5 hours ago</span>
              </div>
              <h4 class="post-title">Study Group for Advanced Algorithms</h4>
              <p class="post-excerpt">Looking for study partners for CS 301. Anyone interested in forming a group?</p>
              <div class="post-stats">
                <span><i class="fas fa-comment"></i> 8 replies</span>
                <span><i class="fas fa-heart"></i> 12 likes</span>
              </div>
            </div>
            
            <div class="forum-post">
              <div class="post-header">
                <span class="post-author">David Kim</span>
                <span class="post-time">1 day ago</span>
              </div>
              <h4 class="post-title">Career Fair Preparation Workshop</h4>
              <p class="post-excerpt">The career services office is hosting a workshop next week...</p>
              <div class="post-stats">
                <span><i class="fas fa-comment"></i> 22 replies</span>
                <span><i class="fas fa-heart"></i> 45 likes</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Events Section -->
        <section class="dashboard-section events-section">
          <div class="section-header">
            <h2 class="section-title">Upcoming Events</h2>
            <a href="<?=ROOT?>/student/EventsBoard">
            <button class="btn btn-outline  view-all-btn">
              <i class="fas fa-calendar"></i>
              View All
            </button>
            </a>
          </div>
          
          <div class="events-grid">
            <div class="event-card">
              <div class="event-date">
                <span class="event-day">15</span>
                <span class="event-month">Dec</span>
              </div>
              <div class="event-details">
                <h4 class="event-title">Alumni Networking Mixer</h4>
                <p class="event-time"><i class="fas fa-clock"></i> 6:00 PM - 8:00 PM</p>
                <p class="event-location"><i class="fas fa-map-marker-alt"></i> University Center</p>
                <p class="event-description">Connect with successful alumni and expand your professional network.</p>
              </div>
              <button class="btn btn-primary btn-sm event-register-btn">Register</button>
            </div>
            
            <div class="event-card">
              <div class="event-date">
                <span class="event-day">22</span>
                <span class="event-month">Dec</span>
              </div>
              <div class="event-details">
                <h4 class="event-title">Career Development Workshop</h4>
                <p class="event-time"><i class="fas fa-clock"></i> 2:00 PM - 4:00 PM</p>
                <p class="event-location"><i class="fas fa-map-marker-alt"></i> Business School</p>
                <p class="event-description">Learn resume writing, interview skills, and job search strategies.</p>
              </div>
              <button class="btn btn-outline btn-sm event-register-btn">Register</button>
            </div>
          </div>
        </section>

        <!-- Shared Resources Section -->
        <section class="dashboard-section resources-section">
          <div class="section-header">
            <h2 class="section-title">Shared Resources</h2>
            <a href="<?=ROOT?>/student/Resources">
            <button class="btn btn-primary  upload-file-btn">
              <i class="fas fa-upload"></i>
              Upload New File
            </button>
            </a>
          </div>
          
          <div class="resources-grid">
            <div class="resource-card">
              <div class="resource-icon">
                <i class="fas fa-file-pdf"></i>
              </div>
              <div class="resource-info">
                <h4 class="resource-name">Study Guide - Data Structures</h4>
                <p class="resource-meta">Shared by Dr. Chen • 2 days ago</p>
                <p class="resource-size">2.4 MB</p>
              </div>
              <button class="btn btn-outline btn-sm">Download</button>
            </div>
            
            <div class="resource-card">
              <div class="resource-icon">
                <i class="fas fa-file-word"></i>
              </div>
              <div class="resource-info">
                <h4 class="resource-name">Resume Template</h4>
                <p class="resource-meta">Shared by Career Services • 1 week ago</p>
                <p class="resource-size">156 KB</p>
              </div>
              <button class="btn btn-outline btn-sm">Download</button>
            </div>
            
            <div class="resource-card">
              <div class="resource-icon">
                <i class="fas fa-file-powerpoint"></i>
              </div>
              <div class="resource-info">
                <h4 class="resource-name">Interview Preparation Slides</h4>
                <p class="resource-meta">Shared by Alumni Panel • 2 weeks ago</p>
                <p class="resource-size">4.1 MB</p>
              </div>
              <button class="btn btn-outline btn-sm">Download</button>
            </div>
          </div>
        </section>

        <!-- Fundraiser Section -->
        <section class="dashboard-section fundraiser-section">
          <div class="section-header">
            <h2 class="section-title">Fundraisers</h2>
            <button class="btn btn-primary  request-fundraiser-btn">
              <i class="fas fa-plus"></i>
              Request Fundraiser
            </button>
          </div>
          
          <div class="fundraiser-content">
            <div class="active-fundraiser">
              <h3 class="fundraiser-title">Computer Science Department Equipment Fund</h3>
              <p class="fundraiser-description">Help us upgrade our computer lab with the latest hardware and software for students.</p>
              
              <div class="fundraiser-progress">
                <div class="progress-bar">
                  <div class="progress-fill" style="width: 75%"></div>
                </div>
                <div class="progress-stats">
                  <span class="raised">$7,500 raised</span>
                  <span class="goal">of $10,000 goal</span>
                </div>
              </div>
              
              <div class="fundraiser-meta">
                <span class="days-left"><i class="fas fa-calendar"></i> 15 days left</span>
                <span class="donors"><i class="fas fa-users"></i> 47 donors</span>
              </div>
              
              <button class="btn btn-primary  donate-btn">Donate Now</button>
            </div>
            
            <div class="no-fundraiser-message" style="display: none;">
              <p>No active fundraisers at the moment. Request a new fundraiser to get started!</p>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/edit-profile.js"></script>
    <script src="<?=ROOT?>/assets/js/dashboard.js"></script>
    <script>
      // Initialize Edit Profile Component only when needed
      document.addEventListener('DOMContentLoaded', function() {
        // Don't auto-initialize - only create when edit profile button is clicked
      });
    </script> -->
  </body>
</html>
