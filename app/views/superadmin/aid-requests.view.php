<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Aid Requests Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Financial Aid Requests</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $aidRequestsData['stats']['total_requests'] ?></span>
                        <span class="stat-label">Total Requests</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">$<?= number_format($aidRequestsData['stats']['total_amount_requested']) ?></span>
                        <span class="stat-label">Total Amount</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $aidRequestsData['stats']['pending_requests'] ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
            </div>
            
            <div class="aid-requests-container">
                <?php foreach ($aidRequestsData['requests'] as $request): ?>
                <div class="aid-request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                            <p class="student-details"><?= esc($request['student_email']) ?> • ID: <?= esc($request['student_id']) ?></p>
                            <p class="aid-type"><?= esc($request['aid_type']) ?></p>
                        </div>
                        <div class="request-meta">
                            <div class="amount-requested">
                                <span class="amount">$<?= number_format($request['amount_requested']) ?></span>
                            </div>
                            <?php if ($request['urgency'] === 'high'): ?>
                            <span class="status-badge status-urgent">URGENT</span>
                            <?php else: ?>
                            <span class="status-badge status-pending">PENDING</span>
                            <?php endif; ?>
                            <p class="request-date">Submitted: <?= date('M j, Y', strtotime($request['submitted_date'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="request-details">
                        <div class="reason-section">
                            <h4>Reason for Aid:</h4>
                            <p><?= esc($request['reason']) ?></p>
                        </div>
                        
                        <div class="documents-info">
                            <h4>Supporting Documents:</h4>
                            <p><?= $request['supporting_documents'] ?> document(s) attached</p>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn btn-success btn-sm approve-btn" data-request-id="<?= $request['id'] ?>">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        <button class="btn btn-danger btn-sm reject-btn" data-request-id="<?= $request['id'] ?>">
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-request-id="<?= $request['id'] ?>">
                            <i class="fas fa-file-alt"></i>
                            View Documents
                        </button>
                        <button class="btn btn-outline btn-sm details-btn" data-request-id="<?= $request['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<style>
.aid-requests-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.aid-request-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.aid-request-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.request-info h3 {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.25rem;
    font-weight: 600;
}

.student-details {
    margin: 0 0 0.5rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.aid-type {
    margin: 0;
    color: #0E2072;
    font-weight: 500;
    font-size: 0.9rem;
}

.request-meta {
    text-align: right;
}

.amount-requested {
    margin-bottom: 0.5rem;
}

.amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #059669;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.status-urgent {
    background-color: #FEE2E2;
    color: #DC2626;
}

.status-pending {
    background-color: #FEF3C7;
    color: #D97706;
}

.request-date {
    margin: 0;
    color: #6B7280;
    font-size: 0.8rem;
}

.request-details {
    margin-bottom: 1.5rem;
}

.reason-section, .documents-info {
    margin-bottom: 1rem;
}

.reason-section h4, .documents-info h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.reason-section p, .documents-info p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.request-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-success {
    background-color: #10B981;
    color: white;
}

.btn-success:hover {
    background-color: #059669;
}

.btn-danger {
    background-color: #EF4444;
    color: white;
}

.btn-danger:hover {
    background-color: #DC2626;
}

.btn-outline {
    background-color: white;
    color: #000000;
    border: 1px solid #000000;
}

.btn-outline:hover {
    background-color: #000000;
    color: white;
}

.section-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0E2072;
}

.stat-label {
    font-size: 0.875rem;
    color: #6B7280;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            if (confirm('Are you sure you want to approve this aid request?')) {
                alert('Aid request approved successfully!');
                this.closest('.aid-request-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            if (confirm('Are you sure you want to reject this aid request?')) {
                alert('Aid request rejected.');
                this.closest('.aid-request-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle view documents button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            alert('View documents functionality would be implemented here for request ID: ' + requestId);
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            alert('View details functionality would be implemented here for request ID: ' + requestId);
        });
    });
});
</script>
