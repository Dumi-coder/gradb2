<?php 
$page_title = "Rejected Requests";
$page_subtitle = "Counselor rejected submissions";
require '../app/views/partials/counselor_header.php'; 

$rejectedRequests = $rejectedRequests ?? [];
$buildFileUrl = static function ($path) {
  $path = trim((string)$path);
  if ($path === '') {
    return '';
  }
  return ROOT . '/' . ltrim($path, '/');
};
?>

<style>
  .req-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
  }
  .req-card {
    border: 1px solid #e6eaf0;
    border-radius: 14px;
    padding: 12px 14px;
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
  }
  .req-card:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); border-color: #d6deea; }

  .req-summary {
    display: grid;
    grid-template-columns: minmax(260px, 1.3fr) minmax(130px, .55fr) minmax(170px, .7fr) auto;
    gap: 12px;
    align-items: center;
  }

  .summary-label {
    display: block;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #64748b;
    margin-bottom: 3px;
    font-weight: 600;
  }

  .summary-value {
    margin: 0;
    color: #0f172a;
    font-size: .98rem;
    font-weight: 700;
  }

  .summary-meta {
    margin: 3px 0 0;
    color: #64748b;
    font-size: .82rem;
  }

  .summary-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
  }

  .req-row {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 10px;
    align-items: start;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e8eef5;
    padding: 9px 10px;
    margin-top: 8px;
  }
  .req-label { color: var(--muted-foreground,#6b7280); font-size: .86rem; }
  .req-row strong { font-size: .9rem; text-align: left; word-break: break-word; line-height: 1.45; }
  .chip-no { background:#ef4444; color:#fff; border-radius:999px; padding:.24rem .62rem; font-size:.74rem; font-weight:600; }

  .review-modal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.52);
    backdrop-filter: blur(3px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 12000;
    padding: 16px;
  }

  .review-modal-panel {
    width: min(920px, 100%);
    max-height: 90vh;
    overflow: auto;
    background: #ffffff;
    border: 1px solid #dbe3ee;
    border-radius: 16px;
    padding: 14px;
    box-shadow: 0 20px 48px rgba(2, 6, 23, 0.26);
  }

  .review-modal-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
  }

  .review-modal-title {
    margin: 0;
    font-size: 1.1rem;
    color: #0f172a;
  }

  .review-modal-meta {
    margin: 2px 0 0;
    color: #64748b;
    font-size: .83rem;
  }

  .modal-close-btn {
    border: 1px solid #d1dae6;
    background: #fff;
    color: #334155;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    cursor: pointer;
    font-size: 1rem;
  }

  @media (max-width: 980px) {
    .req-summary {
      grid-template-columns: 1fr 1fr;
      align-items: start;
    }

    .summary-actions {
      justify-content: flex-start;
    }
  }

  @media (max-width: 640px) {
    .req-summary {
      grid-template-columns: 1fr;
      gap: 8px;
    }

    .req-row {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section">
      <div class="section-header">
        <h2 class="card-title">Rejected Submissions</h2>
      </div>

      <?php if (empty($rejectedRequests)): ?>
        <div class="request-card" style="margin-top: 1rem;">
          <div class="request-details">
            <p class="detail-value">No rejected submissions.</p>
          </div>
        </div>
      <?php else: ?>
        <div class="req-grid">
          <?php foreach ($rejectedRequests as $request): ?>
            <div class="req-card">
              <div class="req-summary">
                <div>
                  <span class="summary-label">Student</span>
                  <p class="summary-value"><?= esc($request->student_name ?? 'Student') ?></p>
                  <p class="summary-meta">ID: <?= esc($request->student_id ?? 'N/A') ?> · <?= esc($request->faculty_name ?? 'Faculty N/A') ?></p>
                </div>

                <div>
                  <span class="summary-label">Aid Type</span>
                  <p class="summary-value"><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></p>
                </div>

                <div>
                  <span class="summary-label">Submitted</span>
                  <p class="summary-value" style="font-size:.9rem;"><?= esc($request->created_at ?? 'N/A') ?></p>
                </div>

                <div class="summary-actions">
                  <span class="chip-no">Rejected</span>
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
                    <h3 class="review-modal-title" id="review-title-<?= (int)$request->request_id ?>"><?= esc($request->student_name ?? 'Student') ?> · Aid Details</h3>
                    <p class="review-modal-meta">Request #<?= (int)$request->request_id ?> · Status: Rejected</p>
                  </div>
                  <button type="button" class="modal-close-btn" data-close-modal aria-label="Close review modal">&times;</button>
                </div>

                <div class="req-row"><span class="req-label">Email</span><strong><?= esc($request->student_email ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Mobile</span><strong><?= esc($request->mobile_number ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Faculty</span><strong><?= esc($request->faculty_name ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></strong></div>
                <div class="req-row"><span class="req-label">Amount</span><strong><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></strong></div>
                <div class="req-row"><span class="req-label">Reason</span><strong><?= esc($request->reason ?? 'N/A') ?></strong></div>
                <div class="req-row"><span class="req-label">Rejected Note</span><strong><?= esc($request->rejection_reason ?? 'No note provided') ?></strong></div>
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

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
