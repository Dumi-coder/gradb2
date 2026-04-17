<?php 
$page_title = "Completed Requests";
$page_subtitle = "Aid requests marked as completed";
require '../app/views/partials/counselor_header.php'; 

$completedRequests = $completedRequests ?? [];
$flashMessage = $flashMessage ?? null;
$buildFileUrl = static function ($path) {
  $path = trim((string)$path);
  if ($path === '') {
    return '';
  }
  return ROOT . '/' . ltrim($path, '/');
};
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor.css">



<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section">
      <div class="section-header">
        <h2 class="card-title">Completed Requests</h2>
      </div>

      <?php if (!empty($flashMessage)): ?>
        <div class="alert alert-info alert-block">
          <?= esc($flashMessage) ?>
        </div>
      <?php endif; ?>

      <?php if (empty($completedRequests)): ?>
        <div class="request-card card-top-gap">
          <div class="request-details">
            <p class="detail-value">No completed requests yet.</p>
          </div>
        </div>
      <?php else: ?>
        <div class="req-grid">
          <?php foreach ($completedRequests as $request): ?>
            <div class="req-card">
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($request->student_name ?? 'Student') ?></p>
                  <p class="summary-meta">ID: <?= esc($request->student_id ?? 'N/A') ?> Â· <?= esc($request->faculty_name ?? 'Faculty N/A') ?></p>
                </div>

                <div>
                  <span class="summary-label">Aid Type</span>
                  <p class="summary-value"><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></p>
                </div>

                <div>
                  <span class="summary-label">Submitted</span>
                  <p class="summary-value summary-value-sm"><?= esc($request->created_at ?? 'N/A') ?></p>
                </div>

                <div class="summary-actions">
                  <span class="chip-completed">Completed</span>
                  <button type="button" class="btn btn-outline btn-sm open-review-modal" data-modal-id="review-modal-<?= (int)$request->request_id ?>">
                    <i class="fas fa-eye"></i>
                    <span>Review</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="review-modal" id="review-modal-<?= (int)$request->request_id ?>" aria-hidden="true">
              <div class="review-modal-panel" role="dialog" aria-modal="true" aria-labelledby="review-title-<?= (int)$request->request_id ?>">
                <div class="review-modal-head">
                  <div>
                    <h3 class="review-modal-title" id="review-title-<?= (int)$request->request_id ?>"><?= esc($request->student_name ?? 'Student') ?> Â· Aid Details</h3>
                    <p class="review-modal-meta">Request #<?= (int)$request->request_id ?> Â· Status: Completed</p>
                  </div>
                  <button type="button" class="modal-close-btn" data-close-modal aria-label="Close review modal">&times;</button>
                </div>

                <div class="req-row"><span class="req-label">Email</span><strong><?= esc($request->student_email ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Mobile</span><strong><?= esc($request->mobile_number ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Faculty</span><strong><?= esc($request->faculty_name ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></strong></div>
                <div class="req-row"><span class="req-label">Amount</span><strong><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></strong></div>
                <div class="req-row"><span class="req-label">Reason</span><strong><?= esc($request->reason ?? 'N/A') ?></strong></div>
                <div class="req-row">
                  <span class="req-label">Student ID Document</span>
                  <strong>
                    <?php $studentIdDocUrl = $buildFileUrl($request->student_id_pdf_path ?? ''); ?>
                    <?php if ($studentIdDocUrl !== ''): ?>
                      <a href="<?= esc($studentIdDocUrl) ?>" target="_blank" rel="noopener">View file</a>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </strong>
                </div>
                <div class="req-row">
                  <span class="req-label">Income Statement</span>
                  <strong>
                    <?php $incomeStatementUrl = $buildFileUrl($request->income_statement_path ?? ''); ?>
                    <?php if ($incomeStatementUrl !== ''): ?>
                      <a href="<?= esc($incomeStatementUrl) ?>" target="_blank" rel="noopener">View file</a>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </strong>
                </div>
                <div class="req-row">
                  <span class="req-label">Gramaseva Niladhari Certificate</span>
                  <strong>
                    <?php $gramasevaCertUrl = $buildFileUrl($request->gramaseva_cert_path ?? ''); ?>
                    <?php if ($gramasevaCertUrl !== ''): ?>
                      <a href="<?= esc($gramasevaCertUrl) ?>" target="_blank" rel="noopener">View file</a>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </strong>
                </div>
                <div class="req-row"><span class="req-label">Submitted</span><strong><?= esc($request->created_at ?? 'N/A') ?></strong></div>

                <?php if (!empty($request->alumnus_user_id)): ?>
                  <div class="req-row"><span class="req-label">Accepted By</span><strong><?= esc($request->alumnus_name ?? ('Alumni User #' . (int)$request->alumnus_user_id)) ?></strong></div>
                  <div class="req-row"><span class="req-label">Alumni Email</span><strong><?= esc($request->alumnus_email ?? 'N/A') ?></strong></div>
                  <div class="req-row"><span class="req-label">Alumni Mobile</span><strong><?= esc($request->alumnus_mobile ?? 'N/A') ?></strong></div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
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

    const openModal = function (modal) {
      if (!modal) return;
      modal.style.display = 'flex';
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    };

    openButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const modalId = btn.getAttribute('data-modal-id');
        const modal = document.getElementById(modalId);
        openModal(modal);
      });
    });

    closeButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const modal = btn.closest('.review-modal');
        closeModal(modal);
      });
    });

    document.querySelectorAll('.review-modal').forEach((modal) => {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal(modal);
        }
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      const activeModal = document.querySelector('.review-modal[aria-hidden="false"]');
      if (activeModal) {
        closeModal(activeModal);
      }
    });
  })();
</script>

</body>
</html>
