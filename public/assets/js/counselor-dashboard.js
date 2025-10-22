// counselor-dashboard.js - Counselor Dashboard Functionality

// Global variables
let currentRequestId = null;
let pendingAction = null;

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    setupEventListeners();
    loadDashboardData();
});

// Initialize dashboard components
function initializeDashboard() {
    console.log('Initializing Counselor Dashboard...');
    
    // Set up filter functionality
    setupFilters();
    
    // Initialize modals
    initializeModals();
    
    // Set up real-time updates (simulated)
    setupRealTimeUpdates();
}

// Set up event listeners
function setupEventListeners() {
    // Refresh button
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', refreshDashboard);
    }
    
    // Export button
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportData);
    }
    
    // Filter dropdowns
    const urgencyFilter = document.getElementById('urgencyFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    if (urgencyFilter) {
        urgencyFilter.addEventListener('change', applyFilters);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', applyFilters);
    }
    
    // Modal close buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') || e.target.classList.contains('modal-close')) {
            closeModal();
            closeConfirmationModal();
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeConfirmationModal();
        }
    });
}

// Set up filter functionality
function setupFilters() {
    // Simple filter setup
}

// Initialize modals
function initializeModals() {
    // Basic modal setup
}

// Set up real-time updates
function setupRealTimeUpdates() {
    // Simple updates
}

// Load dashboard data
function loadDashboardData() {
    // Simple data loading
}

// Update overview statistics
function updateOverviewStats() {
    // Basic stats update
}

// Update activity feed
function updateActivityFeed() {
    // Basic activity update
}

// Update notification badge
function updateNotificationBadge() {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        // Simulate new notifications
        const currentCount = parseInt(badge.textContent) || 0;
        const newCount = Math.max(0, currentCount + Math.floor(Math.random() * 3) - 1);
        badge.textContent = newCount;
        badge.style.display = newCount > 0 ? 'block' : 'none';
    }
}

