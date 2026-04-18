<?php 
$page_title = "Aid Requests";
$page_subtitle = "Track your aid request submissions";
require '../app/views/partials/student_header.php'; 

$requests = $requests ?? [];

$flashMessage = $_SESSION['flash_message'] ?? null;
if ($flashMessage) {
  unset($_SESSION['flash_message']);
}

$buildFileUrl = static function ($path) {
  $path = trim((string)$path);
  if ($path === '') {
    return '';
  }
  return ROOT . '/' . ltrim($path, '/');
};

$statusLabel = function ($status) {
  $status = strtolower((string)$status);

    if ($status === 'pending_verification') {
        return 'Pending';
    }

  if ($status === 'open') {
    return 'Accepted (Counselor)';
  }

  if (in_array($status, ['approved', 'accepted'], true)) {
    return 'Accepted (Alumni)';
    }

    if ($status === 'completed') {
        return 'Completed';
    }

    if ($status === 'rejected') {
        return 'Rejected';
    }

    return ucfirst($status);
};

$statusClass = function ($status) {
    $status = strtolower((string)$status);

    if ($status === 'pending_verification') {
        return 'status-pending';
    }

  if ($status === 'open') {
    return 'status-accepted-stage1';
  }

  if (in_array($status, ['approved', 'accepted'], true)) {
    return 'status-approved';
    }

    if ($status === 'completed') {
      return 'status-accepted-stage2';
    }

    if ($status === 'rejected') {
        return 'status-rejected';
    }

    return 'status-pending';
};

$pendingCount = 0;
$acceptedCount = 0;
$rejectedCount = 0;
$completedCount = 0;

