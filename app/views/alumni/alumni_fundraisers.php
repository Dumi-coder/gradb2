<?php require '../app/views/partials/alumni_header.php'; ?>

<?php
// Ensure placeholders exist if controller isn't providing them (for direct view testing)
$current_page = isset($current_page) ? $current_page : 'fundraisers';
$activeFundraisers = isset($activeFundraisers) ? $activeFundraisers : [
    [
        'title' => 'Student Emergency Fund',
        'created_by' => 'Jane Doe',
        'goal' => 10000,
        'collected' => 6500,
        'status' => 'Active',
    ],
    [
        'title' => 'Research Equipment Fund',
        'created_by' => 'Alumni Research Group',
        'goal' => 20000,
        'collected' => 8000,
        'status' => 'Active',
    ],
];
$completedFundraisers = isset($completedFundraisers) ? $completedFundraisers : [
    [
        'title' => 'Library Modernization',
        'goal' => 15000,
        'raised' => 15000,
        'completed_at' => '2025-06-15',
        'proof_url' => '#',
        'status' => 'Completed',
    ],
    [
        'title' => 'Scholarship Support 2024',
        'goal' => 25000,
        'raised' => 26000,
        'completed_at' => '2024-12-20',
        'proof_url' => '#',
        'status' => 'Completed',
    ],
];
$donationHistory = isset($donationHistory) ? $donationHistory : [
    [
        'fundraiser_title' => 'Student Emergency Fund',
        'amount' => 250.00,
        'date' => '2025-08-15',
        'status' => 'Completed',
    ],
    [
        'fundraiser_title' => 'Library Modernization',
        'amount' => 500.00,
        'date' => '2025-06-10',
        'status' => 'Completed',
    ],
];
$totalDonated = isset($totalDonated) ? $totalDonated : array_sum(array_column($donationHistory, 'amount'));
$totalContributions = isset($totalContributions) ? $totalContributions : count($donationHistory);
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

    <main class="main-content">
        <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Fundraisers</h1>
                <p class="page-subtitle">Create and contribute to fundraisers that support students and university initiatives.</p>
            </div>

            <!-- Create Fundraiser Button Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="card">
                    <div class="upload-action-container">
                        <div class="upload-action-content">
                            <div class="upload-action-info">
                                <h3>Start a Fundraiser</h3>
                                <p>Create a new fundraiser to support students, university initiatives, or community projects.</p>
                            </div>
                            <div class="upload-action-button">
                                <button id="createFundraiserBtn" class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">
                                    <i class="fas fa-plus"></i>
                                    Create Fundraiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Create Fundraiser Form (hidden by default) -->
            <section class="dashboard-section" id="create-fundraiser-section" style="display:none;">
                <div class="card">
                    <h2 class="section-title">Create New Fundraiser</h2>
                    <form id="createFundraiserForm" class="form-grid" method="post" action="#" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" placeholder="Enter fundraiser title" required />
                        </div>
                        <div class="form-group full">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4" placeholder="Describe the purpose and details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="goal">Goal Amount</label>
                            <input type="number" id="goal" name="goal" min="1" placeholder="e.g., 10000" required />
                        </div>
                        <div class="form-group">
                            <label for="proof">Upload Proof Document/Image (optional)</label>
                            <input type="file" id="proof" name="proof" accept="image/*,.pdf" />
                        </div>
                        <div class="form-actions full">
                            <button type="submit" class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Submit</button>
                            <button type="button" id="cancelCreateBtn" class="btn btn-text" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important; text-decoration: none !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white'; this.style.textDecoration='none';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000'; this.style.textDecoration='none';">Cancel</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Active Fundraisers -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Active Fundraisers</h2>
                </div>
                <div class="mentorship-grid">
                    <?php if (!empty($activeFundraisers)): ?>
                        <?php foreach ($activeFundraisers as $f): 
                            $goal = (float)$f['goal'];
                            $collected = (float)$f['collected'];
                            $pct = $goal > 0 ? min(100, round(($collected / $goal) * 100)) : 0;
                            $isUrgent = isset($f['priority']) && $f['priority'] === 'urgent';
                        ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?= htmlspecialchars($f['title']) ?></h3>
                                        <p class="request-type">Created by <?= htmlspecialchars($f['created_by']) ?></p>
                                    </div>
                                    <span class="card-badge <?= $isUrgent ? 'urgent' : 'active' ?>">
                                        <?= $isUrgent ? 'Urgent' : 'Active' ?>
                                    </span>
                                </div>
                                <div class="card-content">
                                    <div class="progress-section">
                                        <div class="progress-bar <?= $isUrgent ? 'urgent' : 'green' ?>">
                                            <div class="progress-fill" style="width: <?= $pct ?>%"></div>
                                        </div>
                                        <div class="progress-stats">
                                            <span class="raised">$<?= number_format($collected, 2) ?> raised</span>
                                            <span class="goal">of $<?= number_format($goal, 2) ?> goal</span>
                                        </div>
                                    </div>
                                    <div class="aid-details">
                                        <div class="aid-amount">Progress: <strong><?= $pct ?>%</strong></div>
                                        <div class="aid-type">Goal: <strong>$<?= number_format($goal, 2) ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Donate</button>
                                    <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Details</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No active fundraisers available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Completed Fundraisers -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Completed Fundraisers</h2>
                </div>
                <div class="mentorship-grid">
                    <?php if (!empty($completedFundraisers)): ?>
                        <?php foreach ($completedFundraisers as $f): 
                            $goal = (float)$f['goal'];
                            $raised = (float)$f['raised'];
                            $pct = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                        ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?= htmlspecialchars($f['title']) ?></h3>
                                        <p class="request-type">Completed on <?= htmlspecialchars($f['completed_at']) ?></p>
                                    </div>
                                    <span class="card-badge active">Completed</span>
                                </div>
                                <div class="card-content">
                                    <div class="progress-section">
                                        <div class="progress-bar completed">
                                            <div class="progress-fill" style="width: <?= $pct ?>%"></div>
                                        </div>
                                        <div class="progress-stats">
                                            <span class="raised">$<?= number_format($raised, 2) ?> raised</span>
                                            <span class="goal">of $<?= number_format($goal, 2) ?> goal</span>
                                        </div>
                                    </div>
                                    <div class="aid-details">
                                        <div class="aid-amount">Achievement: <strong><?= $pct ?>%</strong></div>
                                        <div class="aid-type">Goal: <strong>$<?= number_format($goal, 2) ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">View Details</button>
                                    <a href="<?= htmlspecialchars($f['proof_url']) ?>" class="btn btn-text" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Proof</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- View All Link -->
                        <div style="text-align: center; margin-top: 1rem; grid-column: 1 / -1;">
                            <a href="#" class="btn btn-text">View All Completed Fundraisers</a>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No completed fundraisers yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- My Donation History -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">My Donation History</h2>
                    <a href="#" class="btn btn-text">View All</a>
                </div>
                
                <!-- Donation Summary -->
                <div class="donation-summary card">
                    <div class="summary-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Donated:</span>
                            <span class="stat-value">$<?= number_format($totalDonated ?? 0, 2) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Number of Contributions:</span>
                            <span class="stat-value"><?= $totalContributions ?? 0 ?></span>
                        </div>
                    </div>
                </div>

                <!-- Donation History Cards -->
                <div class="mentorship-grid">
                    <?php if (!empty($donationHistory)): ?>
                        <?php foreach ($donationHistory as $donation): ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?= htmlspecialchars($donation['fundraiser_title']) ?></h3>
                                        <p class="request-type">Donated on <?= htmlspecialchars($donation['date']) ?></p>
                                    </div>
                                    <span class="card-badge <?= strtolower($donation['status']) === 'completed' ? 'active' : (strtolower($donation['status']) === 'pending' ? 'pending' : 'urgent') ?>">
                                        <?= htmlspecialchars($donation['status']) ?>
                                    </span>
                                </div>
                                <div class="card-content">
                                    <div class="aid-details">
                                        <div class="aid-amount">Amount Donated: <strong>$<?= number_format((float)$donation['amount'], 2) ?></strong></div>
                                        <div class="aid-type">Status: <strong><?= htmlspecialchars($donation['status']) ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">View Details</button>
                                    <a href="#" class="btn btn-text" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#000000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">View Proof</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No donation history available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
