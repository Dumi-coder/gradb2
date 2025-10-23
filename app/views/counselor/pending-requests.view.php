<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Requests - Counselor Dashboard - GradBridge</title>
    <meta name="description" content="Review and manage pending student aid requests as a counselor." />
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
            <h1 class="welcome-text">Pending Requests</h1>
            <p class="counselor-role">Review and manage pending aid requests</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">8</span>
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
        <!-- Pending Requests Section -->
        <section class="dashboard-section pending-requests-section">
          <div class="section-header">
            <h2 class="card-title">Pending Aid Requests</h2>
            <div class="section-actions">
              <select class="filter-select" id="urgencyFilter">
                <option value="">All Urgency Levels</option>
                <option value="urgent">Urgent</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
              </select>
              <button class="btn btn-outline btn-sm" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
              </button>
            </div>
          </div>
          
          <div class="requests-container">
            <!-- Request Card 1 -->
            <div class="request-card urgent-request" data-urgency="urgent" data-type="emergency">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-001</h3>
                  <div class="request-meta">
                    <span class="student-name">Sarah Johnson</span>
                    <span class="student-id">ID: 2024001</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="urgency-badge urgent">Urgent</span>
                  <span class="type-badge">Emergency Fund</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 2,500</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Submitted:</span>
                  <span class="detail-value">Dec 15, 2024 - 2 hours ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Family emergency requiring immediate financial assistance for medical bills</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Documents:</span>
                  <div class="document-links">
                    <button class="document-link" onclick="downloadDocument('Financial Statement', 'AR-2024-001')">
                      <i class="fas fa-file-pdf"></i>
                      Financial Statement
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Medical Bills', 'AR-2024-001')">
                      <i class="fas fa-file-pdf"></i>
                      Medical Bills
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Enrollment Proof', 'AR-2024-001')">
                      <i class="fas fa-file-pdf"></i>
                      Enrollment Proof
                      <i class="fas fa-download"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-001')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="approveRequest('AR-2024-001')">
                  <i class="fas fa-check"></i>
                  <span>Approve</span>
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectRequest('AR-2024-001')">
                  <i class="fas fa-times"></i>
                  <span>Reject</span>
                </button>
              </div>
            </div>

            <!-- Request Card 2 -->
            <div class="request-card high-request" data-urgency="high" data-type="tuition">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-002</h3>
                  <div class="request-meta">
                    <span class="student-name">Michael Chen</span>
                    <span class="student-id">ID: 2024002</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="urgency-badge high">High</span>
                  <span class="type-badge">Tuition Assistance</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 1,800</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Submitted:</span>
                  <span class="detail-value">Dec 14, 2024 - 1 day ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Unexpected family financial hardship affecting ability to pay semester tuition</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Documents:</span>
                  <div class="document-links">
                    <button class="document-link" onclick="downloadDocument('Financial Statement', 'AR-2024-002')">
                      <i class="fas fa-file-pdf"></i>
                      Financial Statement
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Enrollment Proof', 'AR-2024-002')">
                      <i class="fas fa-file-pdf"></i>
                      Enrollment Proof
                      <i class="fas fa-download"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-002')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="approveRequest('AR-2024-002')">
                  <i class="fas fa-check"></i>
                  <span>Approve</span>
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectRequest('AR-2024-002')">
                  <i class="fas fa-times"></i>
                  <span>Reject</span>
                </button>
              </div>
            </div>

            <!-- Request Card 3 -->
            <div class="request-card medium-request" data-urgency="medium" data-type="textbooks">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-003</h3>
                  <div class="request-meta">
                    <span class="student-name">Emily Rodriguez</span>
                    <span class="student-id">ID: 2024003</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="urgency-badge medium">Medium</span>
                  <span class="type-badge">Textbook Support</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 350</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Submitted:</span>
                  <span class="detail-value">Dec 13, 2024 - 2 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Required textbooks for next semester courses, unable to afford due to part-time job loss</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Documents:</span>
                  <div class="document-links">
                    <button class="document-link" onclick="downloadDocument('Financial Statement', 'AR-2024-003')">
                      <i class="fas fa-file-pdf"></i>
                      Financial Statement
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Course Schedule', 'AR-2024-003')">
                      <i class="fas fa-file-pdf"></i>
                      Course Schedule
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Enrollment Proof', 'AR-2024-003')">
                      <i class="fas fa-file-pdf"></i>
                      Enrollment Proof
                      <i class="fas fa-download"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-003')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="approveRequest('AR-2024-003')">
                  <i class="fas fa-check"></i>
                  <span>Approve</span>
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectRequest('AR-2024-003')">
                  <i class="fas fa-times"></i>
                  <span>Reject</span>
                </button>
              </div>
            </div>

            <!-- Request Card 4 -->
            <div class="request-card low-request" data-urgency="low" data-type="technology">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="request-id">#AR-2024-004</h3>
                  <div class="request-meta">
                    <span class="student-name">David Kim</span>
                    <span class="student-id">ID: 2024004</span>
                  </div>
                </div>
                <div class="request-badges">
                  <span class="urgency-badge low">Low</span>
                  <span class="type-badge">Technology Grant</span>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-row">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value amount">Rs. 800</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Submitted:</span>
                  <span class="detail-value">Dec 12, 2024 - 3 days ago</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Reason:</span>
                  <span class="detail-value">Laptop replacement needed for coursework, current device is failing</span>
                </div>
                <div class="detail-row">
                  <span class="detail-label">Documents:</span>
                  <div class="document-links">
                    <button class="document-link" onclick="downloadDocument('Financial Statement', 'AR-2024-004')">
                      <i class="fas fa-file-pdf"></i>
                      Financial Statement
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Laptop Quote', 'AR-2024-004')">
                      <i class="fas fa-file-pdf"></i>
                      Laptop Quote
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="document-link" onclick="downloadDocument('Enrollment Proof', 'AR-2024-004')">
                      <i class="fas fa-file-pdf"></i>
                      Enrollment Proof
                      <i class="fas fa-download"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-outline btn-sm" onclick="viewRequestDetails('AR-2024-004')">
                  <i class="fas fa-eye"></i>
                  <span>View Details</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="approveRequest('AR-2024-004')">
                  <i class="fas fa-check"></i>
                  <span>Approve</span>
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectRequest('AR-2024-004')">
                  <i class="fas fa-times"></i>
                  <span>Reject</span>
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
          <button class="btn btn-danger" onclick="rejectRequestFromModal()">
            <i class="fas fa-times"></i>
            Reject
          </button>
          <button class="btn btn-success" onclick="approveRequestFromModal()">
            <i class="fas fa-check"></i>
            Approve
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="confirmationTitle">Confirm Action</h3>
          <button class="modal-close" onclick="closeConfirmationModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <p id="confirmationMessage">Are you sure you want to perform this action?</p>
          
          <div class="note-section">
            <label for="counselorNote" class="note-label">
              <i class="fas fa-sticky-note"></i>
              Add a note (optional)
            </label>
            <textarea 
              id="counselorNote" 
              class="note-textarea" 
              placeholder="Add any additional notes or comments about this decision..."
              rows="3"
            ></textarea>
            <small class="note-help">This note will be visible to the student and included in the request history.</small>
          </div>
          
          <div class="confirmation-actions">
            <button class="btn btn-outline" onclick="closeConfirmationModal()">Cancel</button>
            <button class="btn" id="confirmActionBtn" onclick="executeConfirmedAction()">Confirm</button>
          </div>
        </div>
      </div>
    </div>

    <<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/counselor-dashboard.js"></script>
  </body>
</html>
