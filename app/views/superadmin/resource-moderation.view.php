<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Resource Moderation Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Resource Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $resourceData['stats']['total_resources'] ?></span>
                        <span class="stat-label">Total Resources</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $resourceData['stats']['reported_resources'] ?></span>
                        <span class="stat-label">Reported</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= number_format($resourceData['stats']['total_downloads']) ?></span>
                        <span class="stat-label">Total Downloads</span>
                    </div>
                </div>
            </div>
            
            <!-- Reported Resources Section -->
            <div class="reported-resources-section">
                <h3 class="subsection-title">Reported Resources</h3>
                <div class="reported-resources-container">
                    <?php foreach ($resourceData['reported_resources'] as $resource): ?>
                    <div class="reported-resource-card">
                        <div class="resource-header">
                            <div class="resource-info">
                                <h4 class="resource-title"><?= esc($resource['title']) ?></h4>
                                <p class="resource-author">By: <?= esc($resource['author']) ?> (<?= esc($resource['author_email']) ?>)</p>
                                <p class="resource-type"><?= esc($resource['resource_type']) ?> • <?= esc($resource['file_size']) ?></p>
                            </div>
                            <div class="resource-meta">
                                <span class="report-reason"><?= esc($resource['report_reason']) ?></span>
                                <span class="status-badge status-pending">PENDING</span>
                            </div>
                        </div>
                        
                        <div class="resource-content">
                            <p><strong>Description:</strong> <?= esc($resource['description']) ?></p>
                        </div>
                        
                        <div class="resource-stats">
                            <span class="stat"><i class="fas fa-download"></i> <?= $resource['downloads'] ?> downloads</span>
                        </div>
                        
                        <div class="report-details">
                            <p><strong>Reported by:</strong> <?= esc($resource['reported_by']) ?></p>
                            <p><strong>Reported on:</strong> <?= date('M j, Y', strtotime($resource['reported_date'])) ?></p>
                        </div>
                        
                        <div class="resource-actions">
                            <button class="btn btn-success btn-sm approve-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-check"></i>
                                Approve Resource
                            </button>
                            <button class="btn btn-warning btn-sm hide-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-eye-slash"></i>
                                Hide Resource
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-trash"></i>
                                Delete Resource
                            </button>
                            <button class="btn btn-outline btn-sm download-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Resources Section -->
            <div class="recent-resources-section">
                <div class="section-header-with-button">
                    <h3 class="subsection-title">Recent Resources</h3>
                    <button class="btn btn-primary add-resource-btn" onclick="openAddResourceModal()">
                        <i class="fas fa-plus"></i>
                        Add New Resource
                    </button>
                </div>
                <div class="recent-resources-container">
                    <?php foreach ($resourceData['recent_resources'] as $resource): ?>
                    <div class="recent-resource-card">
                        <div class="resource-header">
                            <h4 class="resource-title"><?= esc($resource['title']) ?></h4>
                            <div class="resource-meta">
                                <span class="resource-author"><?= esc($resource['author']) ?></span>
                                <span class="resource-date"><?= date('M j, Y', strtotime($resource['upload_date'])) ?></span>
                            </div>
                        </div>
                        
                        <div class="resource-content">
                            <p><strong>Description:</strong> <?= esc($resource['description']) ?></p>
                            <p><strong>Type:</strong> <?= esc($resource['resource_type']) ?> • <strong>Size:</strong> <?= esc($resource['file_size']) ?></p>
                        </div>
                        
                        <div class="resource-stats">
                            <span class="stat"><i class="fas fa-download"></i> <?= $resource['downloads'] ?> downloads</span>
                            <span class="stat"><i class="fas fa-check-circle"></i> <?= ucfirst($resource['status']) ?></span>
                        </div>
                        
                        <div class="resource-actions">
                            <button class="btn btn-outline btn-sm view-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                            <button class="btn btn-outline btn-sm moderate-btn" data-resource-id="<?= $resource['id'] ?>">
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

