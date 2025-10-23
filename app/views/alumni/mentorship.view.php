<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with students seeking guidance";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship.css">

<body class="alumni-dashboard">
<div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= esc($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= esc($_SESSION['error']) ?>
          </div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Mentorship Requests Section -->
        <section class="dashboard-section mentorship-requests-section">
          <div class="section-header">
            <h2 class="section-title">Student Mentorship Requests</h2>
          </div>
          
          <div class="mentorship-requests-container">
            <?php if (!empty($mentorshipData['requests'])): ?>
              <?php foreach ($mentorshipData['requests'] as $request): ?>
              <div class="mentorship-request-card">
                <div class="request-header">
                  <div class="request-info">
                    <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                    <p class="guidance-type"><?= esc($request['guidance_type']) ?></p>
                    <p class="student-details">
                      <small>
                        Student ID: <?= esc($request['student_id']) ?> | 
                        Year: <?= esc($request['academic_year']) ?> | 
                        Faculty: <?= esc($request['faculty_name']) ?>
                      </small>
                    </p>
                  </div>
                  <span class="status-badge status-pending">PENDING</span>
                </div>
                
                <div class="request-description">
                  <p><?= esc($request['description']) ?></p>
                </div>
                
                <div class="request-actions">
                  <button class="btn btn-secondary btn-sm view-student-profile-btn"
                          data-student-name="<?= esc($request['student_name']) ?>"
                          data-student-id="<?= esc($request['student_id']) ?>"
                          data-student-year="<?= esc($request['academic_year']) ?>"
                          data-student-email="<?= esc($request['student_email']) ?>"
                          data-faculty-name="<?= esc($request['faculty_name']) ?>">
                    <i class="fas fa-user"></i> View Student Profile
                  </button>
                  <a href="<?= ROOT ?>/Alumni/Mentorship/accept/<?= $request['id'] ?>" 
                     class="btn btn-success btn-sm accept-btn"
                     onclick="return confirm('Are you sure you want to accept this mentorship request?')">
                    <i class="fas fa-check"></i> Accept
                  </a>
                  <!-- <a href="<?= ROOT ?>/Alumni/Mentorship/reject/<?= $request['id'] ?>" 
                     class="btn btn-danger btn-sm decline-btn"
                     onclick="return confirm('Are you sure you want to reject this mentorship request?')">
                    <i class="fas fa-times"></i> Decline
                  </a> -->
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="no-requests-message">
                <div class="no-requests-icon">
                  <i class="fas fa-user-graduate"></i>
                </div>
                <h3>No Pending Mentorship Requests</h3>
                <p>There are currently no mentorship requests from students in your faculty.</p>
                <p>Check back later or encourage students to submit mentorship requests!</p>
              </div>
            <?php endif; ?>
            
            <div class="view-all-link">
              <a href="#" class="view-all-link-text">View All Student Mentorship Requests</a>
            </div>
          </div>
        </section>

        <!-- Active Mentorships Section -->
        <section class="dashboard-section active-mentorships-section">
          <div class="section-header">
            <h2 class="section-title">Active Mentorships</h2>
          </div>
          
          <div class="active-mentorships-container">
            <?php if (!empty($mentorshipData['active'])): ?>
              <?php foreach ($mentorshipData['active'] as $mentorship): ?>
              <div class="mentorship-card">
                <div class="mentorship-header">
                  <div class="mentorship-info">
                    <h3 class="student-name"><?= esc($mentorship['student_name']) ?></h3>
                    <p class="mentorship-type"><?= esc($mentorship['mentorship_type']) ?></p>
                    <p class="student-details">
                      <small>
                        Student ID: <?= esc($mentorship['student_id']) ?> | 
                        Year: <?= esc($mentorship['academic_year']) ?> | 
                        Faculty: <?= esc($mentorship['faculty_name']) ?>
                      </small>
                    </p>
                  </div>
                  <span class="status-badge status-active">ACTIVE</span>
                </div>
                
                <div class="mentorship-description">
                  <p><?= esc($mentorship['description']) ?></p>
                </div>
                
                <div class="mentorship-actions">
                  <button class="btn btn-primary btn-sm details-btn">More Details</button>
                  <button class="btn btn-outline btn-sm complete-btn">Mark as Completed</button>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="no-active-mentorships">
                <div class="no-active-icon">
                  <i class="fas fa-handshake"></i>
                </div>
                <h3>No Active Mentorships</h3>
                <p>You don't have any active mentorship relationships at the moment.</p>
                <p>Accept mentorship requests from students to start mentoring!</p>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Completed Mentorships Section -->
        <section class="dashboard-section completed-mentorships-section">
          <div class="section-header">
            <h2 class="section-title">Completed Mentorships</h2>
          </div>
          
          <div class="completed-mentorships-container">
            <?php foreach ($mentorshipData['completed'] as $mentorship): ?>
            <div class="completed-mentorship-card">
              <div class="completed-header">
                <div class="completed-info">
                  <h3 class="student-name"><?= esc($mentorship['student_name']) ?></h3>
                  <p class="mentorship-topic"><?= esc($mentorship['topic']) ?></p>
                </div>
                <span class="status-badge status-completed">COMPLETED</span>
              </div>
              
              <div class="completed-description">
                <p><?= esc($mentorship['description']) ?></p>
              </div>
              
              <div class="completed-footer">
                <p class="completion-date">Completed: <?= esc($mentorship['completed_date']) ?></p>
                <button class="btn btn-primary btn-sm details-btn">More Details</button>
              </div>
            </div>
            <?php endforeach; ?>
            
            <div class="view-all-link">
              <a href="#" class="view-all-link-text">View All Completed Mentorships</a>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- Student Profile Modal -->
    <div id="studentProfileModal" class="profile-modal" style="display: none;">
      <div class="profile-modal-content">
        <div class="profile-modal-header">
          <h3><i class="fas fa-user-graduate"></i> Student Profile</h3>
          <button class="profile-modal-close">&times;</button>
        </div>
        
        <div class="profile-modal-body">
          <div class="profile-avatar">
            <div class="avatar-circle">
              <i class="fas fa-user-graduate"></i>
            </div>
          </div>
          
          <div class="profile-info-section">
            <h2 class="profile-name" id="studentProfileName"></h2>
            <p class="profile-subtitle" id="studentProfileMajor"></p>
          </div>

          <div class="profile-details-grid">
            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Academic Year</span>
                <span class="detail-value" id="studentProfileYear"></span>
              </div>
            </div>

            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">GPA</span>
                <span class="detail-value" id="studentProfileGPA"></span>
              </div>
            </div>

            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Email</span>
                <span class="detail-value" id="studentProfileEmail"></span>
              </div>
            </div>

            <div class="profile-detail-item full-width">
              <div class="detail-icon">
                <i class="fas fa-heart"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Interests & Goals</span>
                <span class="detail-value" id="studentProfileInterests"></span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="profile-modal-footer">
          <button class="btn btn-outline close-profile-btn">Close</button>
          <button class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/mentorship.js"></script>
    <script src="<?=ROOT?>/assets/js/profile-modals.js"></script>
  </body>
</html>
