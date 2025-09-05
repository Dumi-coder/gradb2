// dashboard.js - Student Dashboard Functionality

document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    initializeNavigation();
    initializeInteractiveElements();
    initializeNotifications();
    initializeStatusUpdates();
}

// Navigation functionality
function initializeNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Check if it's an external link (like mentorship.html)
            if (href && !href.startsWith('#')) {
                // Allow external navigation
                return;
            }
            
            // For internal anchor links, prevent default and scroll
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Get target section
            const targetId = href.substring(1);
            const targetSection = document.querySelector(`#${targetId}`);
            
            if (targetSection) {
                targetSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Interactive elements functionality
function initializeInteractiveElements() {
    // Edit profile button
    const editProfileBtn = document.querySelector('.edit-profile-btn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            // Create component only when needed
            if (!window.editProfileComponent) {
                window.editProfileComponent = new EditProfileComponent();
            }
            window.editProfileComponent.show();
        });
    }
    
    // Request mentorship button
    const requestMentorshipBtn = document.querySelector('.request-mentorship-btn');
    if (requestMentorshipBtn) {
        requestMentorshipBtn.addEventListener('click', function() {
                // Redirect to mentorship page instead of showing modal
    window.location.href = 'mentorship.html';
        });
    }
    
    // New aid request button
    const newAidBtn = document.querySelector('.new-aid-btn');
    if (newAidBtn) {
        newAidBtn.addEventListener('click', function() {
            // Redirect to aid request page instead of showing modal
            window.location.href = 'request-aid.html';
        });
    }
    
    // Upload file button
    const uploadFileBtn = document.querySelector('.upload-file-btn');
    if (uploadFileBtn) {
        uploadFileBtn.addEventListener('click', function() {
            showFileUploadModal();
        });
    }
    
    // Request fundraiser button
    const requestFundraiserBtn = document.querySelector('.request-fundraiser-btn');
    if (requestFundraiserBtn) {
        requestFundraiserBtn.addEventListener('click', function() {
            showRequestFundraiserModal();
        });
    }
    
    // Donate button
    const donateBtn = document.querySelector('.donate-btn');
    if (donateBtn) {
        donateBtn.addEventListener('click', function() {
            showDonationModal();
        });
    }
    
    // Logout button
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            handleLogout();
        });
    }
    
    // Event registration buttons
    const eventRegisterBtns = document.querySelectorAll('.event-register-btn');
    eventRegisterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            handleEventRegistration(this);
        });
    });
    
    // View details buttons in aid requests table
    const viewDetailsBtns = document.querySelectorAll('.aid-requests-table .btn-sm');
    viewDetailsBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            showAidRequestDetails(this);
        });
    });
}

// Notifications functionality
function initializeNotifications() {
    const notificationBtn = document.querySelector('.notification-btn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            showNotificationsPanel();
        });
    }
}

// Status updates functionality
function initializeStatusUpdates() {
    // Simulate real-time status updates
    setInterval(() => {
        updateStatusBadges();
    }, 30000); // Update every 30 seconds
}

// Edit Profile Component will be initialized separately

// showRequestMentorshipModal function removed - now redirects to request-mentorship.html page

// showNewAidRequestModal function removed - now redirects to request-aid.html page

function showFileUploadModal() {
    const modal = createModal('Upload New File', `
        <form class="file-upload-form">
            <div class="form-group">
                <label for="file-title">File Title</label>
                <input type="text" id="file-title" placeholder="Enter a descriptive title" required>
            </div>
            <div class="form-group">
                <label for="file-description">Description</label>
                <textarea id="file-description" rows="3" placeholder="Describe the file content..."></textarea>
            </div>
            <div class="form-group">
                <label for="file-category">Category</label>
                <select id="file-category" required>
                    <option value="">Select category</option>
                    <option value="study-materials">Study Materials</option>
                    <option value="career-resources">Career Resources</option>
                    <option value="templates">Templates</option>
                    <option value="guides">Guides & Tutorials</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file-upload">Choose File</label>
                <input type="file" id="file-upload" required>
                <small>Maximum file size: 10MB</small>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload File</button>
            </div>
        </form>
    `);
    
    const form = modal.querySelector('.file-upload-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('File uploaded successfully!', 'success');
        closeModal();
    });
}

