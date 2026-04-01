<?php 
$page_title = "Rejected Requests";
$page_subtitle = "Counselor rejected submissions";
require '../app/views/partials/counselor_header.php'; 

$rejectedRequests = $rejectedRequests ?? [];
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
  .chip-no { background:#ef4444; color:#fff; border-radius:999px; padding:.24rem .62rem; font-size:.74rem; font-weight:600; }
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
              <div class="req-head">
                <div>
                  <h3 class="req-id">#REQ-<?= (int)$request->request_id ?></h3>
                  <div class="req-meta"><?= esc($request->student_name ?? 'Student') ?> · ID: <?= esc($request->student_id ?? 'N/A') ?></div>
                </div>
                <span class="chip-no">Rejected</span>
              </div>
              <div class="req-row"><span class="req-label">Aid Type</span><strong><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></strong></div>
              <div class="req-row"><span class="req-label">Amount</span><strong><?= isset($request->amount) && $request->amount !== null ? 'LKR ' . esc($request->amount) : 'N/A' ?></strong></div>
              <div class="req-row"><span class="req-label">Submitted</span><strong><?= esc($request->created_at ?? 'N/A') ?></strong></div>
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