<!-- Add Resource Modal -->
<div id="addResourceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Resource</h2>
            <button class="modal-close" onclick="closeAddResourceModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="resource-form" id="resourceForm">
            <div class="form-group">
                <label for="resourceTitle">Resource Title *</label>
                <input type="text" id="resourceTitle" name="resourceTitle" placeholder="Enter resource title" required>
            </div>

            <div class="form-group">
                <label for="resourceCategory">Category *</label>
                <select id="resourceCategory" name="resourceCategory" required>
                    <option value="">Select a category</option>
                    <option value="academic">Academic Materials</option>
                    <option value="career">Career Development</option>
                    <option value="research">Research Tools</option>
                    <option value="software">Software & Tools</option>
                    <option value="templates">Templates & Forms</option>
                    <option value="guides">Guides & Tutorials</option>
                    <option value="databases">Databases & Archives</option>
                    <option value="networking">Networking Resources</option>
                </select>
            </div>

            <div class="form-group">
                <label for="resourceDescription">Description *</label>
                <textarea id="resourceDescription" name="resourceDescription" rows="4" placeholder="Describe the resource and how it can be used..." required></textarea>
            </div>

            <div class="form-group">
                <label for="resourceType">Resource Type *</label>
                <select id="resourceType" name="resourceType" required>
                    <option value="">Select resource type</option>
                    <option value="document">Document (PDF, DOC, etc.)</option>
                    <option value="link">External Link</option>
                    <option value="video">Video</option>
                    <option value="image">Image/Gallery</option>
                    <option value="software">Software/Application</option>
                    <option value="template">Template</option>
                    <option value="guide">Guide/Tutorial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="resourceUrl">Resource URL/Link</label>
                <input type="url" id="resourceUrl" name="resourceUrl" placeholder="Enter resource URL (if applicable)">
            </div>

            <div class="form-group">
                <label for="resourceTags">Tags (Optional)</label>
                <input type="text" id="resourceTags" name="resourceTags" placeholder="Enter tags separated by commas (e.g., research, academic, tools)">
            </div>

            <div class="form-group">
                <label for="resourceAccess">Access Level</label>
                <select id="resourceAccess" name="resourceAccess">
                    <option value="public">Public (All Users)</option>
                    <option value="students">Students Only</option>
                    <option value="alumni">Alumni Only</option>
                    <option value="faculty">Faculty Only</option>
                    <option value="restricted">Restricted Access</option>
                </select>
            </div>

            <div class="form-group">
                <label for="resourceAuthor">Author/Contributor</label>
                <input type="text" id="resourceAuthor" name="resourceAuthor" placeholder="Enter author or contributor name">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeAddResourceModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Resource</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.reported-resources-section, .recent-resources-section {
    margin-bottom: 2rem;
}

.subsection-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
}

.reported-resources-container, .recent-resources-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reported-resource-card, .recent-resource-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.reported-resource-card:hover, .recent-resource-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.resource-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.resource-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.resource-author {
    margin: 0 0 0.25rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.resource-type {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.resource-meta {
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

.resource-content {
    margin-bottom: 1rem;
}

.resource-content p {
    margin: 0 0 0.5rem 0;
    color: #4B5563;
    line-height: 1.5;
}

.resource-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
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

.resource-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Button styles are now in buttons.css - removed to prevent override */

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

.add-resource-btn {
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

.add-resource-btn:hover {
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

.resource-form {
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

. {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            if (confirm('Are you sure you want to approve this resource?')) {
                alert('Resource approved successfully!');
                this.closest('.reported-resource-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle hide button clicks
    document.querySelectorAll('.hide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            if (confirm('Are you sure you want to hide this resource?')) {
                alert('Resource hidden successfully!');
                this.closest('.reported-resource-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            if (confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
                alert('Resource deleted successfully!');
                this.closest('.reported-resource-card').remove();
            }
        });
    });

    // Handle download button clicks
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            alert('Download functionality would be implemented here for resource ID: ' + resourceId);
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            alert('View details functionality would be implemented here for resource ID: ' + resourceId);
        });
    });

    // Handle moderate button clicks
    document.querySelectorAll('.moderate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            alert('Moderate resource functionality would be implemented here for resource ID: ' + resourceId);
        });
    });
});

// Modal Functions
function openAddResourceModal() {
    document.getElementById('addResourceModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddResourceModal() {
    document.getElementById('addResourceModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('resourceForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addResourceModal');
    if (event.target === modal) {
        closeAddResourceModal();
    }
}

// Handle form submission
document.getElementById('resourceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const resourceData = {
        title: formData.get('resourceTitle'),
        category: formData.get('resourceCategory'),
        description: formData.get('resourceDescription'),
        type: formData.get('resourceType'),
        url: formData.get('resourceUrl'),
        tags: formData.get('resourceTags'),
        access: formData.get('resourceAccess'),
        author: formData.get('resourceAuthor')
    };
    
    // Here you would typically send the data to the server
    console.log('Resource Data:', resourceData);
    
    // Show success message
    alert('Resource added successfully!');
    
    // Close modal
    closeAddResourceModal();
    
    // Here you would typically refresh the resources list
    // or add the new resource to the page dynamically
});
</script>
