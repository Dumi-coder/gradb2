<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Events Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Event Approval Requests</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $eventsData['stats']['total_events'] ?></span>
                        <span class="stat-label">Total Events</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">$<?= number_format($eventsData['stats']['total_budget_requested']) ?></span>
                        <span class="stat-label">Total Budget</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $eventsData['stats']['pending_events'] ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
            </div>
            
            <div class="events-container">
                <?php foreach ($eventsData['events'] as $event): ?>
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-info">
                            <h3 class="event-title"><?= esc($event['title']) ?></h3>
                            <p class="event-organizer">Organized by: <?= esc($event['organizer']) ?></p>
                            <p class="event-type"><?= esc($event['event_type']) ?></p>
                        </div>
                        <div class="event-meta">
                            <div class="budget-info">
                                <span class="budget-amount">$<?= number_format($event['budget_requested']) ?></span>
                                <span class="budget-label">Budget Requested</span>
                            </div>
                            <span class="status-badge status-pending">PENDING</span>
                            <p class="event-date">Submitted: <?= date('M j, Y', strtotime($event['submitted_date'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="event-details">
                        <div class="event-description">
                            <h4>Event Description:</h4>
                            <p><?= esc($event['description']) ?></p>
                        </div>
                        
                        <div class="event-schedule">
                            <div class="schedule-item">
                                <i class="fas fa-calendar"></i>
                                <span><strong>Date:</strong> <?= date('M j, Y', strtotime($event['date'])) ?></span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-clock"></i>
                                <span><strong>Time:</strong> <?= esc($event['time']) ?></span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><strong>Location:</strong> <?= esc($event['location']) ?></span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-users"></i>
                                <span><strong>Expected Attendees:</strong> <?= $event['expected_attendees'] ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn btn-success btn-sm approve-btn" data-event-id="<?= $event['id'] ?>">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        <button class="btn btn-danger btn-sm reject-btn" data-event-id="<?= $event['id'] ?>">
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-event-id="<?= $event['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                        <button class="btn btn-primary btn-sm edit-btn" data-event-id="<?= $event['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Approved Events Section -->
            <div class="approved-events-section">
                <div class="section-header-with-button">
                    <h3 class="subsection-title">Approved Events</h3>
                    <button class="btn btn-primary add-event-btn" onclick="openAddEventModal()">
                        <i class="fas fa-plus"></i>
                        Add New Event
                    </button>
                </div>
                <div class="approved-events-container">
                    <div class="approved-event-card">
                        <div class="event-header">
                            <div class="event-info">
                                <h4 class="event-title">Annual Alumni Networking Event</h4>
                                <p class="event-organizer">Organized by: Alumni Association</p>
                                <p class="event-type">Networking</p>
                            </div>
                            <div class="event-meta">
                                <span class="status-badge status-approved">APPROVED</span>
                                <p class="event-date">Date: March 15, 2024</p>
                            </div>
                        </div>
                        <div class="event-actions">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Add Event Modal -->
<div id="addEventModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Event</h2>
            <button class="modal-close" onclick="closeAddEventModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="event-form" id="eventForm">
            <div class="form-group">
                <label for="eventTitle">Event Title *</label>
                <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter event title" required>
            </div>

            <div class="form-group">
                <label for="eventDescription">Event Description *</label>
                <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Describe the event..." required></textarea>
            </div>

            <div class="form-group">
                <label for="eventType">Event Type *</label>
                <select id="eventType" name="eventType" required>
                    <option value="">Select event type</option>
                    <option value="networking">Networking</option>
                    <option value="workshop">Workshop</option>
                    <option value="seminar">Seminar</option>
                    <option value="conference">Conference</option>
                    <option value="social">Social Event</option>
                    <option value="career">Career Fair</option>
                    <option value="academic">Academic</option>
                    <option value="fundraising">Fundraising</option>
                </select>
            </div>

            <div class="form-group">
                <label for="eventDate">Event Date *</label>
                <input type="date" id="eventDate" name="eventDate" required>
            </div>

            <div class="form-group">
                <label for="eventTime">Event Time *</label>
                <input type="time" id="eventTime" name="eventTime" required>
            </div>

            <div class="form-group">
                <label for="eventLocation">Event Location *</label>
                <input type="text" id="eventLocation" name="eventLocation" placeholder="Enter event location" required>
            </div>

            <div class="form-group">
                <label for="eventOrganizer">Event Organizer *</label>
                <input type="text" id="eventOrganizer" name="eventOrganizer" placeholder="Enter organizer name" required>
            </div>

            <div class="form-group">
                <label for="eventBudget">Budget Required</label>
                <input type="number" id="eventBudget" name="eventBudget" placeholder="Enter budget amount" min="0">
            </div>

            <div class="form-group">
                <label for="eventCapacity">Expected Capacity</label>
                <input type="number" id="eventCapacity" name="eventCapacity" placeholder="Enter expected number of attendees" min="1">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeAddEventModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.events-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.event-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.event-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.event-info h3 {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.25rem;
    font-weight: 600;
}

.event-organizer {
    margin: 0 0 0.5rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.event-type {
    margin: 0;
    color: #0E2072;
    font-weight: 500;
    font-size: 0.9rem;
}

.event-meta {
    text-align: right;
}

.budget-info {
    margin-bottom: 0.5rem;
}

.budget-amount {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #059669;
}

.budget-label {
    font-size: 0.75rem;
    color: #6B7280;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    background-color: #FEF3C7;
    color: #D97706;
}

.event-date {
    margin: 0;
    color: #6B7280;
    font-size: 0.8rem;
}

.event-details {
    margin-bottom: 1.5rem;
}

.event-description {
    margin-bottom: 1rem;
}

.event-description h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.event-description p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.event-schedule {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 0.75rem;
}

.schedule-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4B5563;
    font-size: 0.9rem;
}

.schedule-item i {
    color: #0E2072;
    width: 16px;
}

.event-actions {
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

/* Approved Events Section */
.approved-events-section {
    margin-top: 3rem;
    margin-bottom: 2rem;
}

.section-header-with-button {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.subsection-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
}

.add-event-btn {
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

.add-event-btn:hover {
    background-color: #333333;
    transform: translateY(-1px);
}

.approved-events-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.approved-event-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.approved-event-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-approved {
    background-color: #D1FAE5;
    color: #065F46;
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

.event-form {
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
            const eventId = this.getAttribute('data-event-id');
            if (confirm('Are you sure you want to approve this event?')) {
                alert('Event approved successfully!');
                this.closest('.event-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            if (confirm('Are you sure you want to reject this event?')) {
                alert('Event rejected.');
                this.closest('.event-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            alert('View details functionality would be implemented here for event ID: ' + eventId);
        });
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            alert('Edit functionality would be implemented here for event ID: ' + eventId);
        });
    });
});

// Modal Functions
function openAddEventModal() {
    document.getElementById('addEventModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddEventModal() {
    document.getElementById('addEventModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('eventForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addEventModal');
    if (event.target === modal) {
        closeAddEventModal();
    }
}

// Handle form submission
document.getElementById('eventForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const eventData = {
        title: formData.get('eventTitle'),
        description: formData.get('eventDescription'),
        type: formData.get('eventType'),
        date: formData.get('eventDate'),
        time: formData.get('eventTime'),
        location: formData.get('eventLocation'),
        organizer: formData.get('eventOrganizer'),
        budget: formData.get('eventBudget'),
        capacity: formData.get('eventCapacity')
    };
    
    // Here you would typically send the data to the server
    console.log('Event Data:', eventData);
    
    // Show success message
    alert('Event created successfully!');
    
    // Close modal
    closeAddEventModal();
    
    // Here you would typically refresh the events list
    // or add the new event to the page dynamically
});
</script>
