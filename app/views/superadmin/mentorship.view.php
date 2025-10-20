<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Mentorship Requests Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Mentorship Approval Requests</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $mentorshipData['stats']['total_requests'] ?></span>
                        <span class="stat-label">Total Requests</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $mentorshipData['stats']['pending_requests'] ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
            </div>
            
            <div class="mentorship-requests-container">
                <?php foreach ($mentorshipData['requests'] as $request): ?>
                <div class="mentorship-request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <h3 class="student-name"><?= esc($request['student_name']) ?></h3>
                            <p class="student-email"><?= esc($request['student_email']) ?></p>
                            <p class="guidance-type"><?= esc($request['guidance_type']) ?></p>
                        </div>
                        <div class="request-meta">
                            <?php if ($request['urgency'] === 'high'): ?>
                            <span class="status-badge status-urgent">URGENT</span>
                            <?php else: ?>
                            <span class="status-badge status-pending">PENDING</span>
                            <?php endif; ?>
                            <p class="request-date">Requested: <?= date('M j, Y', strtotime($request['requested_date'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="request-details">
                        <div class="mentor-info">
                            <h4>Proposed Mentor:</h4>
                            <p><strong><?= esc($request['mentor_name']) ?></strong></p>
                            <p><?= esc($request['mentor_email']) ?></p>
                        </div>
                        
                        <div class="request-description">
                            <h4>Request Description:</h4>
                            <p><?= esc($request['description']) ?></p>
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
.mentorship-requests-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.mentorship-request-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.mentorship-request-card:hover {
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

.student-email {
    margin: 0 0 0.5rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.guidance-type {
    margin: 0;
    color: #0E2072;
    font-weight: 500;
    font-size: 0.9rem;
}

.request-meta {
    text-align: right;
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

.mentor-info, .request-description {
    margin-bottom: 1rem;
}

.mentor-info h4, .request-description h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.mentor-info p, .request-description p {
    margin: 0 0 0.25rem 0;
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
    font-size: 2rem;
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
            if (confirm('Are you sure you want to approve this mentorship request?')) {
                // Here you would typically send an AJAX request to approve the request
                alert('Mentorship request approved successfully!');
                this.closest('.mentorship-request-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            if (confirm('Are you sure you want to reject this mentorship request?')) {
                // Here you would typically send an AJAX request to reject the request
                alert('Mentorship request rejected.');
                this.closest('.mentorship-request-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            alert('View details functionality would be implemented here for request ID: ' + requestId);
        });
    });
});
</script>
