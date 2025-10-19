<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Fundraiser Moderation Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Fundraiser Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $fundraiserData['stats']['total_fundraisers'] ?></span>
                        <span class="stat-label">Total Fundraisers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $fundraiserData['stats']['reported_fundraisers'] ?></span>
                        <span class="stat-label">Reported</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">$<?= number_format($fundraiserData['stats']['total_amount_raised']) ?></span>
                        <span class="stat-label">Total Raised</span>
                    </div>
                </div>
            </div>
            
            <!-- Reported Fundraisers Section -->
            <div class="reported-fundraisers-section">
                <h3 class="subsection-title">Reported Fundraisers</h3>
                <div class="reported-fundraisers-container">
                    <?php foreach ($fundraiserData['reported_fundraisers'] as $fundraiser): ?>
                    <div class="reported-fundraiser-card">
                        <div class="fundraiser-header">
                            <div class="fundraiser-info">
                                <h4 class="fundraiser-title"><?= esc($fundraiser['title']) ?></h4>
                                <p class="fundraiser-organizer">By: <?= esc($fundraiser['organizer']) ?> (<?= esc($fundraiser['organizer_email']) ?>)</p>
                            </div>
                            <div class="fundraiser-meta">
                                <span class="report-reason"><?= esc($fundraiser['report_reason']) ?></span>
                                <span class="status-badge status-pending">PENDING</span>
                            </div>
                        </div>
                        
                        <div class="fundraiser-content">
                            <p><strong>Description:</strong> <?= esc($fundraiser['description']) ?></p>
                        </div>
                        
                        <div class="fundraiser-progress">
                            <div class="progress-info">
                                <span class="current-amount">$<?= number_format($fundraiser['current_amount']) ?></span>
                                <span class="target-amount">of $<?= number_format($fundraiser['target_amount']) ?></span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= ($fundraiser['current_amount'] / $fundraiser['target_amount']) * 100 ?>%"></div>
                            </div>
                            <div class="progress-stats">
                                <span class="stat"><i class="fas fa-users"></i> <?= $fundraiser['donors'] ?> donors</span>
                            </div>
                        </div>
                        
                        <div class="report-details">
                            <p><strong>Reported by:</strong> <?= esc($fundraiser['reported_by']) ?></p>
                            <p><strong>Reported on:</strong> <?= date('M j, Y', strtotime($fundraiser['reported_date'])) ?></p>
                        </div>
                        
                        <div class="fundraiser-actions">
                            <button class="btn btn-success btn-sm approve-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-check"></i>
                                Approve Fundraiser
                            </button>
                            <button class="btn btn-warning btn-sm pause-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-pause"></i>
                                Pause Fundraiser
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-trash"></i>
                                Delete Fundraiser
                            </button>
                            <button class="btn btn-outline btn-sm view-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Fundraisers Section -->
            <div class="recent-fundraisers-section">
                <div class="section-header-with-button">
                    <h3 class="subsection-title">Recent Fundraisers</h3>
                    <button class="btn btn-primary create-fundraiser-btn" onclick="openCreateFundraiserModal()">
                        <i class="fas fa-plus"></i>
                        Create New Fundraiser
                    </button>
                </div>
                <div class="recent-fundraisers-container">
                    <?php foreach ($fundraiserData['recent_fundraisers'] as $fundraiser): ?>
                    <div class="recent-fundraiser-card">
                        <div class="fundraiser-header">
                            <h4 class="fundraiser-title"><?= esc($fundraiser['title']) ?></h4>
                            <div class="fundraiser-meta">
                                <span class="fundraiser-organizer"><?= esc($fundraiser['organizer']) ?></span>
                                <span class="fundraiser-date"><?= date('M j, Y', strtotime($fundraiser['start_date'])) ?></span>
                            </div>
                        </div>
                        
                        <div class="fundraiser-content">
                            <p><strong>Description:</strong> <?= esc($fundraiser['description']) ?></p>
                        </div>
                        
                        <div class="fundraiser-progress">
                            <div class="progress-info">
                                <span class="current-amount">$<?= number_format($fundraiser['current_amount']) ?></span>
                                <span class="target-amount">of $<?= number_format($fundraiser['target_amount']) ?></span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= ($fundraiser['current_amount'] / $fundraiser['target_amount']) * 100 ?>%"></div>
                            </div>
                            <div class="progress-stats">
                                <span class="stat"><i class="fas fa-users"></i> <?= $fundraiser['donors'] ?> donors</span>
                                <span class="stat"><i class="fas fa-check-circle"></i> <?= ucfirst($fundraiser['status']) ?></span>
                            </div>
                        </div>
                        
                        <div class="fundraiser-actions">
                            <button class="btn btn-outline btn-sm view-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                            <button class="btn btn-outline btn-sm moderate-btn" data-fundraiser-id="<?= $fundraiser['id'] ?>">
                                <i class="fas fa-gavel"></i>
                                Moderate
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Create Fundraiser Modal -->
<div id="createFundraiserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create New Fundraiser</h2>
            <button class="modal-close" onclick="closeCreateFundraiserModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="fundraiser-form" id="fundraiserForm">
            <div class="form-group">
                <label for="fundraiserTitle">Fundraiser Title *</label>
                <input type="text" id="fundraiserTitle" name="fundraiserTitle" placeholder="Enter fundraiser title" required>
            </div>

            <div class="form-group">
                <label for="fundraiserCategory">Category *</label>
                <select id="fundraiserCategory" name="fundraiserCategory" required>
                    <option value="">Select a category</option>
                    <option value="emergency">Emergency Aid</option>
                    <option value="education">Education</option>
                    <option value="community">Community</option>
                    <option value="research">Research</option>
                    <option value="sports">Sports</option>
                    <option value="arts">Arts & Culture</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fundraiserGoal">Fundraising Goal *</label>
                <input type="number" id="fundraiserGoal" name="fundraiserGoal" placeholder="Enter amount in USD" min="100" required>
            </div>

            <div class="form-group">
                <label for="fundraiserDescription">Description *</label>
                <textarea id="fundraiserDescription" name="fundraiserDescription" rows="4" placeholder="Describe your fundraiser and how funds will be used..." required></textarea>
            </div>

            <div class="form-group">
                <label for="fundraiserDeadline">Campaign Deadline</label>
                <input type="date" id="fundraiserDeadline" name="fundraiserDeadline">
            </div>

            <div class="form-group">
                <label for="fundraiserOrganizer">Organizer Name *</label>
                <input type="text" id="fundraiserOrganizer" name="fundraiserOrganizer" placeholder="Enter organizer name" required>
            </div>

            <div class="form-group">
                <label for="fundraiserEmail">Organizer Email *</label>
                <input type="email" id="fundraiserEmail" name="fundraiserEmail" placeholder="Enter organizer email" required>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline btn-md" onclick="closeCreateFundraiserModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary btn-md">
                    <i class="fas fa-rocket"></i>
                    <span>Create Fundraiser</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.reported-fundraisers-section, .recent-fundraisers-section {
    margin-bottom: 2rem;
}

