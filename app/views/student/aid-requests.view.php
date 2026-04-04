<?php 
$page_title = "Aid Requests";
$page_subtitle = "Track your aid request submissions";
require '../app/views/partials/student_header.php'; 

$requests = $requests ?? [];

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
    return 'status-accepted-stage2';
    }

    if ($status === 'completed') {
        return 'status-approved';
    }

    if ($status === 'rejected') {
        return 'status-rejected';
    }

    return 'status-pending';
};

$pendingCount = 0;
$acceptedCount = 0;
$rejectedCount = 0;

foreach ($requests as $request) {
    $currentStatus = strtolower((string)($request->status ?? ''));
    if ($currentStatus === 'pending_verification') {
        $pendingCount++;
    } elseif (in_array($currentStatus, ['open', 'approved', 'accepted'], true)) {
        $acceptedCount++;
    } elseif ($currentStatus === 'rejected') {
        $rejectedCount++;
    }
}
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-requests.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section new-request-section">
      <div class="request-form-card">
        <div class="form-intro">
          <h3 class="form-title">Need Help?</h3>
          <p class="form-description">Submit a new aid request. It first goes to counselor for manual review. If approved, then it goes to alumni.</p>
        </div>
        <div class="form-actions">
          <a href="<?=ROOT?>/student/aid-req-form">
            <button class="btn btn-primary">
              <i class="fas fa-file-alt"></i>
              <span>Start Application</span>
            </button>
          </a>
        </div>
      </div>
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
            <h3 class="stat-number"><?= (int)$rejectedCount ?></h3>
            <p class="stat-label">Rejected</p>
          </div>
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
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $request): ?>
                <tr>
                  <td><?= esc(ucfirst((string)($request->aid_type ?? 'N/A'))) ?></td>
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
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<script src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
