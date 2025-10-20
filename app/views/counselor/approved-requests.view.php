<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Approved Requests - Counselor Dashboard - GradBridge</title>
    <meta name="description" content="View and manage approved student aid requests as a counselor." />
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
            <h1 class="welcome-text">Approved Requests</h1>
            <p class="counselor-role">View and manage approved aid requests</p>
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
        <!-- Approved Requests Section -->
        <section class="dashboard-section approved-requests-section">
          <div class="section-header">
            <h2 class="card-title">Approved Aid Requests</h2>
            <div class="section-actions">
              <select class="filter-select" id="typeFilter">
                <option value="">All Request Types</option>
                <option value="emergency">Emergency Fund</option>
                <option value="tuition">Tuition Assistance</option>
                <option value="textbooks">Textbook Support</option>
                <option value="technology">Technology Grant</option>
              </select>
              <button class="btn btn-outline btn-sm" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
              </button>
            </div>
          </div>
          
          <div class="requests-container">
            <!-- Approved Request Card 1 -->
            <div class="request-card approved-request" data-type="emergency">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-005</h3>
                  <div class="request-meta">
                    <span class="student-name">Jennifer Martinez</span>
                    <span class="student-id">ID: 2024005</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge approved">Approved</span>
                  <span class="type-badge">Emergency Fund</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Approved:</span>
                  <span class="detail-value amount">$1,200</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved Date:</span>
                  <span class="detail-value">Dec 10, 2024 - 5 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Original Amount:</span>
                  <span class="detail-value">$1,500</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Medical emergency requiring immediate financial assistance</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved By:</span>
                  <span class="detail-value">Dr. Sarah Wilson</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Approved with 20% reduction due to available emergency fund limits</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-005')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewApprovalHistory('AR-2024-005')">
                  <i class="fas fa-history"></i>
                  <span>Approval History</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="markAsDisbursed('AR-2024-005')">
                  <i class="fas fa-check-double"></i>
                  <span>Mark as Disbursed</span>
                </button>
              </div>
            </div>

            <!-- Approved Request Card 2 -->
            <div class="request-card approved-request" data-type="tuition">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-006</h3>
                  <div class="request-meta">
                    <span class="student-name">Robert Thompson</span>
                    <span class="student-id">ID: 2024006</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge approved">Approved</span>
                  <span class="type-badge">Tuition Assistance</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Approved:</span>
                  <span class="detail-value amount">$2,000</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved Date:</span>
                  <span class="detail-value">Dec 8, 2024 - 7 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Original Amount:</span>
                  <span class="detail-value">$2,000</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Family financial hardship affecting semester tuition payment</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved By:</span>
                  <span class="detail-value">Dr. Michael Brown</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Full amount approved based on documented financial need</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-006')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewApprovalHistory('AR-2024-006')">
                  <i class="fas fa-history"></i>
                  <span>Approval History</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="markAsDisbursed('AR-2024-006')">
                  <i class="fas fa-check-double"></i>
                  <span>Mark as Disbursed</span>
                </button>
              </div>
            </div>

            <!-- Approved Request Card 3 -->
            <div class="request-card approved-request" data-type="textbooks">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-007</h3>
                  <div class="request-meta">
                    <span class="student-name">Lisa Anderson</span>
                    <span class="student-id">ID: 2024007</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="status-badge approved">Approved</span>
                  <span class="type-badge">Textbook Support</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Approved:</span>
                  <span class="detail-value amount">$280</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved Date:</span>
                  <span class="detail-value">Dec 5, 2024 - 10 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Original Amount:</span>
                  <span class="detail-value">$350</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Required textbooks for next semester courses</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Approved By:</span>
                  <span class="detail-value">Dr. Sarah Wilson</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Notes:</span>
                  <span class="detail-value">Approved for core textbooks only, excluded optional materials</span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-007')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-info btn-sm" onclick="viewApprovalHistory('AR-2024-007')">
                  <i class="fas fa-history"></i>
                  <span>Approval History</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="markAsDisbursed('AR-2024-007')">
                  <i class="fas fa-check-double"></i>
                  <span>Mark as Disbursed</span>
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
