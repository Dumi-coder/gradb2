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
                <button class="btn btn-outline btn-sm">
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
    <script src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>
