<?php 
$page_title = "Aid Requests";
$page_subtitle = "Review and respond to student aid requests";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">

<body class="alumni-dashboard">
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
            <?php 
            // Demo hardcoded data for payment integration showcase
            $demoRequests = [
              [
                'id' => 1,
                'student_name' => 'Sarah Johnson',
                'request_type' => 'Emergency Financial Aid',
                'description' => 'Urgent need for tuition fee payment. Semester fee deadline approaching and unable to afford the full amount. Requesting assistance to complete registration.',
                'amount_requested' => '$2,500',
                'aid_type' => 'Monetary',
                'status' => 'urgent'
              ],
              [
                'id' => 2,
                'student_name' => 'Michael Chen',
                'request_type' => 'Textbook Assistance',
                'description' => 'Need help acquiring required textbooks for Computer Science courses. Total cost is quite high for the semester.',
                'amount_requested' => '$450',
                'aid_type' => 'Monetary',
                'status' => 'pending'
              ]
            ];
            
            foreach ($demoRequests as $request): ?>
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
                <?php if ($request['aid_type'] === 'Monetary'): ?>
                <button class="btn btn-primary btn-sm approve-pay-btn" 
                        data-request-id="<?= $request['id'] ?>" 
                        data-amount="<?= esc($request['amount_requested']) ?>"
                        data-student="<?= esc($request['student_name']) ?>"
                        data-type="<?= esc($request['request_type']) ?>">
                  <i class="fas fa-credit-card"></i> Approve & Pay
                </button>
                <?php else: ?>
                <button class="btn btn-primary btn-sm approve-btn">Approve</button>
                <?php endif; ?>
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
            <?php 
            // Demo hardcoded approved requests
            $demoApproved = [
              [
                'student_name' => 'David Kim',
                'request_type' => 'Laptop Assistance',
                'description' => 'Provided funding for new laptop for online classes.',
                'provided_value' => '$800',
                'aid_type' => 'Monetary'
              ],
              [
                'student_name' => 'Lisa Thompson',
                'request_type' => 'Career Workshop Access',
                'description' => 'Sponsored attendance to professional development workshop.',
                'provided_value' => '$350',
                'aid_type' => 'Monetary'
              ]
            ];
            
            foreach ($demoApproved as $request): ?>
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
            <?php 
            // Demo hardcoded completed requests
            $demoCompleted = [
              [
                'student_name' => 'James Wilson',
                'request_type' => 'Internship Stipend',
                'description' => 'Completed funding for unpaid internship program.',
                'amount_provided' => '$1,200',
                'aid_type' => 'Monetary',
                'completed_date' => 'March 15, 2024'
              ],
              [
                'student_name' => 'Maria Garcia',
                'request_type' => 'Study Materials',
                'description' => 'Provided funds for specialized study materials and software licenses.',
                'amount_provided' => '$650',
                'aid_type' => 'Monetary',
                'completed_date' => 'February 28, 2024'
              ]
            ];
            
            foreach ($demoCompleted as $request): ?>
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

    <!-- Payment Modal -->
    <div id="paymentModal" class="payment-modal" style="display: none;">
      <div class="payment-modal-content">
        <div class="payment-modal-header">
          <h3><i class="fas fa-credit-card"></i> Payment Integration</h3>
          <button class="payment-modal-close">&times;</button>
        </div>
        
        <div class="payment-modal-body">
          <div class="payment-summary">
            <h4>Aid Request Summary</h4>
            <div class="summary-row">
              <span>Student:</span>
              <span id="paymentStudentName"></span>
            </div>
            <div class="summary-row">
              <span>Request Type:</span>
              <span id="paymentRequestType"></span>
            </div>
            <div class="summary-row total">
              <span>Amount:</span>
              <span id="paymentAmount"></span>
            </div>
          </div>

          <div class="payment-methods">
            <h4>Select Payment Method</h4>
            <div class="payment-method-grid">
              <label class="payment-method-card">
                <input type="radio" name="payment_method" value="card" checked>
                <div class="payment-method-content">
                  <i class="fas fa-credit-card"></i>
                  <span>Credit/Debit Card</span>
                </div>
              </label>
              
              <label class="payment-method-card">
                <input type="radio" name="payment_method" value="paypal">
                <div class="payment-method-content">
                  <i class="fab fa-paypal"></i>
                  <span>PayPal</span>
                </div>
              </label>
              
              <label class="payment-method-card">
                <input type="radio" name="payment_method" value="bank">
                <div class="payment-method-content">
                  <i class="fas fa-university"></i>
                  <span>Bank Transfer</span>
                </div>
              </label>
              
              <label class="payment-method-card">
                <input type="radio" name="payment_method" value="wallet">
                <div class="payment-method-content">
                  <i class="fas fa-wallet"></i>
                  <span>Digital Wallet</span>
                </div>
              </label>
            </div>
          </div>

          <div class="payment-details" id="cardPaymentDetails">
            <h4>Card Information</h4>
            <div class="form-group">
              <label>Card Number</label>
              <div class="card-input-wrapper">
                <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                <i class="fas fa-lock card-secure-icon"></i>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Expiry Date</label>
                <input type="text" class="form-control" placeholder="MM/YY" maxlength="5">
              </div>
              <div class="form-group">
                <label>CVV</label>
                <input type="text" class="form-control" placeholder="123" maxlength="3">
              </div>
            </div>
            
            <div class="form-group">
              <label>Cardholder Name</label>
              <input type="text" class="form-control" placeholder="John Doe">
            </div>
          </div>

          <div class="payment-security-badge">
            <i class="fas fa-shield-alt"></i>
            <span>Your payment is secured with 256-bit SSL encryption</span>
          </div>
        </div>
        
        <div class="payment-modal-footer">
          <button class="btn btn-outline cancel-payment-btn">Cancel</button>
          <button class="btn btn-primary proceed-payment-btn">
            <i class="fas fa-lock"></i> Proceed to Pay
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/aid-requests.js"></script>
    <script src="<?=ROOT?>/assets/js/aid-payment.js"></script>
  </body>
</html>
