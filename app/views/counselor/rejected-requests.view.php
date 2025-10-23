<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rejected Requests - Counselor Dashboard - GradBridge</title>
    <meta name="description" content="View and manage rejected student aid requests as a counselor." />
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
            <h1 class="welcome-text">Rejected Requests</h1>
            <p class="counselor-role">View and manage rejected aid requests</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">2</span>
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
        <!-- Rejected Requests Section -->
        <section class="dashboard-section rejected-requests-section">
          <div class="section-header">
            <h2 class="card-title">Rejected Aid Requests</h2>
            <div class="section-actions">
              <select class="filter-select" id="reasonFilter">
                <option value="">All Rejection Reasons</option>
                <option value="insufficient-docs">Insufficient Documentation</option>
                <option value="not-eligible">Not Eligible</option>
                <option value="duplicate">Duplicate Request</option>
                <option value="funds-exhausted">Funds Exhausted</option>
              </select>
              <button class="btn btn-outline btn-sm" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
              </button>
            </div>
          </div>
          
          <div class="requests-container">
            <!-- Rejected Request Card 1 -->
            <div class="request-card rejected-request" data-reason="insufficient-docs">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-008</h3>
                  <div class="request-meta">
                    <span class="student-name">Alex Johnson</span>
                    <span class="student-id">ID: 2024008</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge rejected">Rejected</span>
                  <span class="type-badge">Technology Grant</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 1,200</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected Date:</span>
                  <span class="detail-value">Dec 7, 2024 - 8 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Laptop replacement needed for coursework</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected By:</span>
                  <span class="detail-value">Dr. Michael Brown</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejection Reason:</span>
                  <span class="detail-value">Insufficient Documentation - Missing financial statements and quotes</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Student can reapply with complete documentation within 30 days</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-008')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewRejectionHistory('AR-2024-008')">
                  <i class="fas fa-history"></i>
                  <span>Rejection History</span>
                </button>
                <button class="btn btn-warning btn-sm" onclick="allowResubmission('AR-2024-008')">
                  <i class="fas fa-redo"></i>
                  <span>Allow Resubmission</span>
                </button>
              </div>
            </div>

            <!-- Rejected Request Card 2 -->
            <div class="request-card rejected-request" data-reason="not-eligible">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-009</h3>
                  <div class="request-meta">
                    <span class="student-name">Maria Garcia</span>
                    <span class="student-id">ID: 2024009</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge rejected">Rejected</span>
                  <span class="type-badge">Emergency Fund</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 3,000</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected Date:</span>
                  <span class="detail-value">Dec 3, 2024 - 12 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Family emergency requiring immediate financial assistance</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected By:</span>
                  <span class="detail-value">Dr. Sarah Wilson</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejection Reason:</span>
                  <span class="detail-value">Not Eligible - Student already received maximum emergency fund allocation this semester</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Student can apply for other types of assistance or reapply next semester</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-009')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewRejectionHistory('AR-2024-009')">
                  <i class="fas fa-history"></i>
                  <span>Rejection History</span>
                </button>
                <button class="btn btn-warning btn-sm" onclick="allowResubmission('AR-2024-009')">
                  <i class="fas fa-redo"></i>
                  <span>Allow Resubmission</span>
                </button>
              </div>
            </div>

            <!-- Rejected Request Card 3 -->
            <div class="request-card rejected-request" data-reason="funds-exhausted">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-010</h3>
                  <div class="request-meta">
                    <span class="student-name">James Wilson</span>
                    <span class="student-id">ID: 2024010</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge rejected">Rejected</span>
                  <span class="type-badge">Tuition Assistance</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 2,500</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected Date:</span>
                  <span class="detail-value">Dec 1, 2024 - 14 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Unexpected family financial hardship affecting tuition payment</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejected By:</span>
                  <span class="detail-value">Dr. Michael Brown</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Rejection Reason:</span>
                  <span class="detail-value">Funds Exhausted - Tuition assistance fund depleted for this semester</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Student placed on priority list for next semester funding</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-010')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewRejectionHistory('AR-2024-010')">
                  <i class="fas fa-history"></i>
                  <span>Rejection History</span>
                </button>
                <button class="btn btn-warning btn-sm" onclick="allowResubmission('AR-2024-010')">
                  <i class="fas fa-redo"></i>
                  <span>Allow Resubmission</span>
                </button>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>

    <!-- Request Details Modal -->
    <div class="modal" id="requestDetailsModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Request Details</h3>
          <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body" id="modalBody">
          <!-- Dynamic content will be inserted here -->
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" onclick="closeModal()">Close</button>
        </div>
      </div>
    </div>

    <script type="module" src="assets/js/main.js"></script>
    <script src="assets/js/counselor-dashboard.js"></script>
  </body>
</html>
