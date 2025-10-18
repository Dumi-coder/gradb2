<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aid Requests - GradBridge</title>
    <meta name="description" content="Review and respond to student aid requests — monetary or physical." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    
  </head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Aid Requests</h1>
            <p class="alumni-role">Review and respond to student aid requests — monetary or physical.</p>
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
        <!-- Pending Aid Requests Section -->
        <section class="dashboard-section pending-requests-section">
          <div class="section-header">
            <h2 class="section-title">Pending Aid Requests</h2>
          </div>
          
          <div class="pending-requests-container">
            <?php foreach ($aidRequestsData['pending'] as $request): ?>
            <div class="aid-request-card">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                  <p class="request-type"><?= esc($request['request_type']) ?></p>
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
              
              <div class="request-details">
                <?php if (isset($request['amount_requested'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value"><?= esc($request['amount_requested']) ?></span>
                </div>
                <?php elseif (isset($request['estimated_value'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Estimated Value:</span>
                  <span class="detail-value"><?= esc($request['estimated_value']) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-item">
                  <span class="detail-label">Type:</span>
                  <span class="detail-value"><?= esc($request['aid_type']) ?></span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-primary btn-sm approve-btn">Approve</button>
                <button class="btn btn-outline btn-sm decline-btn">Decline</button>
              </div>
            </div>
            <?php endforeach; ?>
            
            <div class="view-all-link">
              <a href="#" class="view-all-link-text">View All Pending Aid Requests</a>
            </div>
          </div>
        </section>

        <!-- Recently Approved Section -->
        <section class="dashboard-section approved-requests-section">
          <div class="section-header">
            <h2 class="section-title">Recently Approved</h2>
          </div>
          
          <div class="approved-requests-container">
            <?php foreach ($aidRequestsData['approved'] as $request): ?>
            <div class="aid-request-card approved">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                  <p class="request-type"><?= esc($request['request_type']) ?></p>
                </div>
                <span class="status-badge status-approved">APPROVED</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description']) ?></p>
              </div>
              
              <div class="request-details">
                <div class="detail-item">
                  <span class="detail-label">Provided Value:</span>
                  <span class="detail-value"><?= esc($request['provided_value']) ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Type:</span>
                  <span class="detail-value"><?= esc($request['aid_type']) ?></span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-primary btn-sm details-btn">View Details</button>
                <button class="btn btn-outline btn-sm complete-btn">Mark as Completed</button>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Completed Aid Requests Section -->
        <section class="dashboard-section completed-requests-section">
          <div class="section-header">
            <h2 class="section-title">Completed Aid Requests</h2>
          </div>
          
          <div class="completed-requests-container">
            <?php foreach ($aidRequestsData['completed'] as $request): ?>
            <div class="aid-request-card completed">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                  <p class="request-type"><?= esc($request['request_type']) ?></p>
                </div>
                <span class="status-badge status-completed">COMPLETED</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description']) ?></p>
              </div>
              
              <div class="request-details">
                <?php if (isset($request['amount_provided'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Amount Provided:</span>
                  <span class="detail-value"><?= esc($request['amount_provided']) ?></span>
                </div>
                <?php elseif (isset($request['provided_value'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Provided Value:</span>
                  <span class="detail-value"><?= esc($request['provided_value']) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-item">
                  <span class="detail-label">Type:</span>
                  <span class="detail-value"><?= esc($request['aid_type']) ?></span>
                </div>
              </div>
              
              <div class="request-footer">
                <p class="completion-date">Completed: <?= esc($request['completed_date']) ?></p>
                <button class="btn btn-primary btn-sm details-btn">View Details</button>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Request Statistics Section -->
        <section class="dashboard-section statistics-section">
          <div class="section-header">
            <h2 class="section-title">Request Statistics</h2>
          </div>
          
          <div class="statistics-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number">3</div>
                <div class="stat-label">Pending Requests</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-check"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number">8</div>
                <div class="stat-label">Approved This Year</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number">$12,450</div>
                <div class="stat-label">Total Aid Received</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number">85%</div>
                <div class="stat-label">Approval Rate</div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/aid-requests.js"></script>
  </body>
</html>
