<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- FAQ Moderation Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">FAQ Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $faqData['stats']['total_faqs'] ?></span>
                        <span class="stat-label">Total FAQs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $faqData['stats']['published_faqs'] ?></span>
                        <span class="stat-label">Published</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $faqData['stats']['pending_faqs'] ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Filters -->
            <div class="faq-filters">
                <div class="search-box">
                    <input type="text" id="faq-search" placeholder="Search FAQs..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-options">
                    <select id="category-filter" class="filter-select">
                        <option value="">All Categories</option>
                        <?php foreach ($faqData['categories'] as $category): ?>
                        <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="status-filter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="pending">Pending</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>
            
            <!-- Add New FAQ Button -->
            <div class="add-faq-section">
                <button class="btn btn-primary add-faq-btn" onclick="openAddFaqModal()">
                    <i class="fas fa-plus"></i>
                    Add New FAQ
                </button>
            </div>
            
            <!-- FAQs List -->
            <div class="faqs-container">
                <?php foreach ($faqData['faq_items'] as $faq): ?>
                <div class="faq-card" data-category="<?= esc($faq['category']) ?>" data-status="<?= $faq['status'] ?>">
                    <div class="faq-header">
                        <div class="faq-info">
                            <h4 class="faq-question"><?= esc($faq['question']) ?></h4>
                            <p class="faq-category"><?= esc($faq['category']) ?></p>
                        </div>
                        <div class="faq-meta">
                            <span class="status-badge status-<?= $faq['status'] ?>"><?= ucfirst($faq['status']) ?></span>
                            <div class="faq-stats">
                                <span class="stat"><i class="fas fa-eye"></i> <?= $faq['views'] ?> views</span>
                                <span class="stat"><i class="fas fa-thumbs-up"></i> <?= $faq['helpful'] ?> helpful</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-content">
                        <p><strong>Answer:</strong> <?= esc($faq['answer']) ?></p>
                    </div>
                    
                    <div class="faq-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y', strtotime($faq['created_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-edit"></i>
                            <span><strong>Last Updated:</strong> <?= date('M j, Y', strtotime($faq['last_updated'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="faq-actions">
                        <?php if ($faq['status'] === 'pending'): ?>
                        <button class="btn btn-success btn-sm approve-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($faq['status'] === 'published'): ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-primary btn-sm edit-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-faq-id="<?= $faq['id'] ?>">
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

<!-- Add FAQ Modal -->
<div id="addFaqModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New FAQ</h2>
            <button class="modal-close" onclick="closeAddFaqModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="faq-form" id="faqForm">
            <div class="form-group">
                <label for="faqQuestion">Question *</label>
                <input type="text" id="faqQuestion" name="faqQuestion" placeholder="Enter the FAQ question" required>
            </div>

            <div class="form-group">
                <label for="faqAnswer">Answer *</label>
                <textarea id="faqAnswer" name="faqAnswer" rows="6" placeholder="Enter the detailed answer..." required></textarea>
            </div>

            <div class="form-group">
                <label for="faqCategory">Category *</label>
                <select id="faqCategory" name="faqCategory" required>
                    <option value="">Select a category</option>
                    <option value="General">General</option>
                    <option value="Academic">Academic</option>
                    <option value="Career">Career</option>
                    <option value="Financial">Financial Aid</option>
                    <option value="Technical">Technical Support</option>
                    <option value="Registration">Registration</option>
                    <option value="Graduation">Graduation</option>
                    <option value="Alumni">Alumni Services</option>
                </select>
            </div>

            <div class="form-group">
                <label for="faqPriority">Priority</label>
                <select id="faqPriority" name="faqPriority">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="faqStatus">Status</label>
                <select id="faqStatus" name="faqStatus">
                    <option value="draft">Draft</option>
                    <option value="pending">Pending Review</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="form-group">
                <label for="faqTags">Tags (Optional)</label>
                <input type="text" id="faqTags" name="faqTags" placeholder="Enter tags separated by commas (e.g., registration, financial, academic)">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeAddFaqModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add FAQ</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.faq-filters {
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

.faqs-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.faq-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.faq-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.faq-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.faq-question {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.faq-category {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.faq-meta {
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

.status-published {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-pending {
    background-color: #FEF3C7;
    color: #D97706;
}

.status-draft {
    background-color: #E5E7EB;
    color: #6B7280;
}

.faq-stats {
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

.faq-content {
    margin-bottom: 1rem;
}

.faq-content p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.faq-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.faq-actions {
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

/* Add FAQ Section */
.add-faq-section {
    margin-bottom: 2rem;
}

.add-faq-btn {
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

.add-faq-btn:hover {
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

.faq-form {
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
            const faqId = this.getAttribute('data-faq-id');
            if (confirm('Are you sure you want to approve this FAQ?')) {
                alert('FAQ approved successfully!');
                this.closest('.faq-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle unpublish button clicks
    document.querySelectorAll('.unpublish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const faqId = this.getAttribute('data-faq-id');
            if (confirm('Are you sure you want to unpublish this FAQ?')) {
                alert('FAQ unpublished successfully!');
                this.closest('.faq-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const faqId = this.getAttribute('data-faq-id');
            alert('Edit FAQ functionality would be implemented here for FAQ ID: ' + faqId);
        });
    });

    // Handle view button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const faqId = this.getAttribute('data-faq-id');
            alert('View FAQ functionality would be implemented here for FAQ ID: ' + faqId);
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const faqId = this.getAttribute('data-faq-id');
            if (confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')) {
                alert('FAQ deleted successfully!');
                this.closest('.faq-card').remove();
            }
        });
    });

    // Handle search functionality
    document.getElementById('faq-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const faqCards = document.querySelectorAll('.faq-card');
        
        faqCards.forEach(card => {
            const question = card.querySelector('.faq-question').textContent.toLowerCase();
            const answer = card.querySelector('.faq-content p').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle category filter
    document.getElementById('category-filter').addEventListener('change', function() {
        const selectedCategory = this.value;
        const faqCards = document.querySelectorAll('.faq-card');
        
        faqCards.forEach(card => {
            if (selectedCategory === '' || card.getAttribute('data-category') === selectedCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const faqCards = document.querySelectorAll('.faq-card');
        
        faqCards.forEach(card => {
            if (selectedStatus === '' || card.getAttribute('data-status') === selectedStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Modal Functions
function openAddFaqModal() {
    document.getElementById('addFaqModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddFaqModal() {
    document.getElementById('addFaqModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('faqForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addFaqModal');
    if (event.target === modal) {
        closeAddFaqModal();
    }
}

// Handle form submission
document.getElementById('faqForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const faqData = {
        question: formData.get('faqQuestion'),
        answer: formData.get('faqAnswer'),
        category: formData.get('faqCategory'),
        priority: formData.get('faqPriority'),
        status: formData.get('faqStatus'),
        tags: formData.get('faqTags')
    };
    
    // Here you would typically send the data to the server
    console.log('FAQ Data:', faqData);
    
    // Show success message
    alert('FAQ added successfully!');
    
    // Close modal
    closeAddFaqModal();
    
    // Here you would typically refresh the FAQ list
    // or add the new FAQ to the page dynamically
});
</script>
