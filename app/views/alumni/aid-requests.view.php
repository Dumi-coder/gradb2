<?php 
$page_title = "Aid Requests";
$page_subtitle = "Review and respond to student aid requests";
require '../app/views/partials/alumni_header.php'; 

$aidRequestsData = $aidRequestsData ?? [];
$pendingRequests = $aidRequestsData['pending'] ?? [];
$approvedRequests = $aidRequestsData['approved'] ?? [];
$completedRequests = $aidRequestsData['completed'] ?? [];
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">

<style>
  .aid-requests-page .aid-request-card {
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    border-radius: var(--radius-sm);
  }

  .aid-requests-page .approved-requests-container,
  .aid-requests-page .completed-requests-container {
    gap: var(--spacing-md);
  }

  .aid-requests-page .request-header {
    margin-bottom: var(--spacing-sm);
    gap: var(--spacing-sm);
  }

  .aid-requests-page .request-info h3 {
    font-size: var(--font-base);
    margin-bottom: 2px;
  }

  .aid-requests-page .request-type {
    font-size: var(--font-xs);
  }

  .aid-requests-page .request-description {
    margin-bottom: var(--spacing-md);
  }

  .aid-requests-page .request-description p {
    font-size: var(--font-sm);
    line-height: 1.45;
  }

  .aid-requests-page .request-details {
    padding: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
  }

  .aid-requests-page .detail-item {
    justify-content: space-between;
    text-align: left;
  }

  .aid-requests-page .detail-label,
  .aid-requests-page .detail-value {
    font-size: var(--font-xs);
  }

  .aid-requests-page .request-actions {
    gap: var(--spacing-xs);
    flex-wrap: wrap;
  }

  .aid-requests-page .request-actions .btn {
    padding: 0.4rem 0.65rem;
    font-size: var(--font-xs);
  }

  .aid-requests-page .status-badge {
    font-size: var(--font-xs);
    padding: 0.2rem 0.55rem;
  }

  @media (max-width: 768px) {
    .aid-requests-page .aid-request-card {
      padding: var(--spacing-sm);
    }

    .aid-requests-page .request-header,
    .aid-requests-page .request-footer {
      flex-direction: column;
      align-items: flex-start;
      gap: var(--spacing-xs);
    }
  }
</style>

<div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content aid-requests-page">
        <!-- Pending Aid Requests Section -->
        <section class="dashboard-section pending-requests-section">
          <div class="section-header">
            <h2 class="section-title">Pending Aid Requests</h2>
          </div>
          
          <div class="pending-requests-container">
            <?php if (empty($pendingRequests)): ?>
            <p>No pending requests available.</p>
            <?php else: ?>
            <?php foreach ($pendingRequests as $request): ?>
            <div class="aid-request-card">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name'] ?? 'Student') ?></h3>
                  <p class="request-type"><?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>
                <span class="status-badge status-pending">Pending</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
              </div>
              
              <div class="request-details">
                <?php if (!empty($request['amount_requested'])): ?>
                <div class="detail-item">
                  <span class="detail-label">Amount Requested:</span>
                  <span class="detail-value"><?= esc($request['amount_requested']) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-item">
                  <span class="detail-label">Type:</span>
                  <span class="detail-value"><?= esc($request['aid_type'] ?? 'Aid') ?></span>
                </div>
              </div>
              
              <div class="request-actions">
                <form method="POST" action="<?=ROOT?>/alumni/aid-requests/approve/<?= esc($request['id'] ?? '') ?>" style="display:inline;">
                  <?php if (strtolower((string)($request['aid_type'] ?? '')) === 'monetary'): ?>
                  <button type="submit" class="btn btn-success btn-sm approve-btn">
                    <i class="fas fa-credit-card"></i> Approve &amp; Pay
                  </button>
                  <?php else: ?>
                  <button type="submit" class="btn btn-success btn-sm approve-btn">Approve</button>
                  <?php endif; ?>
                </form>
                <button class="btn btn-danger btn-sm decline-btn">Decline</button>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            
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
            <?php if (empty($approvedRequests)): ?>
            <p>No recently approved requests.</p>
            <?php else: ?>
            <?php foreach ($approvedRequests as $request): ?>
            <div class="aid-request-card approved">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name'] ?? 'Student') ?></h3>
                  <p class="request-type"><?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>
                <span class="status-badge status-approved">Approved</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
              </div>
              
              <div class="request-details">
                <div class="detail-item">
                  <span class="detail-label">Provided Value:</span>
                  <span class="detail-value"><?= esc($request['provided_value'] ?? 'N/A') ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Type:</span>
                  <span class="detail-value"><?= esc($request['aid_type'] ?? 'Aid') ?></span>
                </div>
              </div>
              
              <div class="request-actions">
                <button class="btn btn-primary btn-sm details-btn">View Details</button>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

        <!-- Completed Aid Requests Section -->
        <section class="dashboard-section completed-requests-section">
          <div class="section-header">
            <h2 class="section-title">Completed Aid Requests</h2>
          </div>
          
          <div class="completed-requests-container">
            <?php if (empty($completedRequests)): ?>
            <p>No completed aid requests yet.</p>
            <?php else: ?>
            <?php foreach ($completedRequests as $request): ?>
            <div class="aid-request-card completed">
              <div class="request-header">
                <div class="request-info">
                  <h3 class="student-name"><?= esc($request['student_name'] ?? 'Student') ?></h3>
                  <p class="request-type"><?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>
                <span class="status-badge status-completed">Completed</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
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
                <p class="completion-date">Completed: <?= esc($request['completed_date'] ?? 'N/A') ?></p>
                <button class="btn btn-primary btn-sm details-btn">View Details</button>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
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
                <div class="stat-number">Rs. 12,450</div>
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
              <input type="text" class="form-control" placeholder="Ajith Pieris">
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