// Apply filters to request cards
function applyFilters() {
    const urgencyFilter = document.getElementById('urgencyFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const requestCards = document.querySelectorAll('.request-card');
    
    requestCards.forEach(card => {
        const cardUrgency = card.dataset.urgency;
        const cardType = card.dataset.type;
        
        let showCard = true;
        
        if (urgencyFilter && cardUrgency !== urgencyFilter) {
            showCard = false;
        }
        
        if (typeFilter && cardType !== typeFilter) {
            showCard = false;
        }
        
        if (showCard) {
            card.classList.remove('hidden', 'filtered-out');
            card.style.display = 'block';
        } else {
            card.classList.add('filtered-out');
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}

// View request details
function viewRequestDetails(requestId) {
    console.log(`Viewing details for request: ${requestId}`);
    
    currentRequestId = requestId;
    
    // Simulate fetching request details
    const requestData = getRequestData(requestId);
    
    if (requestData) {
        displayRequestDetails(requestData);
        showModal('requestDetailsModal');
    }
}

// Get request data (simulated)
function getRequestData(requestId) {
    // This would typically fetch from an API
    const mockData = {
        'AR-2024-001': {
            id: 'AR-2024-001',
            studentName: 'Sarah Johnson',
            studentId: '2024001',
            email: 'sarah.johnson@university.edu',
            phone: '+1 (555) 123-4567',
            major: 'Computer Science',
            year: 'Junior',
            gpa: '3.7',
            aidType: 'Emergency Fund',
            amount: '$2,500',
            urgency: 'Urgent',
            reason: 'Family emergency requiring immediate financial assistance for medical bills. Father was hospitalized and requires surgery. Family is struggling to cover medical expenses while maintaining basic living costs.',
            submittedDate: 'Dec 15, 2024 - 2 hours ago',
            documents: [
                { name: 'Financial Statement', type: 'pdf', size: '2.3 MB' },
                { name: 'Medical Bills', type: 'pdf', size: '1.8 MB' },
                { name: 'Enrollment Proof', type: 'pdf', size: '0.5 MB' }
            ],
            previousAid: 'Received textbook support ($300) in Fall 2024',
            status: 'Pending'
        }
    };
    
    return mockData[requestId] || null;
}

// Display request details in modal
function displayRequestDetails(data) {
    const modalBody = document.getElementById('modalBody');
    
    modalBody.innerHTML = `
        <div class="request-details-modal">
            <div class="detail-section">
                <h4>Student Information</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Name:</span>
                        <span class="value">${data.studentName}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Student ID:</span>
                        <span class="value">${data.studentId}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email:</span>
                        <span class="value">${data.email}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Phone:</span>
                        <span class="value">${data.phone}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Major:</span>
                        <span class="value">${data.major}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Academic Year:</span>
                        <span class="value">${data.year}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">GPA:</span>
                        <span class="value">${data.gpa}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Request Information</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Request ID:</span>
                        <span class="value">${data.id}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Aid Type:</span>
                        <span class="value">${data.aidType}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Amount:</span>
                        <span class="value amount">${data.amount}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Urgency:</span>
                        <span class="value urgency-${data.urgency.toLowerCase()}">${data.urgency}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Submitted:</span>
                        <span class="value">${data.submittedDate}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Status:</span>
                        <span class="value status-${data.status.toLowerCase()}">${data.status}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Reason for Request</h4>
                <div class="reason-text">
                    ${data.reason}
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Previous Aid Received</h4>
                <div class="previous-aid">
                    ${data.previousAid || 'No previous aid received'}
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Supporting Documents</h4>
                <div class="documents-list">
                    ${data.documents.map(doc => `
                        <div class="document-item">
                            <i class="fas fa-file-${doc.type === 'pdf' ? 'pdf' : 'alt'}"></i>
                            <span class="document-name">${doc.name}</span>
                            <span class="document-size">${doc.size}</span>
                            <button class="btn btn-outline btn-xs" onclick="downloadDocument('${doc.name}')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    `;
}

// Approve request
function approveRequest(requestId) {
    console.log(`Approving request: ${requestId}`);
    
    currentRequestId = requestId;
    pendingAction = 'approve';
    
    showConfirmationModal(
        'Approve Request',
        `Are you sure you want to approve request ${requestId}? This action cannot be undone.`
    );
}

// Reject request
function rejectRequest(requestId) {
    console.log(`Rejecting request: ${requestId}`);
    
    currentRequestId = requestId;
    pendingAction = 'reject';
    
    showConfirmationModal(
        'Reject Request',
        `Are you sure you want to reject request ${requestId}? Please provide a reason for rejection.`
    );
}

// Approve request from modal
function approveRequestFromModal() {
    if (currentRequestId) {
        approveRequest(currentRequestId);
    }
}

// Reject request from modal
function rejectRequestFromModal() {
    if (currentRequestId) {
        rejectRequest(currentRequestId);
    }
}

// Execute confirmed action
function executeConfirmedAction() {
    if (!currentRequestId || !pendingAction) {
        console.error('No pending action to execute');
        return;
    }
    
    // Get the counselor's note
    const noteTextarea = document.getElementById('counselorNote');
    const counselorNote = noteTextarea ? noteTextarea.value.trim() : '';
    
    if (pendingAction === 'approve') {
        processApproval(currentRequestId, counselorNote);
    } else if (pendingAction === 'reject') {
        processRejection(currentRequestId, counselorNote);
    }
    
    closeConfirmationModal();
    closeModal();
    
    // Reset variables
    currentRequestId = null;
    pendingAction = null;
}

// Process approval
function processApproval(requestId, counselorNote = '') {
    console.log(`Processing approval for request: ${requestId}`, { note: counselorNote });
    
    // Simulate API call
    setTimeout(() => {
        // Update UI
        updateRequestStatus(requestId, 'approved');
        
        // Show success message
        const message = counselorNote ? 
            'Request approved successfully with note!' : 
            'Request approved successfully!';
        showNotification(message, 'success');
        
        // Update statistics
        updateOverviewStats();
        
        // Add to activity feed with note info
        const activityDescription = counselorNote ? 
            `Request ${requestId} approved with note: "${counselorNote.substring(0, 50)}${counselorNote.length > 50 ? '...' : ''}"` :
            `Request ${requestId} approved`;
        addActivityItem('approved', activityDescription);
        
        // Log the action with note
        logCounselorAction(requestId, 'approved', counselorNote);
        
    }, 1000);
}

// Process rejection
function processRejection(requestId, counselorNote = '') {
    console.log(`Processing rejection for request: ${requestId}`, { note: counselorNote });
    
    // Simulate API call
    setTimeout(() => {
        // Update UI
        updateRequestStatus(requestId, 'rejected');
        
        // Show success message
        const message = counselorNote ? 
            'Request rejected with note sent to student!' : 
            'Request rejected successfully!';
        showNotification(message, 'success');
        
        // Update statistics
        updateOverviewStats();
        
        // Add to activity feed with note info
        const activityDescription = counselorNote ? 
            `Request ${requestId} rejected with note: "${counselorNote.substring(0, 50)}${counselorNote.length > 50 ? '...' : ''}"` :
            `Request ${requestId} rejected`;
        addActivityItem('rejected', activityDescription);
        
        // Log the action with note
        logCounselorAction(requestId, 'rejected', counselorNote);
        
    }, 1000);
}

// Log counselor action for audit trail
function logCounselorAction(requestId, action, note) {
    const actionLog = {
        requestId: requestId,
        action: action,
        note: note,
        counselorId: 'current-counselor', // In real app, this would be the actual counselor ID
        timestamp: new Date().toISOString(),
        date: Date.now()
    };
    
    console.log('Counselor action logged:', actionLog);
    
    // In a real application, you would send this to your backend
    // fetch('/api/log-counselor-action', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(actionLog)
    // });
}

// Update request status
function updateRequestStatus(requestId, status) {
    const requestCard = document.querySelector(`[data-request-id="${requestId}"]`);
    if (requestCard) {
        requestCard.classList.add(status);
        
        // Update status badge
        const statusBadge = requestCard.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            statusBadge.className = `status-badge status-${status}`;
        }
        
        // Disable action buttons
        const actionButtons = requestCard.querySelectorAll('.request-actions .btn');
        actionButtons.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.5';
        });
    }
}

// Show modal
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

// Close modal
function closeModal() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
    });
    document.body.style.overflow = 'auto';
}

