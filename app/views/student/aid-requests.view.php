<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aid Requests - GradBridge</title>
    <meta name="description" content="Manage your financial aid requests and track application status." />
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
  </head>

  <body>
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Aid Requests</h1>
            <p class="student-role">Aid Review and Approval System</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
          <a href="<?=ROOT?>/student/Logout"><button class="btn btn-primary logout-btn">Logout</button></a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- New Aid Request Section -->
        <section class="dashboard-section new-request-section">
          <div class="request-form-card">
            <div class="form-intro">
              <h3 class="form-title">Looking for Help?</h3>
              <p class="form-description">Whether you need financial aid or physical aid, we’re here to assist you. Send your request, and we’ll get back to you within 48 hours.</p>
            </div>
            
            <div class="form-actions">
              <!-- <button class="btn btn-outline btn-md">
                <i class="fas fa-question-circle"></i>
                <span>View Guidelines</span>
              </button> -->
              <a href="<?=ROOT?>/student/AidReqForm">
              <button class="btn btn-primary btn-md">
                <i class="fas fa-file-alt"></i>
                <span>Start Application</span>
              </button>
              </a>
            </div>
          </div>
        </section>

        <!-- Active Requests Section -->
        <section class="dashboard-section active-requests-section">
          <div class="section-header">
            <h2 class="card-title">Active Requests</h2>
            <div class="section-actions">
              <!-- <button class="btn btn-outline btn-sm">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
              </button> -->
              <!-- <button class="btn btn-outline btn-sm">
                <i class="fas fa-download"></i>
                <span>Export</span>
              </button> -->
            </div>
          </div>
          
          <div class="requests-table-container">
            <table class="aid-requests-table">
              <thead>
                <tr>
                  <th>Request ID</th>
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Submitted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#AR-2024-001</td>
                  <td>
                    <div class="request-type">
                      <i class="fas fa-graduation-cap"></i>
                      <span>Tuition Fee</span>
                    </div>
                  </td>
                  <td>$2,500</td>
                  <td><span class="status-badge status-pending">Under Review</span></td>
                  <td>Dec 15, 2024</td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i>
                        <span>View</span>
                      </button>
                      <!-- <button class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                      </button> -->
                    </div>
                  </td>
                </tr>
                
                <tr>
                  <td>#AR-2024-002</td>
                  <td>
                    <div class="request-type">
                      <i class="fas fa-book"></i>
                      <span>Textbooks</span>
                    </div>
                  </td>
                  <td>$350</td>
                  <td><span class="status-badge status-approved">Approved</span></td>
                  <td>Dec 10, 2024</td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i>
                        <span>View</span>
                      <!-- </button>
                      <button class="btn btn-primary btn-sm">
                        <i class="fas fa-download"></i>
                        <span>Download</span>
                      </button> -->
                    </div>
                  </td>
                </tr>
                
                <tr>
                  <td>#AR-2024-003</td>
                  <td>
                    <div class="request-type">
                      <i class="fas fa-home"></i>
                      <span>Housing</span>
                    </div>
                  </td>
                  <td>$1,200</td>
                  <td><span class="status-badge status-completed">Completed</span></td>
                  <td>Nov 28, 2024</td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i>
                        <span>View</span>
                      </button>
                      <button class="btn btn-outline btn-sm">
                        <i class="fas fa-star"></i>
                        <span>Rate</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
        
        <!-- Recent Updates Section -->
        <section class="dashboard-section updates-section">
          <div class="section-header">
            <h2 class="card-title">Recent Updates</h2>
          </div>
          
          <div class="updates-list">
            <div class="update-item">
              <div class="update-icon">
                <i class="fas fa-bell"></i>
              </div>
              <div class="update-content">
                <h4 class="update-title">Request #AR-2024-002 Approved</h4>
                <p class="update-description">Your textbook funding request has been approved. Funds will be disbursed within 3-5 business days.</p>
                <span class="update-time">2 hours ago</span>
              </div>
            </div>
            
            <div class="update-item">
              <div class="update-icon">
                <i class="fas fa-info-circle"></i>
              </div>
              <div class="update-content">
                <h4 class="update-title">Document Required</h4>
                <p class="update-description">Please upload your latest bank statement for request #AR-2024-001 to continue processing.</p>
                <span class="update-time">1 day ago</span>
              </div>
            </div>
            
            <div class="update-item">
              <div class="update-icon">
                <i class="fas fa-check"></i>
              </div>
              <div class="update-content">
                <h4 class="update-title">Request #AR-2024-003 Completed</h4>
                <p class="update-description">Your housing assistance request has been completed. Thank you for using our services.</p>
                <span class="update-time">3 days ago</span>
              </div>
            </div>
          </div>
        </section>

                <!-- Request Statistics Section -->
        <section class="dashboard-section stats-section">
          <div class="section-header">
            <h2 class="card-title">Request Statistics</h2>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">3</h3>
                <p class="stat-label">Pending Requests</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">8</h3>
                <p class="stat-label">Approved This Year</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">$12,450</h3>
                <p class="stat-label">Total Aid Received</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">85%</h3>
                <p class="stat-label">Approval Rate</p>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>