.subsection-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
}

.reported-fundraisers-container, .recent-fundraisers-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reported-fundraiser-card, .recent-fundraiser-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.reported-fundraiser-card:hover, .recent-fundraiser-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.fundraiser-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.fundraiser-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.fundraiser-organizer {
    margin: 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.fundraiser-meta {
    text-align: right;
}

.report-reason {
    display: block;
    background-color: #FEE2E2;
    color: #DC2626;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    background-color: #FEF3C7;
    color: #D97706;
}

.fundraiser-content {
    margin-bottom: 1rem;
}

.fundraiser-content p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.fundraiser-progress {
    margin-bottom: 1rem;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.current-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #059669;
}

.target-amount {
    color: #6B7280;
    font-size: 0.9rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background-color: #E5E7EB;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background-color: #10B981;
    transition: width 0.3s ease;
}

.progress-stats {
    display: flex;
    gap: 1rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #6B7280;
    font-size: 0.875rem;
}

.stat i {
    color: #0E2072;
}

.report-details {
    background-color: #F9FAFB;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.report-details p {
    margin: 0 0 0.25rem 0;
    color: #374151;
    font-size: 0.9rem;
}

.fundraiser-actions {
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

.btn-warning {
    background-color: #F59E0B;
    color: white;
}

.btn-warning:hover {
    background-color: #D97706;
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

/* Section Header with Button */
.section-header-with-button {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.create-fundraiser-btn {
    background-color: #000000;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.create-fundraiser-btn:hover {
    background-color: #333333;
    transform: translateY(-1px);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #E5E7EB;
    background-color: #F9FAFB;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6B7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background-color: #E5E7EB;
    color: #374151;
}

.fundraiser-form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #0E2072;
    box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #E5E7EB;
}

.btn-md {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fundraiserId = this.getAttribute('data-fundraiser-id');
            if (confirm('Are you sure you want to approve this fundraiser?')) {
                alert('Fundraiser approved successfully!');
                this.closest('.reported-fundraiser-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle pause button clicks
    document.querySelectorAll('.pause-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fundraiserId = this.getAttribute('data-fundraiser-id');
            if (confirm('Are you sure you want to pause this fundraiser?')) {
                alert('Fundraiser paused successfully!');
                this.closest('.reported-fundraiser-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fundraiserId = this.getAttribute('data-fundraiser-id');
            if (confirm('Are you sure you want to delete this fundraiser? This action cannot be undone.')) {
                alert('Fundraiser deleted successfully!');
                this.closest('.reported-fundraiser-card').remove();
            }
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fundraiserId = this.getAttribute('data-fundraiser-id');
            alert('View details functionality would be implemented here for fundraiser ID: ' + fundraiserId);
        });
    });

    // Handle moderate button clicks
    document.querySelectorAll('.moderate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fundraiserId = this.getAttribute('data-fundraiser-id');
            alert('Moderate fundraiser functionality would be implemented here for fundraiser ID: ' + fundraiserId);
        });
    });
});

// Modal Functions
function openCreateFundraiserModal() {
    document.getElementById('createFundraiserModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeCreateFundraiserModal() {
    document.getElementById('createFundraiserModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('fundraiserForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createFundraiserModal');
    if (event.target === modal) {
        closeCreateFundraiserModal();
    }
}

// Handle form submission
document.getElementById('fundraiserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const fundraiserData = {
        title: formData.get('fundraiserTitle'),
        category: formData.get('fundraiserCategory'),
        goal: formData.get('fundraiserGoal'),
        description: formData.get('fundraiserDescription'),
        deadline: formData.get('fundraiserDeadline'),
        organizer: formData.get('fundraiserOrganizer'),
        email: formData.get('fundraiserEmail')
    };
    
    // Here you would typically send the data to the server
    console.log('Fundraiser Data:', fundraiserData);
    
    // Show success message
    alert('Fundraiser created successfully!');
    
    // Close modal
    closeCreateFundraiserModal();
    
    // Here you would typically refresh the fundraisers list
    // or add the new fundraiser to the page dynamically
});
</script>
