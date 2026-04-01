<?php 
$page_title = "Pending Requests";
$page_subtitle = "Review pending aid requests";
require '../app/views/partials/counselor_header.php'; 

$pendingRequests = $pendingRequests ?? [];
?>

<style>
  .req-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(320px,1fr)); gap: 16px; }
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
  .req-id { font-size:1.05rem; margin:0; letter-spacing:.2px; }
  .req-meta { color:var(--muted-foreground,#6b7280); font-size:.86rem; margin-top:4px; }
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
          <a href="<?=ROOT?>/counselor/approved-requests" class="btn btn-outline btn-sm">Accepted</a>
          <a href="<?=ROOT?>/counselor/rejected-requests" class="btn btn-outline btn-sm">Rejected</a>
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
                  <h3 class="req-id">#REQ-<?= (int)$request->request_id ?></h3>
                  <div class="req-meta"><?= esc($request->student_name ?? 'Student') ?> · ID: <?= esc($request->student_id ?? 'N/A') ?></div>
                </div>
                <span class="chip-pending">Pending</span>
              </div>

              <div class="req-row"><span class="req-label">Email</span><strong><?= esc($request->student_email ?? 'N/A') ?></strong></div>
              <div class="req-row"><span class="req-label">Mobile</span><strong><?= esc($request->mobile_number ?? 'N/A') ?></strong></div>
              <div class="req-row"><span class="req-label">Faculty</span><strong><?= esc($request->faculty_name ?? 'N/A') ?></strong></div>
              <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></strong></div>
              <div class="req-row"><span class="req-label">Amount</span><strong><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></strong></div>
              <div class="req-row"><span class="req-label">Reason</span><strong><?= esc($request->reason ?? 'N/A') ?></strong></div>

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