foreach ($requests as $request) {
    $currentStatus = strtolower((string)($request->status ?? ''));
    if ($currentStatus === 'pending_verification') {
        $pendingCount++;
    } elseif (in_array($currentStatus, ['open', 'approved', 'accepted'], true)) {
        $acceptedCount++;
    } elseif ($currentStatus === 'completed') {
      $completedCount++;
    } elseif ($currentStatus === 'rejected') {
        $rejectedCount++;
    }
}
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <main class="main-content">
    <?php if (!empty($flashMessage['text'])): ?>
      <div class="toast-notice toast-<?= esc($flashMessage['type'] ?? 'info') ?>" role="status" aria-live="polite">
        <div class="toast-content"><?= esc($flashMessage['text']) ?></div>
        <button type="button" class="toast-close" aria-label="Close">&times;</button>
      </div>
    <?php endif; ?>

    <section class="dashboard-section new-request-section">
      <div class="request-form-card">
        <div class="form-intro">
          <h3 class="form-title">Need Assistance?</h3>
          <p class="form-description">Your aid request will undergo an initial counselor review for eligibility and completeness. Upon approval, it will be forwarded to alumni for support consideration.</p>
        </div>
        <div class="form-actions">
          <a href="<?=ROOT?>/Student/AidReqForm">
            <button class="btn btn-primary">
              <i class="fas fa-file-alt"></i>
              <span>Start Application</span>
            </button>
          </a>
        </div>
      </div>
    </section>

    <section class="dashboard-section active-requests-section">
      <div class="section-header">
        <h2 class="card-title">Your Submissions</h2>
      </div>

      <?php if (empty($requests)): ?>
        <p>No aid submissions yet.</p>
      <?php else: ?>
        <div class="requests-table-container">
          <table class="aid-requests-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Rejection Reason</th>
                <th>Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $request): ?>
                <?php $detailId = 'request-details-' . (int)($request->request_id ?? 0); ?>
                <?php
                  $requestStatus = strtolower((string)($request->status ?? ''));
                  $isPendingCounselorReview = $requestStatus === 'pending_verification';

                  $rawReason = trim((string)($request->reason ?? ''));
                  $displayReason = $rawReason;
                  $otherAidPrefill = '';
                  $aidTypeDisplay = ucfirst((string)($request->aid_type ?? 'N/A'));

                  // Display format target:
                  // Aid Type: Other (WIFI router)
                  // Description: original reason only
                  if (strtolower((string)($request->aid_type ?? '')) === 'other') {
                    // Legacy format: "Requested item (Other): <item>\n<reason>"
                    if (preg_match('/^Requested item \(Other\):\s*(.+)$/s', $rawReason, $legacy)) {
                      $legacyPayload = trim((string)$legacy[1]);

                      if (preg_match('/^(.+?)\R+(.*)$/s', $legacyPayload, $parts)) {
                        $otherAidPrefill = trim((string)$parts[1]);
                        $displayReason = trim((string)$parts[2]);
                      } elseif (preg_match('/\s+(because|since|as)\b/i', $legacyPayload, $marker, PREG_OFFSET_CAPTURE)) {
                        $splitAt = (int)$marker[0][1];
                        $otherAidPrefill = trim(substr($legacyPayload, 0, $splitAt));
                        $displayReason = trim(substr($legacyPayload, $splitAt + 1));
                      } else {
                        $otherAidPrefill = $legacyPayload;
                        $displayReason = '';
                      }
                    } elseif (preg_match('/^Requested item \((?!Other\))(.+)\):\s*(.*)$/s', $rawReason, $matches)) {
                      // New format: "Requested item (<item>): <reason>"
                      $otherAidPrefill = trim((string)$matches[1]);
                      $displayReason = trim((string)$matches[2]);
                    }

                    if ($otherAidPrefill !== '') {
                      $aidTypeDisplay = 'Other (' . $otherAidPrefill . ')';
                    }
                  }
                ?>
                <tr>
                  <td><?= esc($aidTypeDisplay) ?></td>
                  <td><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></td>
                  <td><span class="status-badge <?= $statusClass($request->status ?? '') ?>"><?= esc($statusLabel($request->status ?? '')) ?></span></td>
                  <td>
                    <?php if (strtolower((string)($request->status ?? '')) === 'rejected'): ?>
                      <?= esc($request->rejection_reason ?? 'No reason provided') ?>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td><?= esc($request->created_at ?? 'N/A') ?></td>
                  <td>
                    <div class="submission-actions">
                      <button type="button" class="btn btn-outline btn-sm view-details-toggle" data-target="<?= esc($detailId) ?>">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                      </button>
                      <?php if ($isPendingCounselorReview): ?>
                        <form method="POST" action="<?= ROOT ?>/Student/AidRequests/delete/<?= (int)($request->request_id ?? 0) ?>" onsubmit="return confirm('Delete this pending request?');">
                          <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            <span>Delete</span>
                          </button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <tr id="<?= esc($detailId) ?>" class="request-details-row" style="display:none;">
                  <td colspan="6">
                    <?php if ($isPendingCounselorReview): ?>
                      <div class="request-details-actions">
                        <button type="button" class="btn btn-outline btn-sm edit-details-toggle" data-target="edit-<?= esc($detailId) ?>">
                          <i class="fas fa-edit"></i>
                          <span>Edit</span>
                        </button>
                      </div>
                    <?php endif; ?>

                    <div class="request-details-card">
                      <div class="request-detail-item"><span class="request-detail-label">Aid Type</span><strong><?= esc($aidTypeDisplay) ?></strong></div>
                      <div class="request-detail-item"><span class="request-detail-label">Amount</span><strong><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></strong></div>
                      <div class="request-detail-item"><span class="request-detail-label">Mobile Number</span><strong><?= esc($request->mobile_number ?? 'N/A') ?></strong></div>
                      <div class="request-detail-item"><span class="request-detail-label">Description</span><strong><?= esc($displayReason !== '' ? $displayReason : 'N/A') ?></strong></div>
                      <div class="request-detail-item">
                        <span class="request-detail-label">Student ID Document</span>
                        <strong>
                          <?php $studentIdDocUrl = $buildFileUrl($request->student_id_pdf_path ?? ''); ?>
                          <?php if ($studentIdDocUrl !== ''): ?>
                            <a href="<?= esc($studentIdDocUrl) ?>" target="_blank" rel="noopener">View file</a>
                          <?php else: ?>
                            N/A
                          <?php endif; ?>
                        </strong>
                      </div>
                      <div class="request-detail-item">
                        <span class="request-detail-label">Income Statement</span>
                        <strong>
                          <?php $incomeStatementUrl = $buildFileUrl($request->income_statement_path ?? ''); ?>
                          <?php if ($incomeStatementUrl !== ''): ?>
                            <a href="<?= esc($incomeStatementUrl) ?>" target="_blank" rel="noopener">View file</a>
                          <?php else: ?>
                            N/A
                          <?php endif; ?>
                        </strong>
                      </div>
                      <div class="request-detail-item">
                        <span class="request-detail-label">Gramaseva Niladhari Certificate</span>
                        <strong>
                          <?php $gramasevaCertUrl = $buildFileUrl($request->gramaseva_cert_path ?? ''); ?>
                          <?php if ($gramasevaCertUrl !== ''): ?>
                            <a href="<?= esc($gramasevaCertUrl) ?>" target="_blank" rel="noopener">View file</a>
                          <?php else: ?>
                            N/A
                          <?php endif; ?>
                        </strong>
                      </div>
                      <div class="request-detail-item"><span class="request-detail-label">Submitted</span><strong><?= esc($request->created_at ?? 'N/A') ?></strong></div>
                    </div>

                    <?php if ($isPendingCounselorReview): ?>
                      <form id="edit-<?= esc($detailId) ?>" class="request-edit-form" method="POST" action="<?= ROOT ?>/Student/AidRequests/update/<?= (int)($request->request_id ?? 0) ?>" enctype="multipart/form-data" style="display:none;">
                        <h4>Edit Pending Request</h4>
                        <div class="request-edit-grid">
                          <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="tel" name="mobile_number" pattern="[0-9]{10}" required value="<?= esc($request->mobile_number ?? '') ?>">
                          </div>

                          <div class="form-group">
                            <label>Type of Aid Request</label>
                            <select name="aid_type" class="aid-type-select" required>
                              <option value="money" <?= (($request->aid_type ?? '') === 'money') ? 'selected' : '' ?>>Money</option>
                              <option value="laptop" <?= (($request->aid_type ?? '') === 'laptop') ? 'selected' : '' ?>>Laptop</option>
                              <option value="textbooks" <?= (($request->aid_type ?? '') === 'textbooks') ? 'selected' : '' ?>>Textbooks</option>
                              <option value="stationery" <?= (($request->aid_type ?? '') === 'stationery') ? 'selected' : '' ?>>Stationery</option>
                              <option value="other" <?= (($request->aid_type ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                            </select>
                          </div>

                          <div class="form-group amount-edit-group <?= (($request->aid_type ?? '') === 'money') ? '' : 'hidden' ?>">
                            <label>If Money, Amount (LKR)</label>
                            <input type="number" min="1" name="amount" value="<?= esc((string)($request->amount ?? '')) ?>">
                          </div>

                          <div class="form-group other-edit-group <?= (($request->aid_type ?? '') === 'other') ? '' : 'hidden' ?>">
                            <label>If Other, Please Specify</label>
                            <input type="text" name="other_aid_type" value="<?= esc($otherAidPrefill) ?>">
                          </div>

                          <div class="form-group full-width">
                            <label>Description</label>
                            <textarea name="reason" rows="3" required><?= esc($displayReason) ?></textarea>
                          </div>

                          <div class="form-group">
                            <label>Replace Student ID (optional)</label>
                            <input type="file" name="student_id_pdf" accept=".pdf,.jpg,.jpeg,.png">
                          </div>

                          <div class="form-group">
                            <label>Replace Income Statement (optional)</label>
                            <input type="file" name="income_statement" accept=".pdf">
                          </div>

                          <div class="form-group">
                            <label>Replace Gramaseva Certificate (optional)</label>
                            <input type="file" name="gramaseva_certificate" accept=".pdf">
                          </div>
                        </div>
                        <div class="request-edit-actions">
                          <button type="button" class="btn btn-outline btn-sm edit-cancel" data-target="edit-<?= esc($detailId) ?>">Cancel</button>
                          <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <section class="dashboard-section stats-section">
      <div class="section-header">
        <h2 class="card-title">Submission Status</h2>
      </div>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-content">
            <h3 class="stat-number"><?= (int)$pendingCount ?></h3>
            <p class="stat-label">Pending</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-content">
            <h3 class="stat-number"><?= (int)$acceptedCount ?></h3>
            <p class="stat-label">Accepted</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-content">
            <h3 class="stat-number"><?= (int)$completedCount ?></h3>
            <p class="stat-label">Completed</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-content">
            <h3 class="stat-number"><?= (int)$rejectedCount ?></h3>
            <p class="stat-label">Rejected</p>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
  (function () {
    const toast = document.querySelector('.toast-notice');
    if (toast) {
      const closeBtn = toast.querySelector('.toast-close');
      const closeToast = function () {
        toast.classList.add('is-hiding');
        setTimeout(() => {
          if (toast && toast.parentNode) {
            toast.parentNode.removeChild(toast);
          }
        }, 280);
      };

      if (closeBtn) {
        closeBtn.addEventListener('click', closeToast);
      }

      setTimeout(closeToast, 3600);
    }

    const toggles = document.querySelectorAll('.view-details-toggle');
    if (!toggles.length) return;

    toggles.forEach((btn) => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-target');
        const target = document.getElementById(targetId);
        if (!target) return;

        const isHidden = target.style.display === 'none' || target.style.display === '';
        target.style.display = isHidden ? 'table-row' : 'none';

        const label = btn.querySelector('span');
        if (label) {
          label.textContent = isHidden ? 'Hide Details' : 'View Details';
        }
      });
    });

    const editToggles = document.querySelectorAll('.edit-details-toggle');
    editToggles.forEach((btn) => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-target');
        const target = document.getElementById(targetId);
        if (!target) return;

        const isHidden = target.style.display === 'none' || target.style.display === '';
        target.style.display = isHidden ? 'block' : 'none';

        const label = btn.querySelector('span');
        if (label) {
          label.textContent = isHidden ? 'Close Edit' : 'Edit';
        }
      });
    });

    const editCancelButtons = document.querySelectorAll('.edit-cancel');
    editCancelButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-target');
        const target = document.getElementById(targetId);
        if (!target) return;
        target.style.display = 'none';
      });
    });

    const editForms = document.querySelectorAll('.request-edit-form');
    editForms.forEach((form) => {
      const aidType = form.querySelector('.aid-type-select');
      const amountGroup = form.querySelector('.amount-edit-group');
      const otherGroup = form.querySelector('.other-edit-group');
      const amountInput = amountGroup ? amountGroup.querySelector('input[name="amount"]') : null;
      const otherInput = otherGroup ? otherGroup.querySelector('input[name="other_aid_type"]') : null;

      const sync = function () {
        const isMoney = aidType && aidType.value === 'money';
        const isOther = aidType && aidType.value === 'other';

        if (amountGroup && amountInput) {
          amountGroup.classList.toggle('hidden', !isMoney);
          amountInput.required = isMoney;
          if (!isMoney) amountInput.value = '';
        }

        if (otherGroup && otherInput) {
          otherGroup.classList.toggle('hidden', !isOther);
          otherInput.required = isOther;
          if (!isOther) otherInput.value = '';
        }
      };

      if (aidType) {
        aidType.addEventListener('change', sync);
        sync();
      }
    });
  })();
</script>
</body>
</html>
