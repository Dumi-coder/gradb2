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
                <div id="faq-empty-state" class="faq-empty-state <?= empty($faqData['faq_items']) ? '' : 'is-hidden' ?>">
                    There is nothing to show.
                </div>
                <?php foreach ($faqData['faq_items'] as $faq): ?>
                <div class="faq-card" 
                     data-category="<?= esc($faq['category']) ?>" 
                     data-status="<?= $faq['status'] ?>"
                     data-faq-id="<?= $faq['id'] ?>"
                     data-question="<?= esc($faq['question']) ?>"
                     data-answer="<?= esc($faq['answer']) ?>"
                     data-priority="<?= esc($faq['priority'] ?? 'normal') ?>"
                     data-tags="<?= esc($faq['tags'] ?? '') ?>">
                    <div class="faq-header">
                        <div class="faq-info">
                            <h4 class="faq-question"><?= esc($faq['question']) ?></h4>
                            <p class="faq-category"><?= esc($faq['category']) ?></p>
                        </div>
                        <div class="faq-meta">
                            <span class="status-badge status-<?= $faq['status'] ?>"><?= ucfirst($faq['status']) ?></span>
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
                        <?php if ($faq['status'] === 'published'): ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success btn-sm publish-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-check"></i>
                            Publish
                        </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-primary btn-sm edit-btn" data-faq-id="<?= $faq['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
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
                    <option value="general">General</option>
                    <option value="mentorship">Mentorship</option>
                    <option value="aid">Aid Requests</option>
                    <option value="technical">Technical</option>
                    <option value="fundraiser">Fundraiser</option>
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

<!-- Edit FAQ Modal -->
<div id="editFaqModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit FAQ</h2>
            <button class="modal-close" onclick="closeEditFaqModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="faq-form" id="editFaqForm">
            <input type="hidden" id="editFaqId" name="editFaqId">
            
            <div class="form-group">
                <label for="editFaqQuestion">Question *</label>
                <input type="text" id="editFaqQuestion" name="editFaqQuestion" placeholder="Enter the FAQ question" required>
            </div>

            <div class="form-group">
                <label for="editFaqAnswer">Answer *</label>
                <textarea id="editFaqAnswer" name="editFaqAnswer" rows="6" placeholder="Enter the detailed answer..." required></textarea>
            </div>

            <div class="form-group">
                <label for="editFaqCategory">Category *</label>
                <select id="editFaqCategory" name="editFaqCategory" required>
                    <option value="">Select a category</option>
                    <option value="general">General</option>
                    <option value="mentorship">Mentorship</option>
                    <option value="aid">Aid Requests</option>
                    <option value="technical">Technical</option>
                    <option value="fundraiser">Fundraiser</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editFaqPriority">Priority</label>
                <select id="editFaqPriority" name="editFaqPriority">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editFaqStatus">Status</label>
                <select id="editFaqStatus" name="editFaqStatus">
                    <option value="draft">Draft</option>
                    <option value="pending">Pending Review</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editFaqTags">Tags (Optional)</label>
                <input type="text" id="editFaqTags" name="editFaqTags" placeholder="Enter tags separated by commas">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeEditFaqModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Update FAQ</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Alert Modal -->
<div id="customAlert" class="custom-modal">
    <div class="custom-modal-content alert-modal">
        <div class="custom-modal-icon">
            <i class="fas fa-check-circle" id="alertIcon"></i>
        </div>
        <h3 id="alertTitle">Success</h3>
        <p id="alertMessage"></p>
        <button class="btn btn-primary" onclick="closeCustomAlert()">OK</button>
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="customConfirm" class="custom-modal">
    <div class="custom-modal-content confirm-modal">
        <div class="custom-modal-icon warning">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 id="confirmTitle">Confirm Action</h3>
        <p id="confirmMessage"></p>
        <div class="custom-modal-actions">
            <button class="btn btn-outline" onclick="closeCustomConfirm(false)">Cancel</button>
            <button class="btn btn-primary" onclick="closeCustomConfirm(true)" id="confirmBtn">Confirm</button>
        </div>
    </div>
</div>



<script>
const ROOT_URL = '<?= ROOT ?>';

// Custom Alert Function
let alertCallback = null;

function showAlert(message, type = 'success') {
    const modal = document.getElementById('customAlert');
    const icon = document.getElementById('alertIcon');
    const title = document.getElementById('alertTitle');
    const messageEl = document.getElementById('alertMessage');
    
    messageEl.textContent = message;
    
    if (type === 'success') {
        title.textContent = 'Success';
        icon.className = 'fas fa-check-circle';
        icon.parentElement.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    } else if (type === 'error') {
        title.textContent = 'Error';
        icon.className = 'fas fa-times-circle';
        icon.parentElement.style.background = 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    return new Promise((resolve) => {
        alertCallback = resolve;
    });
}

function closeCustomAlert() {
    document.getElementById('customAlert').style.display = 'none';
    document.body.style.overflow = 'auto';
    if (alertCallback) {
        alertCallback();
        alertCallback = null;
    }
}

// Custom Confirm Function
let confirmCallback = null;

function showConfirm(message, title = 'Confirm Action') {
    const modal = document.getElementById('customConfirm');
    const titleEl = document.getElementById('confirmTitle');
    const messageEl = document.getElementById('confirmMessage');
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    return new Promise((resolve) => {
        confirmCallback = resolve;
    });
}

function closeCustomConfirm(result) {
    document.getElementById('customConfirm').style.display = 'none';
    document.body.style.overflow = 'auto';
    if (confirmCallback) {
        confirmCallback(result);
        confirmCallback = null;
    }
}

function sendAjaxRequest(action, faqId, formData = null) {
    const data = new FormData();
    data.append('action', action);
    if (faqId) data.append('faq_id', faqId);
    if (formData) {
        for (let [key, value] of formData.entries()) {
            data.append(key, value);
        }
    }

    return fetch(window.location.href, {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            return result;
        } else {
            throw new Error(result.message || 'Operation failed');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    function updateFaqEmptyState() {
        const emptyState = document.getElementById('faq-empty-state');
        const faqCards = document.querySelectorAll('.faq-card');
        let visibleCount = 0;

        faqCards.forEach(card => {
            if (card.style.display !== 'none') {
                visibleCount++;
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function applyFaqFilters() {
        const searchTerm = document.getElementById('faq-search').value.toLowerCase();
        const selectedCategory = document.getElementById('category-filter').value;
        const selectedStatus = document.getElementById('status-filter').value;
        const faqCards = document.querySelectorAll('.faq-card');

        faqCards.forEach(card => {
            const question = card.querySelector('.faq-question').textContent.toLowerCase();
            const answer = card.querySelector('.faq-content p').textContent.toLowerCase();
            const cardCategory = card.getAttribute('data-category');
            const cardStatus = card.getAttribute('data-status');

            const matchesSearch = searchTerm === '' || question.includes(searchTerm) || answer.includes(searchTerm);
            const matchesCategory = selectedCategory === '' || cardCategory === selectedCategory;
            const matchesStatus = selectedStatus === '' || cardStatus === selectedStatus;

            card.style.display = (matchesSearch && matchesCategory && matchesStatus) ? 'block' : 'none';
        });

        updateFaqEmptyState();
    }

    // Handle publish button clicks
    document.querySelectorAll('.publish-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const faqId = this.getAttribute('data-faq-id');
            const confirmed = await showConfirm('Are you sure you want to publish this FAQ?', 'Publish FAQ');
            
            if (confirmed) {
                sendAjaxRequest('publish', faqId)
                    .then(async () => {
                        await showAlert('FAQ published successfully!', 'success');
                        location.reload();
                    })
                    .catch(async (error) => {
                        await showAlert('Error: ' + error.message, 'error');
                    });
            }
        });
    });

    // Handle unpublish button clicks
    document.querySelectorAll('.unpublish-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const faqId = this.getAttribute('data-faq-id');
            const confirmed = await showConfirm('Are you sure you want to unpublish this FAQ? You can publish it again anytime.', 'Unpublish FAQ');
            
            if (confirmed) {
                sendAjaxRequest('unpublish', faqId)
                    .then(async () => {
                        await showAlert('FAQ unpublished successfully!', 'success');
                        location.reload();
                    })
                    .catch(async (error) => {
                        await showAlert('Error: ' + error.message, 'error');
                    });
            }
        });
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const faqId = this.getAttribute('data-faq-id');
            const faqCard = this.closest('.faq-card');
            
            // Populate the edit modal with FAQ data
            document.getElementById('editFaqId').value = faqId;
            document.getElementById('editFaqQuestion').value = faqCard.getAttribute('data-question');
            document.getElementById('editFaqAnswer').value = faqCard.getAttribute('data-answer');
            document.getElementById('editFaqCategory').value = faqCard.getAttribute('data-category');
            document.getElementById('editFaqPriority').value = faqCard.getAttribute('data-priority');
            document.getElementById('editFaqStatus').value = faqCard.getAttribute('data-status');
            document.getElementById('editFaqTags').value = faqCard.getAttribute('data-tags');
            
            // Open the edit modal
            openEditFaqModal();
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const faqId = this.getAttribute('data-faq-id');
            const confirmed = await showConfirm('Are you sure you want to delete this FAQ? This action cannot be undone.', 'Delete FAQ');
            
            if (confirmed) {
                const faqCard = this.closest('.faq-card');
                sendAjaxRequest('delete', faqId)
                    .then(async () => {
                        await showAlert('FAQ deleted successfully!', 'success');
                        faqCard.remove();
                        applyFaqFilters();
                    })
                    .catch(async (error) => {
                        await showAlert('Error: ' + error.message, 'error');
                    });
            }
        });
    });

    // Handle search functionality
    document.getElementById('faq-search').addEventListener('input', function() {
        applyFaqFilters();
    });

    // Handle category filter
    document.getElementById('category-filter').addEventListener('change', function() {
        applyFaqFilters();
    });

    // Handle status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        applyFaqFilters();
    });

    applyFaqFilters();
});

