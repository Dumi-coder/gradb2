<?php
$page_title = "Counselor Dashboard";
$page_subtitle = "Aid request overview and verification";
require '../app/views/partials/counselor_header.php';

$pendingRequests = $pendingRequests ?? [];
$acceptedRequests = $acceptedRequests ?? [];
$completedRequests = $completedRequests ?? [];
$rejectedRequests = $rejectedRequests ?? [];

$allRequests = array_merge($pendingRequests, $acceptedRequests, $completedRequests, $rejectedRequests);
usort($allRequests, static function ($a, $b) {
  return strtotime((string)($b->created_at ?? '')) <=> strtotime((string)($a->created_at ?? ''));
});

$buildFileUrl = static function ($path) {
  $path = trim((string)$path);
  if ($path === '') {
    return '';
  }
  return ROOT . '/' . ltrim($path, '/');
};

$getStatusMeta = static function ($status) {
  $status = strtolower((string)$status);

  if ($status === 'pending_verification') {
    return ['group' => 'pending', 'label' => 'Pending', 'class' => 'chip-pending'];
  }

  if (in_array($status, ['open', 'approved', 'accepted'], true)) {
    return ['group' => 'approved', 'label' => 'Approved', 'class' => 'chip-approved'];
  }

  if ($status === 'completed') {
    return ['group' => 'completed', 'label' => 'Completed', 'class' => 'chip-completed'];
  }

  if ($status === 'rejected') {
    return ['group' => 'rejected', 'label' => 'Rejected', 'class' => 'chip-rejected'];
  }

  return ['group' => 'other', 'label' => ucfirst($status), 'class' => 'chip-other'];
};
?>




