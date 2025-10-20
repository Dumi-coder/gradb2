<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Analytics & Reports - Counselor Dashboard - GradBridge</title>
    <meta name="description" content="View analytics and generate reports for student aid requests as a counselor." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor-dashboard.css">
  </head>

  <body>
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Analytics & Reports</h1>
            <p class="counselor-role">View insights and generate reports</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/counselor/Logout">
            <button class="btn btn-primary logout-btn">
              <i class="fas fa-sign-out-alt"></i>
              Logout
            </button>
            </a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      <!-- sidebar -->
      <?php require '../app/views/partials/counselor_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Quick Stats Section -->
        <section class="dashboard-section quick-stats-section">
          <div class="section-header">
            <h2 class="card-title">Quick Overview</h2>
            <div class="section-actions">
              <select class="filter-select" id="timeFilter">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
              </select>
            </div>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">247</h3>
                <p class="stat-label">Total Requests</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">78%</h3>
                <p class="stat-label">Approval Rate</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">$89,450</h3>
                <p class="stat-label">Total Disbursed</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">2.3</h3>
                <p class="stat-label">Avg. Processing Days</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Request Types Breakdown -->
        <section class="dashboard-section breakdown-section">
          <div class="section-header">
            <h2 class="card-title">Request Types Breakdown</h2>
          </div>
          
          <div class="breakdown-grid">
            <div class="breakdown-item">
              <div class="breakdown-header">
                <h4>Emergency Fund</h4>
                <span class="breakdown-count">45</span>
              </div>
              <div class="breakdown-bar">
                <div class="breakdown-fill" style="width: 85%"></div>
              </div>
              <div class="breakdown-stats">
                <span>Approved: 38</span>
                <span>Rejected: 7</span>
              </div>
            </div>

            <div class="breakdown-item">
              <div class="breakdown-header">
                <h4>Tuition Assistance</h4>
                <span class="breakdown-count">78</span>
              </div>
              <div class="breakdown-bar">
                <div class="breakdown-fill" style="width: 75%"></div>
              </div>
              <div class="breakdown-stats">
                <span>Approved: 62</span>
                <span>Rejected: 16</span>
              </div>
            </div>

            <div class="breakdown-item">
              <div class="breakdown-header">
                <h4>Textbook Support</h4>
                <span class="breakdown-count">89</span>
              </div>
              <div class="breakdown-bar">
                <div class="breakdown-fill" style="width: 95%"></div>
              </div>
              <div class="breakdown-stats">
                <span>Approved: 85</span>
                <span>Rejected: 4</span>
              </div>
            </div>

            <div class="breakdown-item">
              <div class="breakdown-header">
                <h4>Technology Grant</h4>
                <span class="breakdown-count">35</span>
              </div>
              <div class="breakdown-bar">
                <div class="breakdown-fill" style="width: 80%"></div>
              </div>
              <div class="breakdown-stats">
                <span>Approved: 28</span>
                <span>Rejected: 7</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Report Generation -->
        <section class="dashboard-section report-section">
          <div class="section-header">
            <h2 class="card-title">Generate Report</h2>
            <div class="section-actions">
              <button class="btn btn-primary" onclick="generateReport()">
                <i class="fas fa-download"></i>
                <span>Generate Report</span>
              </button>
            </div>
          </div>
          
          <div class="report-form">
            <div class="form-row">
              <div class="form-group">
                <label for="reportType">Report Type</label>
                <select id="reportType" class="form-select">
                  <option value="summary">Summary Report</option>
                  <option value="detailed">Detailed Analysis</option>
                  <option value="financial">Financial Report</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="dateRange">Date Range</label>
                <select id="dateRange" class="form-select">
                  <option value="7">Last 7 Days</option>
                  <option value="30" selected>Last 30 Days</option>
                  <option value="90">Last 90 Days</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="format">Format</label>
                <select id="format" class="form-select">
                  <option value="pdf">PDF</option>
                  <option value="excel">Excel</option>
                  <option value="csv">CSV</option>
                </select>
              </div>
            </div>
          </div>
        </section>

        <!-- Recent Reports -->
        <section class="dashboard-section recent-reports-section">
          <div class="section-header">
            <h2 class="card-title">Recent Reports</h2>
            <div class="section-actions">
              <button class="btn btn-outline btn-sm" onclick="refreshReports()">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
              </button>
            </div>
          </div>
          
          <div class="reports-list">
            <div class="report-item">
              <div class="report-info">
                <h4 class="report-title">Monthly Summary - December 2024</h4>
                <div class="report-meta">
                  <span class="report-type">Summary Report</span>
                  <span class="report-date">Dec 15, 2024</span>
                  <span class="report-size">2.3 MB</span>
                </div>
              </div>
              <div class="report-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-eye"></i>
                  <span>View</span>
                </button>
                <button class="btn btn-primary btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
              </div>
            </div>

            <div class="report-item">
              <div class="report-info">
                <h4 class="report-title">Financial Analysis - Q4 2024</h4>
                <div class="report-meta">
                  <span class="report-type">Financial Report</span>
                  <span class="report-date">Dec 10, 2024</span>
                  <span class="report-size">4.1 MB</span>
                </div>
              </div>
              <div class="report-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-eye"></i>
                  <span>View</span>
                </button>
                <button class="btn btn-primary btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
              </div>
            </div>

            <div class="report-item">
              <div class="report-info">
                <h4 class="report-title">Performance Metrics - November 2024</h4>
                <div class="report-meta">
                  <span class="report-type">Performance Report</span>
                  <span class="report-date">Dec 5, 2024</span>
                  <span class="report-size">1.8 MB</span>
                </div>
              </div>
              <div class="report-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-eye"></i>
                  <span>View</span>
                </button>
                <button class="btn btn-primary btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>

    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/counselor-dashboard.js"></script>
  </body>
</html>