// Show confirmation modal
function showConfirmationModal(title, message) {
    const modal = document.getElementById('confirmationModal');
    const titleElement = document.getElementById('confirmationTitle');
    const messageElement = document.getElementById('confirmationMessage');
    const confirmBtn = document.getElementById('confirmActionBtn');
    
    if (modal && titleElement && messageElement && confirmBtn) {
        titleElement.textContent = title;
        messageElement.textContent = message;
        
        // Update confirm button based on action
        if (pendingAction === 'approve') {
            confirmBtn.textContent = 'Approve';
            confirmBtn.className = 'btn btn-success';
        } else if (pendingAction === 'reject') {
            confirmBtn.textContent = 'Reject';
            confirmBtn.className = 'btn btn-danger';
        }
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

// Close confirmation modal
function closeConfirmationModal() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
        
        // Clear the note field
        const noteTextarea = document.getElementById('counselorNote');
        if (noteTextarea) {
            noteTextarea.value = '';
        }
    }
}

// Download document
function downloadDocument(documentName, requestId) {
    console.log(`Downloading document: ${documentName} for request: ${requestId}`);
    
    // Show loading notification
    showNotification(`Downloading ${documentName}...`, 'info');
    
    // Simulate download process
    setTimeout(() => {
        // In a real application, this would trigger an actual download
        // For demo purposes, we'll just show a success message
        showNotification(`${documentName} downloaded successfully!`, 'success');
        
        // Log the download action
        console.log(`Document downloaded: ${documentName} from request ${requestId}`);
        
        // You could also track downloads for audit purposes
        trackDocumentDownload(requestId, documentName);
    }, 1500);
}

// Track document download for audit purposes
function trackDocumentDownload(requestId, documentName) {
    const downloadLog = {
        requestId: requestId,
        documentName: documentName,
        downloadedBy: 'Counselor', // In real app, this would be the actual counselor ID
        downloadedAt: new Date().toISOString(),
        timestamp: Date.now()
    };
    
    console.log('Download logged:', downloadLog);
    
    // In a real application, you would send this to your backend
    // fetch('/api/track-download', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(downloadLog)
    // });
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Get notification icon based on type
function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Add activity item (simplified)
function addActivityItem(type, description) {
    // Simple activity logging
    console.log(`Activity: ${type} - ${description}`);
}

// Refresh dashboard
function refreshDashboard() {
    console.log('Refreshing dashboard...');
    
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.classList.add('loading');
        refreshBtn.disabled = true;
    }
    
    // Simulate refresh
    setTimeout(() => {
        loadDashboardData();
        
        if (refreshBtn) {
            refreshBtn.classList.remove('loading');
            refreshBtn.disabled = false;
        }
        
        showNotification('Dashboard refreshed successfully!', 'success');
    }, 2000);
}

// Export data
function exportData() {
    console.log('Exporting data...');
    
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.classList.add('loading');
        exportBtn.disabled = true;
    }
    
    // Simulate export
    setTimeout(() => {
        if (exportBtn) {
            exportBtn.classList.remove('loading');
            exportBtn.disabled = false;
        }
        
        showNotification('Data exported successfully!', 'success');
    }, 3000);
}

// Utility function to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Utility function to format date
function formatDate(date) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

// Export functions for global access
window.viewRequestDetails = viewRequestDetails;
window.approveRequest = approveRequest;
window.rejectRequest = rejectRequest;
window.approveRequestFromModal = approveRequestFromModal;
window.rejectRequestFromModal = rejectRequestFromModal;
window.closeModal = closeModal;
window.closeConfirmationModal = closeConfirmationModal;
window.executeConfirmedAction = executeConfirmedAction;
window.downloadDocument = downloadDocument;