<link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section">
      <div class="dashboard-hero">
        <div>
          <h2 class="card-title" class="card-title-tight">Aid Request Dashboard</h2>
          <p class="dashboard-kicker">Review, filter, and process requests from one place.</p>
        </div>
      </div>

      <?php if (!empty($flashMessage)): ?>
        <div class="alert alert-info" class="alert-block">
          <?= esc($flashMessage) ?>
        </div>
      <?php endif; ?>

      <div class="dashboard-tools">
        <div class="search-wrap">
          <p class="search-title">Quick Search</p>
          <div class="search-row">
            <input type="text" id="requestSearchInput" placeholder="Request ID or Student Name" autocomplete="off">
            <button type="button" class="btn btn-outline btn-sm" id="clearRequestSearch">Clear</button>
          </div>
        </div>
      </div>

      <p class="search-meta" id="searchMetaText">Search by student name.</p>

      <?php if (empty($allRequests)): ?>
        <div class="request-card" class="req-card-mt">
          <div class="request-details">
            <p class="detail-value">No aid requests available right now.</p>
          </div>
        </div>
      <?php else: ?>
        <div class="req-grid" id="requestGrid">
          <?php foreach ($allRequests as $request): ?>
            <?php $statusMeta = $getStatusMeta($request->status ?? ''); ?>
            <?php $studentName = (string)($request->student_name ?? 'Student'); ?>
            <div
              class="req-card"
              data-request-id="<?= (int)$request->request_id ?>"
              data-student-name="<?= esc(strtolower($studentName)) ?>"
              data-status-group="<?= esc($statusMeta['group']) ?>"
            >
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($studentName) ?></p>
                  <p class="summary-meta">Reg: <?= esc($request->student_id ?? 'N/A') ?> · <?= esc($request->faculty_name ?? 'Faculty N/A') ?></p>
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
                  <span class="chip-state <?= esc($statusMeta['class']) ?>"><?= esc($statusMeta['label']) ?></span>
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
                    <h3 class="review-modal-title" id="review-title-<?= (int)$request->request_id ?>"><?= esc($studentName) ?> · Aid Details</h3>
                    <p class="review-modal-meta">Status: <?= esc($statusMeta['label']) ?></p>
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

                <?php if ($statusMeta['group'] === 'rejected'): ?>
                  <div class="req-row"><span class="req-label">Rejection Note</span><strong><?= esc($request->rejection_reason ?? 'No note provided') ?></strong></div>
                <?php endif; ?>

                <?php if (!empty($request->alumnus_user_id)): ?>
                  <div class="req-row"><span class="req-label">Accepted By</span><strong><?= esc($request->alumnus_name ?? ('Alumni User #' . (int)$request->alumnus_user_id)) ?></strong></div>
                  <div class="req-row"><span class="req-label">Alumni Email</span><strong><?= esc($request->alumnus_email ?? 'N/A') ?></strong></div>
                <?php endif; ?>

                <?php if ($statusMeta['group'] === 'pending'): ?>
                  <div class="action-wrap">
                    <form method="POST" class="toolbar-inline">
                      <input type="hidden" name="action" value="approve">
                      <input type="hidden" name="request_id" value="<?= (int)$request->request_id ?>">
                      <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> <span>Approve</span></button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm open-reject-panel" data-target="reject-panel-<?= (int)$request->request_id ?>">
                      <i class="fas fa-times"></i>
                      <span>Reject</span>
                    </button>
                  </div>

                  <div id="reject-panel-<?= (int)$request->request_id ?>" class="reject-panel hidden">
                    <form method="POST" class="reject-form">
                      <input type="hidden" name="action" value="reject">
                      <input type="hidden" name="request_id" value="<?= (int)$request->request_id ?>">
                      <textarea name="note" rows="2" required placeholder="Rejection note (required)"></textarea>
                      <div class="reject-actions">
                        <button type="button" class="btn btn-outline btn-sm close-reject-panel" data-target="reject-panel-<?= (int)$request->request_id ?>">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> <span>Confirm Reject</span></button>
                      </div>
                    </form>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="empty-filter-state" id="emptyFilterState">
          No requests match the current search/filter.
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

    const openRejectButtons = document.querySelectorAll('.open-reject-panel');
    const closeRejectButtons = document.querySelectorAll('.close-reject-panel');

    openRejectButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const panelId = btn.getAttribute('data-target');
        const panel = document.getElementById(panelId);
        if (!panel) return;
        panel.classList.remove('hidden');
        const textarea = panel.querySelector('textarea[name="note"]');
        if (textarea) textarea.focus();
      });
    });

    closeRejectButtons.forEach((btn) => {
      btn.addEventListener('click', function () {
        const panelId = btn.getAttribute('data-target');
        const panel = document.getElementById(panelId);
        if (!panel) return;
        panel.classList.add('hidden');
      });
    });

    const searchInput = document.getElementById('requestSearchInput');
    const clearSearchButton = document.getElementById('clearRequestSearch');
    const searchMetaText = document.getElementById('searchMetaText');
    const emptyFilterState = document.getElementById('emptyFilterState');
    const requestCards = Array.from(document.querySelectorAll('.req-card'));

    const applyFilters = function () {
      const rawTerm = searchInput ? (searchInput.value || '').trim() : '';
      const normalizedTerm = rawTerm.toLowerCase();
      let visibleCount = 0;

      requestCards.forEach((card) => {
        const studentName = (card.getAttribute('data-student-name') || '').toLowerCase();
        const matchesSearch = normalizedTerm === '' || studentName.includes(normalizedTerm);
        const isVisible = matchesSearch;

        card.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleCount += 1;
      });

      if (searchMetaText) {
        searchMetaText.textContent = normalizedTerm === ''
          ? 'Search by student name.'
          : ('Showing ' + visibleCount + ' request(s) for "' + rawTerm + '".');
      }

      if (emptyFilterState) {
        emptyFilterState.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    };

    if (searchInput) {
      searchInput.addEventListener('input', applyFilters);
    }

    if (clearSearchButton && searchInput) {
      clearSearchButton.addEventListener('click', function () {
        searchInput.value = '';
        applyFilters();
        searchInput.focus();
      });
    }

    applyFilters();
  })();
</script>

</body>
</html>
