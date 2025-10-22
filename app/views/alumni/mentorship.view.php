<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mentorship - GradBridge</title>
    <meta name="description" content="Manage mentorship requests and connect with students seeking guidance." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    
  </head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Mentorship</h1>
            <p class="header-subtitle">Connect with students seeking guidance and make a difference in their academic and career journey.</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
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
        <!-- Mentorship Requests Section -->
        <section class="dashboard-section mentorship-requests-section">
          <div class="section-header">
            <h2 class="section-title">Student Mentorship Requests</h2>
          </div>
          
          <div class="mentorship-requests-container">
            <?php foreach ($mentorshipData['requests'] as $request): ?>
            <div class="mentorship-request-card">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                  <p class="guidance-type"><?= esc($request['guidance_type']) ?></p>
                </div>
                <?php if ($request['status'] === 'urgent'): ?>
                <span class="status-badge status-urgent">URGENT</span>
                <?php elseif ($request['status'] === 'pending'): ?>
                <span class="status-badge status-pending">PENDING</span>
                <?php endif; ?>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description']) ?></p>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-primary btn-sm accept-btn">Accept</button>
                <button class="btn btn-outline btn-sm decline-btn">Decline</button>
              </div>
            </div>
            <?php endforeach; ?>
            
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
            <?php foreach ($mentorshipData['active'] as $mentorship): ?>
            <div class="mentorship-card">
              <div class="mentorship-header">
                <div class="mentorship-info">
                  <h3 class="student-name"><?= esc($mentorship['student_name']) ?></h3>
                  <p class="mentorship-type"><?= esc($mentorship['mentorship_type']) ?></p>
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

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/mentorship.js"></script>
  </body>
</html>
