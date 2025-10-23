<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with alumni mentors";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship.css">

<div class="dashboard-container">

      <!-- sidebar -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>
      
      <!-- Main Content Area -->
      <main class="main-content">
        <div class="content-container">
          <!-- Need Mentorship Section -->
          <section class="mentorship-section">
          <div class="mentorship-card">
            <h2 class="card-title">Need mentorship?</h2>
            <p class="card-description">Before requesting, please check if your query is already answered.</p>
            <a href="<?=ROOT?>/student/Faq">
              <button class="btn btn-outline btn-md faq-btn">
              <i class="fas fa-question-circle"></i>
              <span>View FAQs</span>
              </button>
            </a>
            <div class="divider"></div>
            <p class="question-text">Are your questions still unanswered?</p>
            <a href="<?=ROOT?>/student/MentorshipReq">
            <button class="btn btn-primary btn-lg request-btn">
              <i class="fas fa-plus"></i>
              <span>Yes, Request Mentorship</span>
            </button>  
            </a>         
          </div>
        </section>

        
        <!-- Your Mentorship Requests Section -->
        <section class="requests-section">
          <h2 class="section-title">Your mentorship requests</h2>
          <div class="requests-grid">
            <div class="request-card">
              <div class="request-header">
                <h3 class="request-title">Career guidance</h3>
                <span class="status-badge status-pending">Pending</span>
              </div>
              <p class="request-description">Seeking advice to transition from academia to industry roles...</p>
              <div class="request-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
              </div>
            </div>
            
            <div class="request-card">
              <div class="request-header">
                <h3 class="request-title">Final Year Project</h3>
                <span class="status-badge status-approved">Approved</span>
              </div>
              <p class="request-description">Need help with the machine learning implementation</p>
              <div class="request-actions">
                <button class="btn btn-outline btn-sm view-alumni-profile-btn"
                        data-alumni-name="Dr. Sarah Mitchell"
                        data-alumni-title="Senior ML Engineer at Google"
                        data-alumni-graduation="Class of 2015"
                        data-alumni-expertise="Machine Learning, Data Science, Cloud AI"
                        data-alumni-company="Google"
                        data-alumni-experience="8 years"
                        data-alumni-email="sarah.mitchell@alumni.edu">
                  <i class="fas fa-user"></i>
                  <span>View Alumni Profile</span>
                </button>
              </div>
            </div>
            
            <div class="request-card">
              <div class="request-header">
                <h3 class="request-title">Technical Skills Development</h3>
                <span class="status-badge status-completed">Completed</span>
              </div>
              <p class="request-description">Looking for guidance on advanced programming concepts</p>
              <div class="request-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-user"></i>
                  <span>View Alumni Profile</span>
                </button>
              </div>
            </div>
            
            <div class="request-card">
              <div class="request-header">
                <h3 class="request-title">Leadership Training</h3>
                <span class="status-badge status-waiting">Waiting</span>
              </div>
              <p class="request-description">Want to develop leadership skills for student organizations</p>
              <div class="request-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
              </div>
            </div>
          </div>
                  </section>
        </div>
      </main>
    </div>

    <!-- Alumni Profile Modal -->
    <div id="alumniProfileModal" class="profile-modal" style="display: none;">
      <div class="profile-modal-content">
        <div class="profile-modal-header">
          <h3><i class="fas fa-user-tie"></i> Alumni Profile</h3>
          <button class="profile-modal-close">&times;</button>
        </div>
        
        <div class="profile-modal-body">
          <div class="profile-avatar alumni-avatar">
            <div class="avatar-circle">
              <i class="fas fa-user-tie"></i>
            </div>
          </div>
          
          <div class="profile-info-section">
            <h2 class="profile-name" id="alumniProfileName"></h2>
            <p class="profile-subtitle" id="alumniProfileTitle"></p>
          </div>

          <div class="profile-details-grid">
            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Graduation</span>
                <span class="detail-value" id="alumniProfileGraduation"></span>
              </div>
            </div>

            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-building"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Company</span>
                <span class="detail-value" id="alumniProfileCompany"></span>
              </div>
            </div>

            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-briefcase"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Experience</span>
                <span class="detail-value" id="alumniProfileExperience"></span>
              </div>
            </div>

            <div class="profile-detail-item">
              <div class="detail-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Email</span>
                <span class="detail-value" id="alumniProfileEmail"></span>
              </div>
            </div>

            <div class="profile-detail-item full-width">
              <div class="detail-icon">
                <i class="fas fa-star"></i>
              </div>
              <div class="detail-content">
                <span class="detail-label">Expertise & Skills</span>
                <span class="detail-value" id="alumniProfileExpertise"></span>
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
    <script src="<?=ROOT?>/assets/js/profile-modals.js"></script>
  </body>
</html>
