// faq.js - FAQ Page Functionality

// Global variables
let currentCategory = 'all';
let searchTimeout = null;

// DOM elements
const faqSearch = document.getElementById('faqSearch');
const categoryBtns = document.querySelectorAll('.category-btn');
const faqItems = document.querySelectorAll('.faq-item');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeFAQ();
});

function initializeFAQ() {
    // Add event listeners
    if (faqSearch) {
        faqSearch.addEventListener('input', handleSearch);
    }
    
    // Category button handlers
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            selectCategory(this);
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
}

// Search functionality
function handleSearch(event) {
    const query = event.target.value.toLowerCase().trim();
    
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    // Debounce search
    searchTimeout = setTimeout(() => {
        performSearch(query);
    }, 300);
}

function performSearch(query) {
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
        const category = item.getAttribute('data-category');
        
        const matchesSearch = query === '' || question.includes(query) || answer.includes(query);
        const matchesCategory = currentCategory === 'all' || category === currentCategory;
        
        if (matchesSearch && matchesCategory) {
            item.classList.remove('hidden');
            if (query !== '') {
                item.classList.add('highlighted');
                // Auto-expand if search term is found
                if (!item.classList.contains('active')) {
                    toggleFAQ(item.querySelector('.faq-question'));
                }
            } else {
                item.classList.remove('highlighted');
            }
        } else {
            item.classList.add('hidden');
            item.classList.remove('highlighted');
        }
    });
    
    // Update results count
    updateSearchResults(query);
}

function updateSearchResults(query) {
    const visibleItems = document.querySelectorAll('.faq-item:not(.hidden)');
    const resultsText = query ? `Found ${visibleItems.length} result(s) for "${query}"` : '';
    
    // You could add a results counter element here if needed
    console.log(resultsText);
}

// Category filtering
function selectCategory(button) {
    // Remove active class from all buttons
    categoryBtns.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    button.classList.add('active');
    
    // Update current category
    currentCategory = button.getAttribute('data-category');
    
    // Filter FAQ items
    filterByCategory(currentCategory);
    
    // Clear search if category is selected
    if (currentCategory !== 'all') {
        faqSearch.value = '';
        performSearch('');
    }
}

function filterByCategory(category) {
    faqItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        
        if (category === 'all' || itemCategory === category) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}

// FAQ toggle functionality
function toggleFAQ(questionElement) {
    const faqItem = questionElement.closest('.faq-item');
    const isActive = faqItem.classList.contains('active');
    
    // Close all other FAQ items
    faqItems.forEach(item => {
        if (item !== faqItem) {
            item.classList.remove('active');
        }
    });
    
    // Toggle current item
    faqItem.classList.toggle('active', !isActive);
    
    // Smooth scroll to item if it was just opened
    if (!isActive) {
        setTimeout(() => {
            faqItem.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }, 100);
    }
}

// Keyboard navigation
function handleKeyboardNavigation(event) {
    // Handle Enter key on FAQ questions
    if (event.key === 'Enter' && event.target.classList.contains('faq-question')) {
        event.preventDefault();
        toggleFAQ(event.target);
    }
    
    // Handle Escape key to close all FAQs
    if (event.key === 'Escape') {
        faqItems.forEach(item => {
            item.classList.remove('active');
        });
    }
    
    // Handle arrow keys for navigation
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        const activeElement = document.activeElement;
        const faqQuestions = Array.from(document.querySelectorAll('.faq-question'));
        const currentIndex = faqQuestions.indexOf(activeElement);
        
        if (currentIndex !== -1) {
            event.preventDefault();
            let nextIndex;
            
            if (event.key === 'ArrowDown') {
                nextIndex = Math.min(currentIndex + 1, faqQuestions.length - 1);
            } else {
                nextIndex = Math.max(currentIndex - 1, 0);
            }
            
            faqQuestions[nextIndex].focus();
        }
    }
}

// Live chat functionality
function openLiveChat() {
    // This would typically open a chat widget or redirect to a chat service
    showNotification('Live chat feature coming soon!', 'info');
    
    // For now, just show a placeholder
    console.log('Opening live chat...');
}

// Utility functions
function clearSearch() {
    faqSearch.value = '';
    performSearch('');
    
    // Remove highlights
    faqItems.forEach(item => {
        item.classList.remove('highlighted');
    });
}

function expandAllFAQs() {
    faqItems.forEach(item => {
        item.classList.add('active');
    });
}

function collapseAllFAQs() {
    faqItems.forEach(item => {
        item.classList.remove('active');
    });
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

// Analytics and tracking (optional)
function trackFAQInteraction(action, details) {
    // This would typically send data to an analytics service
    console.log('FAQ Interaction:', action, details);
}

// Export functions for global access
window.toggleFAQ = toggleFAQ;
window.openLiveChat = openLiveChat;
window.clearSearch = clearSearch;
window.expandAllFAQs = expandAllFAQs;
window.collapseAllFAQs = collapseAllFAQs;
