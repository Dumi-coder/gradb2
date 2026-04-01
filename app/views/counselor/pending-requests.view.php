<?php 
$page_title = "Pending Requests";
$page_subtitle = "Review pending aid requests";
require '../app/views/partials/counselor_header.php'; 

$pendingRequests = $pendingRequests ?? [];
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
    display:grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    align-items: stretch;
  }
  @media (max-width: 1200px) {
    .req-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 768px) {
    .req-grid { grid-template-columns: 1fr; }
  }
  .req-card {
    border:1px solid #e6eaf0;
    border-radius:16px;
    padding:16px;
    background:#ffffff;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    transition: transform .15s ease, box-shadow .15s ease;
  }
  .req-card:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); }
  .req-head { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; margin-bottom:10px; }
  .req-meta { color:var(--muted-foreground,#6b7280); font-size:.86rem; margin-top:4px; }
  .student-meta { display:flex; gap:8px; flex-wrap:wrap; margin-top:6px; }
  .student-chip {
    display:inline-flex;
    align-items:center;
    border:1px solid #e2e8f0;
    background:#f8fafc;
    color:#334155;
    border-radius:999px;
    font-size:.78rem;
    font-weight:600;
    padding:.2rem .55rem;
  }
  .student-chip .k { color:#64748b; font-weight:600; margin-right:4px; }
  .req-row {
    display:grid;
    grid-template-columns: 140px 1fr;
    gap:10px;
    align-items:start;
    background:#f8fafc;
    border-radius:10px;
    padding:9px 10px;
    margin-top:8px;
  }
  .req-label { color:var(--muted-foreground,#6b7280); font-size:.86rem; }
  .req-row strong { font-size:.9rem; text-align:left; word-break: break-word; }
  .chip-pending { background:#f59e0b; color:#fff; border-radius:999px; padding:.24rem .62rem; font-size:.74rem; font-weight:600; }
  .action-wrap { margin-top:12px; display:flex; gap:10px; flex-wrap:wrap; padding-top:4px; }
  .reject-form { flex:1; min-width:260px; }
  .reject-form textarea { width:100%; border:1px solid #d7dde6; border-radius:10px; padding:9px 10px; margin-bottom:8px; resize:vertical; }
</style>

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section">
      <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2 class="card-title">Pending Aid Requests</h2>
        <div style="display:flex; gap:.6rem;">
          <!-- <a href="<?=ROOT?>/counselor/approved-requests" class="btn btn-outline btn-sm">Accepted</a> -->
          <!-- <a href="<?=ROOT?>/counselor/rejected-requests" class="btn btn-outline btn-sm">Rejected</a> -->
        </div>
      </div>

      <?php if (!empty($flashMessage)): ?>
        <div class="alert alert-info" style="margin: 1rem 0;">
          <?= esc($flashMessage) ?>
        </div>
      <?php endif; ?>

      <?php if (empty($pendingRequests)): ?>
        <div class="request-card" style="margin-top:1rem;">
          <div class="request-details">
            <p class="detail-value">No pending requests right now.</p>
          </div>
        </div>
      <?php else: ?>
        <div class="req-grid">
          <?php foreach ($pendingRequests as $request): ?>
            <div class="req-card" data-request-id="<?= (int)$request->request_id ?>">
              <div class="req-head">
                <div>
                  <div class="student-meta">
                    <span class="student-chip"><span class="k">Name:</span><?= esc($request->student_name ?? 'Student') ?></span>
                    <span class="student-chip"><span class="k">ID:</span><?= esc($request->student_id ?? 'N/A') ?></span>
                  </div>
                </div>
                <span class="chip-pending">Pending</span>
              </div>

              <div class="req-row"><span class="req-label">Email</span><strong><?= esc($request->student_email ?? 'N/A') ?></strong></div>
              <div class="req-row"><span class="req-label">Student ID</span><strong><?= esc($request->student_id ?? 'N/A') ?></strong></div>
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

              <div class="action-wrap">
                <form method="POST" style="display:inline-block;">
                  <input type="hidden" name="action" value="approve">
                  <input type="hidden" name="request_id" value="<?= (int)$request->request_id ?>">
                  <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> <span>Approve</span></button>
                </form>

                <form method="POST" class="reject-form">
                  <input type="hidden" name="action" value="reject">
                  <input type="hidden" name="request_id" value="<?= (int)$request->request_id ?>">
                  <textarea name="note" rows="2" required placeholder="Rejection note (required)"></textarea>
                  <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> <span>Reject</span></button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