// Modal Functions
function openAddFaqModal() {
    document.getElementById('addFaqModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddFaqModal() {
    document.getElementById('addFaqModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('faqForm').reset();
}

function openEditFaqModal() {
    document.getElementById('editFaqModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditFaqModal() {
    document.getElementById('editFaqModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('editFaqForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addFaqModal');
    const editModal = document.getElementById('editFaqModal');
    
    if (event.target === addModal) {
        closeAddFaqModal();
    } else if (event.target === editModal) {
        closeEditFaqModal();
    }
}

// Handle add form submission
document.getElementById('faqForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const faqData = new FormData();
    faqData.append('action', 'add');
    faqData.append('question', formData.get('faqQuestion'));
    faqData.append('answer', formData.get('faqAnswer'));
    faqData.append('category', formData.get('faqCategory'));
    faqData.append('priority', formData.get('faqPriority'));
    faqData.append('status', formData.get('faqStatus'));
    faqData.append('tags', formData.get('faqTags'));
    
    sendAjaxRequest('add', null, faqData)
        .then(async () => {
            closeAddFaqModal();
            await showAlert('FAQ added successfully!', 'success');
            location.reload();
        })
        .catch(async (error) => {
            await showAlert('Error: ' + error.message, 'error');
        });
});

// Handle edit form submission
document.getElementById('editFaqForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const faqData = new FormData();
    const faqId = formData.get('editFaqId');
    
    faqData.append('action', 'edit');
    faqData.append('faq_id', faqId);
    faqData.append('question', formData.get('editFaqQuestion'));
    faqData.append('answer', formData.get('editFaqAnswer'));
    faqData.append('category', formData.get('editFaqCategory'));
    faqData.append('priority', formData.get('editFaqPriority'));
    faqData.append('status', formData.get('editFaqStatus'));
    faqData.append('tags', formData.get('editFaqTags'));
    
    sendAjaxRequest('edit', faqId, faqData)
        .then(async () => {
            closeEditFaqModal();
            await showAlert('FAQ updated successfully!', 'success');
            location.reload();
        })
        .catch(async (error) => {
            await showAlert('Error: ' + error.message, 'error');
        });
});
</script>
