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
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-aid-requests.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/alumni_sidebar.php'; ?>

  <main class="main-content aid-requests-page">
    <section class="dashboard-section pending-requests-section">
      <div class="section-header">
        <h2 class="section-title">Pending Aid Requests</h2>
      </div>

      <div class="alumni-req-grid pending-requests-container">
        <?php if (empty($pendingRequests)): ?>
          <p class="empty-state">No pending requests available.</p>
        <?php else: ?>
          <?php foreach ($pendingRequests as $request): ?>
            <?php $pendingModalId = 'pending-review-modal-' . (int)($request['id'] ?? 0); ?>
            <?php $pendingAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
            <?php $pendingSupportText = 'N/A'; ?>
            <?php if ($isMonetaryType($pendingAidType) && !empty($request['amount_requested'])) { $pendingSupportText = (string)$request['amount_requested']; } ?>
            <?php if ($pendingAidType === 'laptop') { $pendingSupportText = 'Laptop'; } ?>

            <div class="req-card">
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($request['student_name'] ?? 'Student') ?></p>
                  <p class="summary-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; <?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>

                <div>
                  <span class="summary-label">Support</span>
                  <p class="summary-value" style="font-size:.9rem;"><?= esc($pendingSupportText) ?></p>
                </div>

                <div>
                  <span class="summary-label">Status</span>
                  <p class="summary-value" style="font-size:.9rem;">Pending</p>
                </div>

                <div class="summary-actions">
                  <span class="chip-state chip-pending">Pending</span>
                  <button type="button" class="btn btn-outline btn-sm open-review-modal" data-modal-id="<?= esc($pendingModalId) ?>">
                    <i class="fas fa-eye"></i>
                    <span>Review</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="review-modal" id="<?= esc($pendingModalId) ?>" aria-hidden="true">
              <div class="review-modal-panel" role="dialog" aria-modal="true" aria-labelledby="title-<?= esc($pendingModalId) ?>">
                <div class="review-modal-head">
                  <div>
                    <h3 class="review-modal-title" id="title-<?= esc($pendingModalId) ?>"><?= esc($request['student_name'] ?? 'Student') ?> &middot; Aid Review</h3>
                    <p class="review-modal-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; Status: Pending</p>
                  </div>
                  <button type="button" class="modal-close-btn" data-close-modal aria-label="Close review modal">&times;</button>
                </div>

                <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc($request['request_type'] ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Support</span><strong><?= esc($pendingSupportText) ?></strong></div>
                <div class="req-row"><span class="req-label">Description</span><strong><?= esc($request['description'] ?? 'No description provided') ?></strong></div>

                <div class="action-wrap">
                  <form method="POST" action="<?=ROOT?>/Alumni/AidRequests/approve/<?= esc($request['id'] ?? '') ?>" style="display:inline;">
                    <?php if (strtolower((string)($request['aid_type'] ?? '')) === 'monetary'): ?>
                      <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-credit-card"></i>
                        <span>Approve &amp; Pay</span>
                      </button>
                    <?php else: ?>
                      <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i>
                        <span>Approve</span>
                      </button>
                    <?php endif; ?>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section approved-requests-section">
      <div class="section-header">
        <h2 class="section-title">Recently Approved</h2>
      </div>

      <div class="alumni-req-grid approved-requests-container">
        <?php if (empty($approvedRequests)): ?>
          <p class="empty-state">No recently approved requests.</p>
        <?php else: ?>
          <?php foreach ($approvedRequests as $request): ?>
            <?php $approvedModalId = 'approved-review-modal-' . (int)($request['id'] ?? 0); ?>
            <?php $approvedAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
            <?php $approvedSupportText = 'N/A'; ?>
            <?php if ($isMonetaryType($approvedAidType) && !empty($request['provided_value'])) { $approvedSupportText = (string)$request['provided_value']; } ?>
            <?php if ($approvedAidType === 'laptop') { $approvedSupportText = 'Laptop'; } ?>

            <div class="req-card">
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($request['student_name'] ?? 'Student') ?></p>
                  <p class="summary-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; <?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>

                <div>
                  <span class="summary-label">Support</span>
                  <p class="summary-value" style="font-size:.9rem;"><?= esc($approvedSupportText) ?></p>
                </div>

                <div>
                  <span class="summary-label">Status</span>
                  <p class="summary-value" style="font-size:.9rem;">Approved</p>
                </div>

                <div class="summary-actions">
                  <span class="chip-state chip-approved">Approved</span>
                  <button type="button" class="btn btn-outline btn-sm open-review-modal" data-modal-id="<?= esc($approvedModalId) ?>">
                    <i class="fas fa-eye"></i>
                    <span>Review</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="review-modal" id="<?= esc($approvedModalId) ?>" aria-hidden="true">
              <div class="review-modal-panel" role="dialog" aria-modal="true" aria-labelledby="title-<?= esc($approvedModalId) ?>">
                <div class="review-modal-head">
                  <div>
                    <h3 class="review-modal-title" id="title-<?= esc($approvedModalId) ?>"><?= esc($request['student_name'] ?? 'Student') ?> &middot; Aid Details</h3>
                    <p class="review-modal-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; Status: Approved</p>
                  </div>
                  <button type="button" class="modal-close-btn" data-close-modal aria-label="Close review modal">&times;</button>
                </div>

                <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc($request['request_type'] ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Support</span><strong><?= esc($approvedSupportText) ?></strong></div>
                <div class="req-row"><span class="req-label">Description</span><strong><?= esc($request['description'] ?? 'No description provided') ?></strong></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section completed-requests-section">
      <div class="section-header">
        <h2 class="section-title">Completed Aid Requests</h2>
      </div>

      <div class="alumni-req-grid completed-requests-container">
        <?php if (empty($completedRequests)): ?>
          <p class="empty-state">No completed aid requests yet.</p>
        <?php else: ?>
          <?php foreach ($completedRequests as $request): ?>
            <?php $completedModalId = 'completed-review-modal-' . (int)($request['id'] ?? 0); ?>
            <?php $completedAidType = $normalizeAidType($request['aid_type'] ?? $request['request_type'] ?? ''); ?>
            <?php $completedSupportText = 'N/A'; ?>
            <?php if ($isMonetaryType($completedAidType) && !empty($request['amount_provided'])) { $completedSupportText = (string)$request['amount_provided']; } ?>
            <?php if ($isMonetaryType($completedAidType) && empty($request['amount_provided']) && !empty($request['provided_value'])) { $completedSupportText = (string)$request['provided_value']; } ?>
            <?php if ($completedAidType === 'laptop') { $completedSupportText = 'Laptop'; } ?>

            <div class="req-card">
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($request['student_name'] ?? 'Student') ?></p>
                  <p class="summary-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; <?= esc($request['request_type'] ?? 'Aid Request') ?></p>
                </div>

                <div>
                  <span class="summary-label">Support</span>
                  <p class="summary-value" style="font-size:.9rem;"><?= esc($completedSupportText) ?></p>
                </div>

                <div>
                  <span class="summary-label">Completed</span>
                  <p class="summary-value" style="font-size:.9rem;"><?= esc($request['completed_date'] ?? 'N/A') ?></p>
                </div>

                <div class="summary-actions">
                  <span class="chip-state chip-completed">Completed</span>
                  <button type="button" class="btn btn-outline btn-sm open-review-modal" data-modal-id="<?= esc($completedModalId) ?>">
                    <i class="fas fa-eye"></i>
                    <span>Review</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="review-modal" id="<?= esc($completedModalId) ?>" aria-hidden="true">
              <div class="review-modal-panel" role="dialog" aria-modal="true" aria-labelledby="title-<?= esc($completedModalId) ?>">
                <div class="review-modal-head">
                  <div>
                    <h3 class="review-modal-title" id="title-<?= esc($completedModalId) ?>"><?= esc($request['student_name'] ?? 'Student') ?> &middot; Aid Details</h3>
                    <p class="review-modal-meta">Request #<?= esc($request['id'] ?? 'N/A') ?> &middot; Status: Completed</p>
                  </div>
                  <button type="button" class="modal-close-btn" data-close-modal aria-label="Close review modal">&times;</button>
                </div>

                <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc($request['request_type'] ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Support</span><strong><?= esc($completedSupportText) ?></strong></div>
                <div class="req-row"><span class="req-label">Description</span><strong><?= esc($request['description'] ?? 'No description provided') ?></strong></div>
                <div class="req-row"><span class="req-label">Completed On</span><strong><?= esc($request['completed_date'] ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Counselor Completion Note</span><strong><?= esc($request['completion_note'] ?? 'No completion note provided') ?></strong></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

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

<script>
  (function () {
    const openButtons = document.querySelectorAll('.open-review-modal');
    const closeButtons = document.querySelectorAll('[data-close-modal]');

    const closeModal = function (modal) {
      if (!modal) return;
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };

    openButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const modalId = this.getAttribute('data-modal-id');
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      });
    });

    closeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        closeModal(this.closest('.review-modal'));
      });
    });

    document.querySelectorAll('.review-modal').forEach(function (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal(modal);
        }
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      const openModal = document.querySelector('.review-modal[aria-hidden="false"]');
      if (openModal) {
        closeModal(openModal);
      }
    });
  })();
</script>
<script src="<?=ROOT?>/assets/js/aid-payment.js"></script>
</body>
</html>
