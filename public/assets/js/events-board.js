// events-board.js - Events Board Page Functionality

// Global variables
let currentModal = null;
let selectedCategory = 'all';
let currentFilters = {
  categories: ['academic', 'social', 'career', 'workshop'],
  dateRange: 'today',
  timeOfDay: ['morning', 'afternoon', 'evening']
};

// DOM elements
const newEventModal = document.getElementById('newEventModal');
const filterModal = document.getElementById('filterModal');
const newEventForm = document.querySelector('.new-event-form');
const categoryTabs = document.querySelectorAll('.category-tab');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeEventsBoard();
});

function initializeEventsBoard() {
    // Add event listeners
    if (newEventForm) {
        newEventForm.addEventListener('submit', handleNewEventSubmit);
    }
    
    // Add category tab functionality
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const category = tab.dataset.category;
            selectCategory(category);
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
    
    // Set minimum date for event creation to today
    const today = new Date().toISOString().split('T')[0];
    const eventDateInput = document.getElementById('eventDate');
    if (eventDateInput) {
        eventDateInput.min = today;
    }
}

// Modal functions
function openNewEventModal() {
    newEventModal.style.display = 'block';
    currentModal = 'newEvent';
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    setTimeout(() => {
        document.getElementById('eventTitle').focus();
    }, 100);
}

function closeNewEventModal() {
    newEventModal.style.display = 'none';
    currentModal = null;
    document.body.style.overflow = 'auto';
    
    // Reset form
    resetNewEventForm();
}

function openFilterModal() {
    filterModal.style.display = 'block';
    currentModal = 'filter';
    document.body.style.overflow = 'hidden';
    
    // Apply current filters to form
    applyCurrentFiltersToForm();
}

function closeFilterModal() {
    filterModal.style.display = 'none';
    currentModal = null;
    document.body.style.overflow = 'auto';
}

function closeAllModals() {
    if (currentModal === 'newEvent') {
        closeNewEventModal();
    } else if (currentModal === 'filter') {
        closeFilterModal();
    }
}

// Category selection
function selectCategory(category) {
    selectedCategory = category;
    
    // Update active tab
    categoryTabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.category === category) {
            tab.classList.add('active');
        }
    });
    
    // Filter events based on category
    filterEventsByCategory(category);
}

function filterEventsByCategory(category) {
    // TODO: Implement actual filtering logic
    console.log('Filtering events by category:', category);
    
    // For now, just show a notification
    if (category === 'all') {
        showNotification('Showing all events', 'info');
    } else {
        showNotification(`Showing ${category} events`, 'info');
    }
}

// Filter management
function applyCurrentFiltersToForm() {
    // Apply categories
    const categoryCheckboxes = document.querySelectorAll('input[value="academic"], input[value="social"], input[value="career"], input[value="workshop"]');
    categoryCheckboxes.forEach(checkbox => {
        checkbox.checked = currentFilters.categories.includes(checkbox.value);
    });
    
    // Apply date range
    const dateRangeRadios = document.querySelectorAll('input[name="dateRange"]');
    dateRangeRadios.forEach(radio => {
        radio.checked = radio.value === currentFilters.dateRange;
    });
    
    // Apply time of day
    const timeCheckboxes = document.querySelectorAll('input[value="morning"], input[value="afternoon"], input[value="evening"]');
    timeCheckboxes.forEach(checkbox => {
        checkbox.checked = currentFilters.timeOfDay.includes(checkbox.value);
    });
}

function applyFilters() {
    // Get selected categories
    const selectedCategories = [];
    document.querySelectorAll('input[value="academic"], input[value="social"], input[value="career"], input[value="workshop"]:checked').forEach(checkbox => {
        selectedCategories.push(checkbox.value);
    });
    
    // Get selected date range
    const selectedDateRange = document.querySelector('input[name="dateRange"]:checked').value;
    
    // Get selected time of day
    const selectedTimeOfDay = [];
    document.querySelectorAll('input[value="morning"], input[value="afternoon"], input[value="evening"]:checked').forEach(checkbox => {
        selectedTimeOfDay.push(checkbox.value);
    });
    
    // Update current filters
    currentFilters = {
        categories: selectedCategories,
        dateRange: selectedDateRange,
        timeOfDay: selectedTimeOfDay
    };
    
    // Apply filters
    applyFiltersToEvents();
    
    // Close modal
    closeFilterModal();
    
    // Show notification
    showNotification('Filters applied successfully', 'success');
}

