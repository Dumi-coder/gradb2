<?php 
$page_title = "Analytics & Reports";
$page_subtitle = "View insights and generate reports";
require '../app/views/partials/counselor_header.php'; 

$summary = $summary ?? [
  'total_requests' => 0,
  'approval_rate' => 0,
  'total_disbursed' => 0,
  'avg_processing_days' => 0,
];
$breakdown = $breakdown ?? [];
$recentRequests = $recentRequests ?? [];
$days = (int)($days ?? 30);

$statusLabel = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'pending_verification') {
    return 'Pending';
  }
  if (in_array($status, ['open', 'approved', 'accepted'], true)) {
    return 'Accepted';
  }
  if ($status === 'rejected') {
    return 'Rejected';
  }
  return ucfirst($status);
};

$formatAidType = function ($aidType) {
  return ucfirst(str_replace('_', ' ', (string)$aidType));
};
?>

    <div class="dashboard-container">
      <!-- sidebar -->
      <?php require '../app/views/partials/counselor_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Quick Stats Section -->
        <section class="dashboard-section quick-stats-section">
          <div class="section-header">
            <h2 class="card-title">Quick Overview</h2>
            <div class="section-actions">
              <select class="filter-select" id="timeFilter">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last <?= (int)$days ?> Days</option>
                <option value="90">Last 90 Days</option>
              </select>
            </div>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= (int)($summary['total_requests'] ?? 0) ?></h3>
                <p class="stat-label">Total Requests</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= esc((string)($summary['approval_rate'] ?? 0)) ?>%</h3>
                <p class="stat-label">Approval Rate</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">Rs. <?= number_format((float)($summary['total_disbursed'] ?? 0), 2) ?></h3>
                <p class="stat-label">Total Disbursed</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= esc((string)($summary['avg_processing_days'] ?? 0)) ?></h3>
                <p class="stat-label">Avg. Processing Days</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Request Types Breakdown -->
        <section class="dashboard-section breakdown-section">
          <div class="section-header">
            <h2 class="card-title">Request Types Breakdown</h2>
          </div>
          
          <div class="breakdown-grid">
            <?php if (empty($breakdown)): ?>
              <p>No aid request data available for this period.</p>
            <?php else: ?>
              <?php foreach ($breakdown as $item): ?>
                <div class="breakdown-item">
                  <div class="breakdown-header">
                    <h4><?= esc($formatAidType($item['aid_type'] ?? 'other')) ?></h4>
                    <span class="breakdown-count"><?= (int)($item['total_count'] ?? 0) ?></span>
                  </div>
                  <div class="breakdown-bar">
                    <div class="breakdown-fill" style="width: <?= (int)($item['bar_percentage'] ?? 0) ?>%"></div>
                  </div>
                  <div class="breakdown-stats">
                    <span>Approved: <?= (int)($item['approved_count'] ?? 0) ?></span>
                    <span>Rejected: <?= (int)($item['rejected_count'] ?? 0) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

        <!-- Recent Aid Requests -->
        <section class="dashboard-section recent-reports-section">
          <div class="section-header">
            <h2 class="card-title">Recent Aid Requests</h2>
          </div>

          <div class="reports-list">
            <?php if (empty($recentRequests)): ?>
              <p>No recent aid requests found.</p>
            <?php else: ?>
              <?php foreach ($recentRequests as $request): ?>
                <div class="report-item">
                  <div class="report-info">
                    <h4 class="report-title"><?= esc($request->student_name ?? 'Student') ?> · <?= esc($formatAidType($request->aid_type ?? 'aid')) ?></h4>
                    <div class="report-meta">
                      <span class="report-type">Student ID: <?= esc($request->student_id ?? 'N/A') ?></span>
                      <span class="report-date"><?= esc($request->created_at ?? 'N/A') ?></span>
                      <span class="report-size">Amount: <?= isset($request->amount) && $request->amount !== null ? 'Rs. ' . esc($request->amount) : 'N/A' ?></span>
                    </div>
                  </div>
                  <div class="report-actions">
                    <span class="status-badge status-<?= esc(strtolower((string)($request->status ?? 'pending'))) ?>">
                      <?= esc($statusLabel($request->status ?? 'pending')) ?>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

      </main>
    </div>

    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>
