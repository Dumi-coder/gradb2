<?php 
$page_title = "Aid Requests";
$page_subtitle = "Review and respond to student aid requests";
require '../app/views/partials/alumni_header.php'; 

$aidRequestsData = $aidRequestsData ?? [];
$pendingRequests = $aidRequestsData['pending'] ?? [];
$approvedRequests = $aidRequestsData['approved'] ?? [];
$completedRequests = $aidRequestsData['completed'] ?? [];

$pendingCount = is_array($pendingRequests) ? count($pendingRequests) : 0;
$approvedCount = is_array($approvedRequests) ? count($approvedRequests) : 0;
$completedCount = is_array($completedRequests) ? count($completedRequests) : 0;
$totalRequestCount = $pendingCount + $approvedCount + $completedCount;

$normalizeAidType = static function ($value) {
  return strtolower(trim((string)$value));
};

$isMonetaryType = static function ($type) {
  return in_array($type, ['monetary', 'money', 'financial'], true);
};
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">

<style>
  .aid-requests-page .dashboard-section {
    max-width: 920px;
    margin-left: auto;
    margin-right: auto;
  }

  .aid-requests-page .aid-request-card {
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-sm);
    border-radius: var(--radius-sm);
    width: 100%;
  }

  .aid-requests-page .pending-requests-container,
  .aid-requests-page .approved-requests-container,
  .aid-requests-page .completed-requests-container {
    display: grid;
    gap: var(--spacing-sm);
  }

  .aid-requests-page .request-header {
    margin-bottom: var(--spacing-sm);
    gap: var(--spacing-xs);
  }

  .aid-requests-page .request-info h3 {
    font-size: var(--font-base);
    margin-bottom: 0;
  }

  .aid-requests-page .request-type {
    font-size: var(--font-sm);
    margin: 0;
  }

  .aid-requests-page .request-description {
    margin-bottom: var(--spacing-sm);
  }

  .aid-requests-page .request-description p {
    font-size: var(--font-sm);
    line-height: 1.45;
    margin: 0;
  }

  .aid-requests-page .request-details {
    padding: var(--spacing-sm);
    margin-bottom: var(--spacing-sm);
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: var(--spacing-xs) var(--spacing-sm);
  }

  .aid-requests-page .detail-item {
    justify-content: space-between;
    text-align: left;
  }

  .aid-requests-page .detail-label,
  .aid-requests-page .detail-value {
    font-size: var(--font-sm);
  }

  .aid-requests-page .request-actions {
    gap: var(--spacing-xs);
    flex-wrap: wrap;
  }

  .aid-requests-page .request-actions .btn {
    padding: 0.42rem 0.72rem;
    font-size: var(--font-xs);
  }

  .aid-requests-page .status-badge {
    font-size: var(--font-xs);
    padding: 0.2rem 0.55rem;
  }

  .aid-requests-page .status-badge.status-approved {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #86efac;
  }

  .aid-requests-page .status-badge.status-completed {
    background-color: #ede9fe;
    color: #5b21b6;
    border: 1px solid #c4b5fd;
  }

  @media (max-width: 768px) {
    .aid-requests-page .dashboard-section {
      max-width: 100%;
    }

    .aid-requests-page .aid-request-card {
      padding: var(--spacing-sm);
    }

    .aid-requests-page .request-details {
      grid-template-columns: 1fr;
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
                  <?php $pendingAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
                  <?php if ($pendingAidType !== 'other' && $pendingAidType !== ''): ?>
                  <p class="request-type\"><?= esc($request['request_type'] ?? ucfirst($pendingAidType)) ?></p>
                  <?php endif; ?>
                </div>
                <span class="status-badge status-pending">Pending</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
              </div>
              
              <div class="request-details">
                <?php if ($isMonetaryType($pendingAidType) && !empty($request['amount_requested'])): ?>
                <div class="detail-item">
                  <span class="detail-value\"><?= esc($request['amount_requested']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($pendingAidType === 'laptop'): ?>
                <div class="detail-item">
                  <span class="detail-value">Laptop</span>
                </div>
                <?php endif; ?>
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
                  <?php $approvedAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
                  <?php if ($approvedAidType !== 'other' && $approvedAidType !== ''): ?>
                  <p class="request-type\"><?= esc($request['request_type'] ?? ucfirst($approvedAidType)) ?></p>
                  <?php endif; ?>
                </div>
                <span class="status-badge status-approved">Approved</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
              </div>
              
              <div class="request-details">
                <?php if ($isMonetaryType($approvedAidType) && !empty($request['provided_value'])): ?>
                <div class="detail-item">
                  <span class="detail-value\"><?= esc($request['provided_value']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($approvedAidType === 'laptop'): ?>
                <div class="detail-item">
                  <span class="detail-value">Laptop</span>
                </div>
                <?php endif; ?>
              </div>
              
              <div class="request-actions">
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
                  <?php $completedAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
                  <?php if ($completedAidType !== 'other' && $completedAidType !== ''): ?>
                  <p class="request-type\"><?= esc($request['request_type'] ?? ucfirst($completedAidType)) ?></p>
                  <?php endif; ?>
                </div>
                <span class="status-badge status-completed">Completed</span>
              </div>
              
              <div class="request-description">
                <p><?= esc($request['description'] ?? 'No description provided') ?></p>
              </div>
              
              <div class="request-details">
                <?php if ($isMonetaryType($completedAidType) && !empty($request['amount_provided'])): ?>
                <div class="detail-item">
                  <span class="detail-value"><?= esc($request['amount_provided']) ?></span>
                </div>
                <?php elseif ($isMonetaryType($completedAidType) && !empty($request['provided_value'])): ?>
                <div class="detail-item">
                  <span class="detail-value"><?= esc($request['provided_value']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($completedAidType === 'laptop'): ?>
                <div class="detail-item">
                  <span class="detail-value">Laptop</span>
                </div>
                <?php endif; ?>
              </div>
              
              <div class="request-footer">
                <p class="completion-date">Completed: <?= esc($request['completed_date'] ?? 'N/A') ?></p>
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
                <div class="stat-number"><?= (int)$pendingCount ?></div>
                <div class="stat-label">Pending Requests</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-check"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number"><?= (int)$approvedCount ?></div>
                <div class="stat-label">Approved Requests</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-flag-checkered"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number"><?= (int)$completedCount ?></div>
                <div class="stat-label">Completed Requests</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-list"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number"><?= (int)$totalRequestCount ?></div>
                <div class="stat-label">Total Requests</div>
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