function showRequestFundraiserModal() {
    const modal = createModal('Request Fundraiser', `
        <form class="request-fundraiser-form">
            <div class="form-group">
                <label for="fundraiser-title">Fundraiser Title</label>
                <input type="text" id="fundraiser-title" placeholder="Enter a compelling title" required>
            </div>
            <div class="form-group">
                <label for="fundraiser-goal">Fundraising Goal</label>
                <input type="number" id="fundraiser-goal" min="1" step="0.01" placeholder="Enter goal amount" required>
            </div>
            <div class="form-group">
                <label for="fundraiser-description">Description</label>
                <textarea id="fundraiser-description" rows="4" placeholder="Explain what you're raising funds for..." required></textarea>
            </div>
            <div class="form-group">
                <label for="fundraiser-deadline">Campaign Deadline</label>
                <input type="date" id="fundraiser-deadline" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    `);
    
    const form = modal.querySelector('.request-fundraiser-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Fundraiser request submitted!', 'success');
        closeModal();
    });
}

function showDonationModal() {
    const modal = createModal('Make a Donation', `
        <form class="donation-form">
            <div class="form-group">
                <label for="donation-amount">Donation Amount</label>
                <div class="amount-options">
                    <button type="button" class="amount-option" data-amount="25">$25</button>
                    <button type="button" class="amount-option" data-amount="50">$50</button>
                    <button type="button" class="amount-option" data-amount="100">$100</button>
                    <button type="button" class="amount-option" data-amount="250">$250</button>
                </div>
                <input type="number" id="donation-amount" placeholder="Or enter custom amount" min="1" step="0.01">
            </div>
            <div class="form-group">
                <label for="donation-message">Message (Optional)</label>
                <textarea id="donation-message" rows="3" placeholder="Leave a message of support..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Donate Now</button>
            </div>
        </form>
    `);
    
    // Handle amount option clicks
    const amountOptions = modal.querySelectorAll('.amount-option');
    amountOptions.forEach(option => {
        option.addEventListener('click', function() {
            amountOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('donation-amount').value = this.dataset.amount;
        });
    });
    
    const form = modal.querySelector('.donation-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Thank you for your donation!', 'success');
        closeModal();
    });
}

// Utility functions
function createModal(title, content) {
    // Remove existing modal if any
    const existingModal = document.querySelector('.modal-overlay');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';
    modalOverlay.innerHTML = `
        <div class="modal">
            <div class="modal-header">
                <h3>${title}</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
        </div>
    `;
    
    document.body.appendChild(modalOverlay);
    
    // Add modal styles if not already present
    if (!document.querySelector('#modal-styles')) {
        addModalStyles();
    }
    
    return modalOverlay;
}

function closeModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        modal.remove();
    }
}