function resetFilters() {
    // Reset to default filters
    currentFilters = {
        categories: ['academic', 'social', 'career', 'workshop'],
        dateRange: 'today',
        timeOfDay: ['morning', 'afternoon', 'evening']
    };
    
    // Apply default filters to form
    applyCurrentFiltersToForm();
    
    // Show notification
    showNotification('Filters reset to default', 'info');
}

function applyFiltersToEvents() {
    // TODO: Implement actual filtering logic
    console.log('Applying filters:', currentFilters);
    
    // This would typically involve:
    // 1. Hiding/showing events based on category
    // 2. Filtering by date range
    // 3. Filtering by time of day
    // 4. Updating the UI accordingly
}

// Form handling
function handleNewEventSubmit(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(newEventForm);
    const eventData = {
        title: formData.get('eventTitle'),
        category: formData.get('eventCategory'),
        date: formData.get('eventDate'),
        startTime: formData.get('startTime'),
        endTime: formData.get('endTime'),
        location: formData.get('eventLocation'),
        description: formData.get('eventDescription'),
        maxAttendees: formData.get('maxAttendees'),
        tags: formData.get('eventTags'),
        timestamp: new Date().toISOString()
    };
    
    // Validate form
    if (!validateEventData(eventData)) {
        return;
    }
    
    // Submit event (simulate API call)
    submitEvent(eventData);
}

function validateEventData(eventData) {
    if (!eventData.title.trim()) {
        showNotification('Please enter an event title', 'error');
        return false;
    }
    
    if (!eventData.category) {
        showNotification('Please select a category', 'error');
        return false;
    }
    
    if (!eventData.date) {
        showNotification('Please select an event date', 'error');
        return false;
    }
    
    if (!eventData.startTime) {
        showNotification('Please select a start time', 'error');
        return false;
    }
    
    if (!eventData.endTime) {
        showNotification('Please select an end time', 'error');
        return false;
    }
    
    if (!eventData.location.trim()) {
        showNotification('Please enter an event location', 'error');
        return false;
    }
    
    if (!eventData.description.trim()) {
        showNotification('Please enter an event description', 'error');
        return false;
    }
    
    // Validate time logic
    if (eventData.startTime >= eventData.endTime) {
        showNotification('End time must be after start time', 'error');
        return false;
    }
    
    // Validate date (must be in the future)
    const eventDate = new Date(eventData.date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (eventDate < today) {
        showNotification('Event date must be in the future', 'error');
        return false;
    }
    
    if (eventData.title.length < 10) {
        showNotification('Title must be at least 10 characters long', 'error');
        return false;
    }
    
    if (eventData.description.length < 50) {
        showNotification('Event description must be at least 50 characters long', 'error');
        return false;
    }
    
    return true;
}

function submitEvent(eventData) {
    // Show loading state
    const submitBtn = newEventForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Event...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Success
        showNotification('Event created successfully!', 'success');
        closeNewEventModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Add event to events list
        console.log('Event data:', eventData);
        
    }, 1500);
}

function resetNewEventForm() {
    if (newEventForm) {
        newEventForm.reset();
    }
    
    // Reset date input to today
    const eventDateInput = document.getElementById('eventDate');
    if (eventDateInput) {
        const today = new Date().toISOString().split('T')[0];
        eventDateInput.value = today;
    }
}

// Utility functions
function viewAllFeatured() {
    // TODO: Implement view all featured events
    showNotification('Viewing all featured events', 'info');
}

function viewCalendar() {
    // TODO: Implement calendar view
    showNotification('Switching to calendar view', 'info');
}

function exportEvents() {
    // TODO: Implement event export
    showNotification('Exporting events...', 'info');
}

function viewAllMyEvents() {
    // TODO: Implement view all my events
    showNotification('Viewing all my events', 'info');
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
window.openNewEventModal = openNewEventModal;
window.closeNewEventModal = closeNewEventModal;
window.openFilterModal = openFilterModal;
window.closeFilterModal = closeFilterModal;
window.applyFilters = applyFilters;
window.resetFilters = resetFilters;
window.viewAllFeatured = viewAllFeatured;
window.viewCalendar = viewCalendar;
window.exportEvents = exportEvents;
window.viewAllMyEvents = viewAllMyEvents;