</div>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_mentorship.css">
<style>
.card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.header-actions { margin-top: 8px; }

/* Upload Action Section */
.upload-action-container {
    padding: 1.5rem;
}

.upload-action-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.upload-action-info h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.upload-action-info p {
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

.upload-action-button .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    white-space: nowrap;
}

.upload-action-button .btn i {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .upload-action-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .upload-action-button {
        width: 100%;
    }
    
    .upload-action-button .btn {
        width: 100%;
        justify-content: center;
    }
}
.header-actions .btn-primary { float: right; }
.table { overflow-x: auto; }
.fundraisers-table { width: 100%; border-collapse: collapse; }
.fundraisers-table th, .fundraisers-table td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
.fundraisers-table thead th { background: #fafafa; font-weight: 600; font-size: 14px; }
.fundraisers-table tbody tr.completed { color: #6b7280; background: #f9fafb; }
.fundraisers-table .link { color: #2563eb; text-decoration: none; }

/* Add spacing between sections */
.dashboard-section + .dashboard-section { margin-top: 32px; }

.progress-wrapper { min-width: 220px; }
.progress-bar { height: 8px; background: #e5e7eb; border-radius: 9999px; overflow: hidden; }
.progress-bar .progress-fill { height: 100%; background: #10b981; }
.progress-bar.green .progress-fill { background: #10b981; }
.progress-bar.urgent .progress-fill { background: #ef4444; }
.progress-stats { font-size: 12px; color: #374151; margin-top: 6px; }

/* Urgent fundraiser styling */
.urgent-row { background: #fef2f2; border-left: 4px solid #ef4444; }
.urgent-badge { 
  background: #ef4444; 
  color: white; 
  padding: 2px 6px; 
  border-radius: 4px; 
  font-size: 10px; 
  font-weight: 700; 
  margin-left: 8px; 
}

.status { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
.status.active { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.status.urgent { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.status.completed { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

/* Card-specific styles for fundraisers */
.progress-section {
  margin: 16px 0;
}

.progress-section .progress-bar {
  height: 12px;
  margin-bottom: 8px;
}

.progress-section .progress-stats {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  color: #374151;
}

.progress-bar.completed .progress-fill {
  background: #6b7280;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
  font-style: italic;
  grid-column: 1 / -1;
}

/* Ensure proper responsive grid behavior */
@media (max-width: 1200px) {
  .mentorship-grid {
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  }
}

@media (max-width: 900px) {
  .mentorship-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  }
}

@media (max-width: 600px) {
  .mentorship-grid {
    grid-template-columns: 1fr;
  }
  
  .mentorship-card {
    margin: 0;
  }
}

/* Donation History Styles */
.donation-summary { margin-bottom: 16px; }
.summary-stats { 
  display: flex; 
  gap: 32px; 
  align-items: center; 
}
.stat-item { 
  display: flex; 
  flex-direction: column; 
  text-align: center; 
}
.stat-label { 
  font-size: 14px; 
  color: #6b7280; 
  margin-bottom: 4px; 
}
.stat-value { 
  font-size: 24px; 
  font-weight: 700; 
  color: #1f2937; 
}

.amount-donated { 
  font-weight: 700; 
  color: #059669; 
  font-size: 16px; 
}

.fundraiser-link { 
  color: #2563eb; 
  text-decoration: none; 
  font-weight: 500; 
}
.fundraiser-link:hover { 
  text-decoration: underline; 
}

.donation-status { 
  padding: 4px 10px; 
  border-radius: 999px; 
  font-size: 12px; 
  font-weight: 600; 
}
.donation-status.completed { 
  background: #ecfdf5; 
  color: #065f46; 
  border: 1px solid #a7f3d0; 
}
.donation-status.pending { 
  background: #fef3c7; 
  color: #92400e; 
  border: 1px solid #fcd34d; 
}
.donation-status.refunded { 
  background: #fee2e2; 
  color: #991b1b; 
  border: 1px solid #fca5a5; 
}

@media (max-width: 768px) {
  .summary-stats { 
    flex-direction: column; 
    gap: 16px; 
  }
}

.form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.form-grid .form-group { display: flex; flex-direction: column; }
.form-grid .form-group.full { grid-column: span 2 / span 2; }
.form-grid label { font-weight: 600; margin-bottom: 6px; }
.form-grid input[type="text"],
.form-grid input[type="number"],
.form-grid input[type="file"],
.form-grid textarea { padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
.form-actions { display: flex; gap: 12px; }

@media (max-width: 900px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-grid .form-group.full { grid-column: span 1 / span 1; }
}
</style>

<script src="<?=ROOT?>/assets/js/dashboard.js"></script>
<script>
// Ensure sidebar toggle works on this page and icons remain visible
document.addEventListener('DOMContentLoaded', function() {
    // Add sidebar toggle functionality if not already initialized
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    // Function to ensure icons are visible
    function ensureIconsVisible() {
        const navIcons = document.querySelectorAll('.nav-icon');
        navIcons.forEach(icon => {
            icon.style.display = 'block';
            icon.style.visibility = 'visible';
            icon.style.opacity = '1';
        });
    }
    
    // Call on page load
    ensureIconsVisible();
    
    // Handle manual sidebar toggle - DISABLED
    if (sidebarToggle && sidebar && !sidebarToggle.hasAttribute('data-initialized')) {
        sidebarToggle.setAttribute('data-initialized', 'true');
        sidebarToggle.addEventListener('click', function() {
            // DISABLED: Sidebar toggle functionality removed
            // sidebar.classList.toggle('collapsed');
            // The sidebar and content now stay fixed - no toggling
            console.log('Sidebar toggle disabled - layout stays fixed');
            
            // Ensure icons remain visible
            setTimeout(ensureIconsVisible, 100);
        });
    }
    
    
    // Observe DOM changes to ensure icons stay visible
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                ensureIconsVisible();
            }
        });
    });
    
    if (sidebar) {
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
});

// Toggle create fundraiser form visibility
const createBtn = document.getElementById('createFundraiserBtn');
const cancelBtn = document.getElementById('cancelCreateBtn');
const createSection = document.getElementById('create-fundraiser-section');

if (createBtn) {
  createBtn.addEventListener('click', () => {
    createSection.style.display = createSection.style.display === 'none' ? 'block' : 'none';
  });
}

if (cancelBtn) {
  cancelBtn.addEventListener('click', () => {
    createSection.style.display = 'none';
  });
}
</script>
