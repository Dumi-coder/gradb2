<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Faculty Announcements Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Faculty Announcements</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $announcementsData['stats']['total_announcements'] ?></span>
                        <span class="stat-label">Total Announcements</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $announcementsData['stats']['published_announcements'] ?></span>
                        <span class="stat-label">Published</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $announcementsData['stats']['high_priority'] ?></span>
                        <span class="stat-label">High Priority</span>
                    </div>
                </div>
            </div>
            
            <!-- Create New Announcement Button -->
            <div class="create-announcement-section">
                <button class="btn btn-primary create-btn" onclick="openCreateAnnouncementModal()">
                    <i class="fas fa-plus"></i>
                    Create New Announcement
                </button>
            </div>
            
            <!-- Announcements Filters -->
            <div class="announcements-filters">
                <div class="search-box">
                    <input type="text" id="announcement-search" placeholder="Search announcements..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-options">
                    <select id="priority-filter" class="filter-select">
                        <option value="">All Priorities</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                    <select id="status-filter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>
            
            <!-- Announcements List -->
            <div class="announcements-container">
                <?php foreach ($announcementsData['announcements'] as $announcement): ?>
                <div class="announcement-card" data-priority="<?= $announcement['priority'] ?>" data-status="<?= $announcement['status'] ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['author']) ?> (<?= esc($announcement['author_email']) ?>)</p>
                            <p class="announcement-audience">Target: <?= esc($announcement['target_audience']) ?></p>
                        </div>
                        <div class="announcement-meta">
                            <span class="priority-badge priority-<?= $announcement['priority'] ?>"><?= ucfirst($announcement['priority']) ?> Priority</span>
                            <span class="status-badge status-<?= $announcement['status'] ?>"><?= ucfirst($announcement['status']) ?></span>
                        </div>
                    </div>
                    
                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>
                    
                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y', strtotime($announcement['created_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span><strong>Expires:</strong> <?= date('M j, Y', strtotime($announcement['expiry_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <?= $announcement['views'] ?></span>
                        </div>
                    </div>
                    
                    <div class="announcement-actions">
                        <?php if ($announcement['status'] === 'draft'): ?>
                        <button class="btn btn-success btn-sm publish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline btn-sm edit-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<!-- Create Announcement Modal -->
<div id="createAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create New Announcement</h2>
            <button class="modal-close" onclick="closeCreateAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="announcement-form" id="announcementForm">
            <div class="form-group">
                <label for="announcementTitle">Announcement Title *</label>
                <input type="text" id="announcementTitle" name="announcementTitle" placeholder="Enter announcement title" required>
            </div>

            <div class="form-group">
                <label for="announcementContent">Content *</label>
                <textarea id="announcementContent" name="announcementContent" rows="6" placeholder="Enter announcement content..." required></textarea>
            </div>

            <div class="form-group">
                <label for="announcementPriority">Priority *</label>
                <select id="announcementPriority" name="announcementPriority" required>
                    <option value="">Select priority</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>

            <div class="form-group">
                <label for="announcementAudience">Target Audience *</label>
                <select id="announcementAudience" name="announcementAudience" required>
                    <option value="">Select audience</option>
                    <option value="All Faculty">All Faculty</option>
                    <option value="Faculty Admins">Faculty Admins</option>
                    <option value="All Users">All Users</option>
                    <option value="Students Only">Students Only</option>
                    <option value="Alumni Only">Alumni Only</option>
                </select>
            </div>

            <div class="form-group">
                <label for="announcementExpiry">Expiry Date</label>
                <input type="date" id="announcementExpiry" name="announcementExpiry">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline btn-md" onclick="closeCreateAnnouncementModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary btn-md">
                    <i class="fas fa-paper-plane"></i>
                    <span>Publish Announcement</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.create-announcement-section {
    margin-bottom: 2rem;
}

.create-btn {
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

.create-btn:hover {
    background-color: #333333;
    transform: translateY(-1px);
}

.announcements-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #0E2072;
    box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6B7280;
}

.filter-options {
    display: flex;
    gap: 0.75rem;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 0.9rem;
    background-color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #0E2072;
}

.announcements-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.announcement-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.announcement-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.announcement-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.announcement-author {
    margin: 0 0 0.25rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.announcement-audience {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.announcement-meta {
    text-align: right;
}

.priority-badge {
    display: block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.priority-high {
    background-color: #FEE2E2;
    color: #DC2626;
}

.priority-medium {
    background-color: #FEF3C7;
    color: #D97706;
}

.priority-low {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-published {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-draft {
    background-color: #E5E7EB;
    color: #6B7280;
}

.announcement-content {
    margin-bottom: 1rem;
}

.announcement-content p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.announcement-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4B5563;
    font-size: 0.9rem;
}

.detail-item i {
    color: #0E2072;
    width: 16px;
}

.announcement-actions {
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

.announcement-form {
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
    // Handle create announcement button
    document.querySelector('.create-btn').addEventListener('click', function() {
        openCreateAnnouncementModal();
    });

    // Handle publish button clicks
    document.querySelectorAll('.publish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            if (confirm('Are you sure you want to publish this announcement?')) {
                alert('Announcement published successfully!');
                this.closest('.announcement-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle unpublish button clicks
    document.querySelectorAll('.unpublish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            if (confirm('Are you sure you want to unpublish this announcement?')) {
                alert('Announcement unpublished successfully!');
                this.closest('.announcement-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            alert('Edit announcement functionality would be implemented here for announcement ID: ' + announcementId);
        });
    });

    // Handle view button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            alert('View announcement functionality would be implemented here for announcement ID: ' + announcementId);
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                alert('Announcement deleted successfully!');
                this.closest('.announcement-card').remove();
            }
        });
    });

    // Handle search functionality
    document.getElementById('announcement-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const announcementCards = document.querySelectorAll('.announcement-card');
        
        announcementCards.forEach(card => {
            const title = card.querySelector('.announcement-title').textContent.toLowerCase();
            const content = card.querySelector('.announcement-content p').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle priority filter
    document.getElementById('priority-filter').addEventListener('change', function() {
        const selectedPriority = this.value;
        const announcementCards = document.querySelectorAll('.announcement-card');
        
        announcementCards.forEach(card => {
            if (selectedPriority === '' || card.getAttribute('data-priority') === selectedPriority) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const announcementCards = document.querySelectorAll('.announcement-card');
        
        announcementCards.forEach(card => {
            if (selectedStatus === '' || card.getAttribute('data-status') === selectedStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Modal Functions
function openCreateAnnouncementModal() {
    document.getElementById('createAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeCreateAnnouncementModal() {
    document.getElementById('createAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('announcementForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createAnnouncementModal');
    if (event.target === modal) {
        closeCreateAnnouncementModal();
    }
}

// Handle form submission
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const announcementData = {
        title: formData.get('announcementTitle'),
        content: formData.get('announcementContent'),
        priority: formData.get('announcementPriority'),
        audience: formData.get('announcementAudience'),
        expiry: formData.get('announcementExpiry')
    };
    
    // Here you would typically send the data to the server
    console.log('Announcement Data:', announcementData);
    
    // Show success message
    alert('Announcement created successfully!');
    
    // Close modal
    closeCreateAnnouncementModal();
    
    // Here you would typically refresh the announcements list
    // or add the new announcement to the page dynamically
});
</script>