function addModalStyles() {
    const style = document.createElement('style');
    style.id = 'modal-styles';
    style.textContent = `
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        
        .modal {
            background-color: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        
        .modal-close:hover {
            background-color: #f3f4f6;
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-group small {
            display: block;
            margin-top: 4px;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .amount-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .amount-option {
            padding: 12px;
            border: 1px solid #d1d5db;
            background-color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .amount-option:hover {
            border-color: #0E2072;
            background-color: #f8fafc;
        }
        
        .amount-option.active {
            border-color: #0E2072;
            background-color: #0E2072;
            color: white;
        }
        
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }
        
        .modal-actions .btn {
            min-width: 100px;
        }
    `;
    
    document.head.appendChild(style);
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    // Add toast styles if not already present
    if (!document.querySelector('#toast-styles')) {
        addToastStyles();
    }
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function addToastStyles() {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 10001;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast-success {
            border-left: 4px solid #10b981;
        }
        
        .toast-info {
            border-left: 4px solid #3b82f6;
        }
        
        .toast-warning {
            border-left: 4px solid #f59e0b;
        }
        
        .toast-error {
            border-left: 4px solid #ef4444;
        }
    `;
    
    document.head.appendChild(style);
}

// Event handlers
function handleEventRegistration(button) {
    const eventTitle = button.closest('.event-card').querySelector('.event-title').textContent;
    
    if (button.textContent === 'Register') {
        button.textContent = 'Registered';
        button.classList.remove('btn-primary', 'btn-outline');
        button.classList.add('btn-secondary');
        button.disabled = true;
        showToast(`Successfully registered for "${eventTitle}"!`, 'success');
    }
}

function showAidRequestDetails(button) {
    const row = button.closest('tr');
    const type = row.cells[0].textContent;
    const status = row.cells[1].querySelector('.status-badge').textContent;
    
    const modal = createModal('Aid Request Details', `
        <div class="aid-request-details">
            <h4>${type}</h4>
            <p><strong>Status:</strong> <span class="status-badge ${getStatusClass(status)}">${status}</span></p>
            <p><strong>Submitted:</strong> ${getRandomDate()}</p>
            <p><strong>Amount:</strong> $${getRandomAmount()}</p>
            <p><strong>Description:</strong> This is a detailed description of the aid request and the circumstances surrounding it.</p>
        </div>
    `);
}

function showNotificationsPanel() {
    const modal = createModal('Notifications', `
        <div class="notifications-panel">
            <div class="notification-item unread">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-content">
                    <h5>New Mentorship Request</h5>
                    <p>Dr. Chen has accepted your mentorship request</p>
                    <span class="notification-time">2 hours ago</span>
                </div>
            </div>
            
            <div class="notification-item unread">
                <div class="notification-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="notification-content">
                    <h5>Aid Request Update</h5>
                    <p>Your tuition assistance request has been approved</p>
                    <span class="notification-time">1 day ago</span>
                </div>
            </div>
            
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="notification-content">
                    <h5>Event Reminder</h5>
                    <p>Alumni Networking Mixer starts in 2 hours</p>
                    <span class="notification-time">2 days ago</span>
                </div>
            </div>
        </div>
    `);
    
    // Add notification styles
    if (!document.querySelector('#notification-styles')) {
        addNotificationStyles();
    }
}

function addNotificationStyles() {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        .notifications-panel {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-item {
            display: flex;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item.unread {
            background-color: #f8fafc;
            margin: 0 -24px;
            padding: 16px 24px;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            background-color: #0E2072;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
        
        .notification-content h5 {
            margin: 0 0 4px 0;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .notification-content p {
            margin: 0 0 8px 0;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #9ca3af;
        }
    `;
    
    document.head.appendChild(style);
}

function handleLogout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'login.html';
    }
}

// Helper functions
function getStatusClass(status) {
    const statusMap = {
        'Pending': 'status-pending',
        'Approved': 'status-approved',
        'Completed': 'status-completed',
        'Waiting': 'status-waiting',
        'Accepted': 'status-accepted'
    };
    return statusMap[status] || 'status-pending';
}

function getRandomDate() {
    const dates = ['Dec 10, 2024', 'Dec 8, 2024', 'Dec 5, 2024', 'Dec 1, 2024'];
    return dates[Math.floor(Math.random() * dates.length)];
}

function getRandomAmount() {
    const amounts = [500, 750, 1000, 1250, 1500];
    return amounts[Math.floor(Math.random() * amounts.length)];
}

function updateStatusBadges() {
    // Simulate status updates
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        if (Math.random() < 0.1) { // 10% chance of status change
            const statuses = ['Pending', 'Approved', 'Completed', 'Waiting', 'Accepted'];
            const newStatus = statuses[Math.floor(Math.random() * statuses.length)];
            badge.textContent = newStatus;
            badge.className = `status-badge ${getStatusClass(newStatus)}`;
        }
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Profile Picture functionality moved to EditProfileComponent
