// fundraising.js - Fundraising Page Functionality

// Global variables
let currentCampaign = null;

// DOM elements
const createCampaignModal = document.getElementById('createCampaignModal');
const donateModal = document.getElementById('donateModal');
const campaignForm = document.querySelector('.campaign-form');
const donationForm = document.querySelector('.donation-form');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeFundraising();
});

function initializeFundraising() {
    // Add event listeners
    if (campaignForm) {
        campaignForm.addEventListener('submit', handleCampaignSubmit);
    }
    
    if (donationForm) {
        donationForm.addEventListener('submit', handleDonationSubmit);
    }
    
    // Amount button handlers
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectAmount(this);
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeAllModals();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllModals();
        }
    });
}

// Modal functions
function openCreateCampaignModal() {
    createCampaignModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    setTimeout(() => {
        document.getElementById('campaignTitle').focus();
    }, 100);
}

function closeCreateCampaignModal() {
    createCampaignModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetCampaignForm();
}

function openDonateModal(campaignTitle, goal, raised) {
    currentCampaign = { title: campaignTitle, goal, raised };
    
    // Update modal content
    document.getElementById('donateCampaignTitle').textContent = campaignTitle;
    document.getElementById('donateGoal').textContent = `$${goal.toLocaleString()}`;
    document.getElementById('donateRaised').textContent = `$${raised.toLocaleString()}`;
    
    // Update progress bar
    const progress = (raised / goal) * 100;
    document.getElementById('donateProgressFill').style.width = `${Math.min(progress, 100)}%`;
    
    donateModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Focus on amount input
    setTimeout(() => {
        document.getElementById('donationAmount').focus();
    }, 100);
}

function closeDonateModal() {
    donateModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetDonationForm();
    currentCampaign = null;
}

function closeAllModals() {
    closeCreateCampaignModal();
    closeDonateModal();
}

// Amount selection
function selectAmount(button) {
    // Remove active class from all buttons
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    button.classList.add('active');
    
    // Set amount in input
    const amount = button.getAttribute('data-amount');
    document.getElementById('donationAmount').value = amount;
}

// Form handling
function handleCampaignSubmit(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(campaignForm);
    const campaignData = {
        title: formData.get('campaignTitle'),
        category: formData.get('campaignCategory'),
        goal: parseFloat(formData.get('campaignGoal')),
        description: formData.get('campaignDescription'),
        deadline: formData.get('campaignDeadline'),
        timestamp: new Date().toISOString()
    };
    
    // Validate form
    if (!validateCampaignData(campaignData)) {
        return;
    }
    
    // Submit campaign (simulate API call)
    submitCampaign(campaignData);
}

function handleDonationSubmit(event) {
    event.preventDefault();
    
    if (!currentCampaign) {
        showNotification('No campaign selected', 'error');
        return;
    }
    
    // Get form data
    const formData = new FormData(donationForm);
    const donationData = {
        campaign: currentCampaign.title,
        amount: parseFloat(formData.get('donationAmount')),
        donorName: formData.get('donorName'),
        donorEmail: formData.get('donorEmail'),
        message: formData.get('donorMessage'),
        timestamp: new Date().toISOString()
    };
    
    // Validate form
    if (!validateDonationData(donationData)) {
        return;
    }
    
    // Submit donation (simulate API call)
    submitDonation(donationData);
}

function validateCampaignData(campaignData) {
    if (!campaignData.title.trim()) {
        showNotification('Please enter a campaign title', 'error');
        return false;
    }
    
    if (!campaignData.category) {
        showNotification('Please select a category', 'error');
        return false;
    }
    
    if (!campaignData.goal || campaignData.goal < 100) {
        showNotification('Goal must be at least Rs. 100', 'error');
        return false;
    }
    
    if (!campaignData.description.trim()) {
        showNotification('Please enter a description', 'error');
        return false;
    }
    
    if (campaignData.title.length < 10) {
        showNotification('Title must be at least 10 characters long', 'error');
        return false;
    }
    
    if (campaignData.description.length < 50) {
        showNotification('Description must be at least 50 characters long', 'error');
        return false;
    }
    
    return true;
}

function validateDonationData(donationData) {
    if (!donationData.amount || donationData.amount < 1) {
        showNotification('Please enter a valid donation amount', 'error');
        return false;
    }
    
    if (!donationData.donorName.trim()) {
        showNotification('Please enter your name', 'error');
        return false;
    }
    
    if (!donationData.donorEmail.trim()) {
        showNotification('Please enter your email', 'error');
        return false;
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(donationData.donorEmail)) {
        showNotification('Please enter a valid email address', 'error');
        return false;
    }
    
    return true;
}

function submitCampaign(campaignData) {
    // Show loading state
    const submitBtn = campaignForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showNotification('Campaign created successfully!', 'success');
        closeCreateCampaignModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Add campaign to campaigns list
        console.log('Campaign data:', campaignData);
        
    }, 2000);
}

function submitDonation(donationData) {
    // Show loading state
    const submitBtn = donationForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showNotification(`Thank you for your $${donationData.amount} donation!`, 'success');
        closeDonateModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Update campaign progress
        console.log('Donation data:', donationData);
        
    }, 2000);
}

function resetCampaignForm() {
    if (campaignForm) {
        campaignForm.reset();
    }
}

function resetDonationForm() {
    if (donationForm) {
        donationForm.reset();
    }
    
    // Reset amount buttons
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.classList.remove('active');
    });
}

// Utility functions
function viewAllCampaigns() {
    // TODO: Navigate to full campaigns page or expand list
    console.log('Viewing all campaigns');
    showNotification('Loading all campaigns...', 'info');
}

// Notification system
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
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        case 'info': return 'info-circle';
        default: return 'info-circle';
    }
}

// Export functions for global access
window.openCreateCampaignModal = openCreateCampaignModal;
window.closeCreateCampaignModal = closeCreateCampaignModal;
window.openDonateModal = openDonateModal;
window.closeDonateModal = closeDonateModal;
window.viewAllCampaigns = viewAllCampaigns;

