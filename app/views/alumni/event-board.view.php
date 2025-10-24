<?php 
$page_title = "Events Board";
$page_subtitle = "Discover & Join Campus Activities";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/events-board.css">

<div class="dashboard-container">
      
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/alumni_sidebar.php'; ?>

       <!-- Main Content Area -->
      <main class="main-content">
        <!-- Events Header Section -->
        <section class="dashboard-section events-header-section">
          <div class="section-header">
            <h2 class="card-title">Upcoming Events</h2>
            <div class="header-actions">
              <button class="btn btn-primary" onclick="openNewEventModal()">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
              </button>
              <button class="btn btn-outline btn-sm" onclick="viewAllFeatured()">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </section>

        <!-- Featured Events Section -->
        <section class="dashboard-section featured-events-section">
          <div class="featured-events-grid">
            <div class="featured-event-card">
              <div class="event-image">
                <!-- Empty for demo - plain background only -->
              </div>
              <div class="event-content">
                <div class="event-category academic">Academic</div>
                <h3 class="event-title">Computer Science Career Fair 2024</h3>
                <p class="event-description">Connect with top tech companies and discover internship opportunities in software development, AI, and data science.</p>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> 10:00 AM - 4:00 PM</span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> E401 Hall</span>
                </div>
                <div class="event-stats">
                  <span class="attendees"><i class="fas fa-users"></i> 56 registered</span>
                  <span class="spots-left"><i class="fas fa-ticket-alt"></i> 10 spots left</span>
                </div>
                <div class="event-actions">
                  <button type="button" class="btn btn-primary btn-sm" onclick="openRegisterModal('Computer Science Career Fair 2024')">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Register Now</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-heart"></i>
                    <span>Save</span>
                  </button>
                </div>
              </div>
            </div>
            
            <div class="featured-event-card">
              <div class="event-image">
                <!-- Empty for demo - plain background only -->
              </div>
              <div class="event-content">
                <div class="event-category workshop">Workshop</div>
                <h3 class="event-title">Machine Learning Bootcamp</h3>
                <p class="event-description">Hands-on workshop covering Python, TensorFlow, and real-world ML applications. Perfect for beginners!</p>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> 2:00 PM - 6:00 PM</span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> Computer Lab A</span>
                </div>
                <div class="event-stats">
                  <span class="attendees"><i class="fas fa-users"></i> 10 registered</span>
                  <span class="spots-left"><i class="fas fa-ticket-alt"></i> 5 spots left</span>
                </div>
                <div class="event-actions">
                  <button type="button" class="btn btn-primary btn-sm" onclick="openRegisterModal('Machine Learning Bootcamp')">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Register Now</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-heart"></i>
                    <span>Save</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Events I'm Hosting Section -->
        <section class="dashboard-section hosting-events-section">
          <div class="section-header">
            <h2 class="card-title">Events I'm Hosting</h2>
            <button class="btn btn-outline btn-sm" onclick="viewAllHostingEvents()">
              <span>Manage All</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </div>
          
          <div class="hosting-events-grid">
            <!-- Example Event with Open Registrations -->
            <div class="hosting-event-card">
              <div class="event-header">
                <h3 class="event-title">Tech Career Mentorship Session</h3>
                <span class="status-badge status-active">Registrations Open</span>
              </div>
              <div class="event-meta">
                <span class="event-time"><i class="fas fa-clock"></i> Dec 20, 3:00 PM</span>
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Main Hall</span>
              </div>
              <div class="event-stats">
                <span class="registrants"><i class="fas fa-users"></i> 45 registered</span>
                <span class="capacity"><i class="fas fa-ticket-alt"></i> 50 capacity</span>
              </div>
              <div class="event-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-edit"></i> Edit Details
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmCloseRegistrations(1)">
                  <i class="fas fa-lock"></i> Close Registrations
                </button>
              </div>
            </div>

            <!-- Example Event with Closed Registrations -->
            <div class="hosting-event-card">
              <div class="event-header">
                <h3 class="event-title">Alumni Networking Night</h3>
                <span class="status-badge status-closed">Registrations Closed</span>
              </div>
              <div class="event-meta">
                <span class="event-time"><i class="fas fa-clock"></i> Dec 18, 6:00 PM</span>
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Conference Room B</span>
              </div>
              <div class="event-stats">
                <span class="registrants"><i class="fas fa-users"></i> 30 registered</span>
                <span class="capacity"><i class="fas fa-ticket-alt"></i> 30 capacity</span>
              </div>
              <div class="event-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-edit"></i> Edit Details
                </button>
                <button class="btn btn-success btn-sm" onclick="confirmOpenRegistrations(2)">
                  <i class="fas fa-unlock"></i> Reopen Registrations
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- My Registered Events Section -->
        <section class="dashboard-section my-events-section">
          <div class="section-header">
            <h2 class="card-title">Events I'm Attending</h2>
            <button class="btn btn-outline btn-sm" onclick="viewAllMyEvents()">
              <span>View All</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </div>
          
          <div class="my-events-grid">
            <div class="my-event-card">
              <div class="event-status registered">Registered</div>
              <div class="event-content">
                <h3 class="event-title">Computer Science Career Fair 2024</h3>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> Dec 15, 10:00 AM</span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> E401 Hall</span>
                </div>
                <div class="event-actions">
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Add to Calendar</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                  </button>
                </div>
              </div>
            </div>
            
            <div class="my-event-card">
              <div class="event-status saved">Saved</div>
              <div class="event-content">
                <h3 class="event-title">Machine Learning Bootcamp</h3>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> Dec 18, 2:00 PM</span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> Computer Lab A</span>
                </div>
                <div class="event-actions">
                <button type="button" class="btn btn-primary btn-sm" onclick="openRegisterModal('Machine Learning Bootcamp')">
                  <i class="fas fa-calendar-plus"></i>
                  <span>Register Now</span>
                </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-heart-broken"></i>
                    <span>Remove</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Event Statistics Section -->
        <section class="dashboard-section event-stats-section">
          <div class="section-header">
            <h2 class="card-title">Event Activity</h2>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">24</h3>
                <p class="stat-label">Events This Month</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">1,247</h3>
                <p class="stat-label">Total Registrations</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">8</h3>
                <p class="stat-label">Upcoming This Week</p>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- New Event Modal -->
    <div id="newEventModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Event</h2>
          <button class="modal-close" onclick="closeNewEventModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="new-event-form">
          <div class="form-group">
            <label for="eventTitle">Event Title *</label>
            <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter a clear, descriptive title for your event" required>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="eventCategory">Category *</label>
              <select id="eventCategory" name="eventCategory" required>
                <option value="">Select a category</option>
                <option value="academic">Academic</option>
                <option value="social">Social</option>
                <option value="career">Career</option>
                <option value="workshop">Workshop</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="eventDate">Event Date *</label>
              <input type="date" id="eventDate" name="eventDate" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="startTime">Start Time *</label>
              <input type="time" id="startTime" name="startTime" required>
            </div>
            
            <div class="form-group">
              <label for="endTime">End Time *</label>
              <input type="time" id="endTime" name="endTime" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="eventLocation">Location *</label>
            <input type="text" id="eventLocation" name="eventLocation" placeholder="Enter event location" required>
          </div>
          
          <div class="form-group">
            <label for="eventDescription">Event Description *</label>
            <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Describe your event in detail. What will attendees learn or experience?" required></textarea>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="maxAttendees">Maximum Attendees</label>
              <input type="number" id="maxAttendees" name="maxAttendees" placeholder="Leave empty for unlimited" min="1">
            </div>
            
            <div class="form-group">
              <label for="eventTags">Tags</label>
              <input type="text" id="eventTags" name="eventTags" placeholder="e.g., python, networking, career">
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeNewEventModal()">
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

    <!-- Register Now Modal -->
    <div id="registerModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Register for the Event</h2>
          <button class="modal-close" onclick="closeRegisterModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="new-event-form">
          <div class="form-row">
            <div class="form-group">
          <label for="participantName">Full Name *</label>
          <input type="text" id="participantName" name="participantName" placeholder="Enter your full name" required>
          </div>

          <div class="form-group">
          <label for="participantEmail">Email *</label>
          <input type="email" id="participantEmail" name="participantEmail" placeholder="Enter your email" required>
          </div>

          <div class="form-group">
            <label for="participantRole">Role *</label>
            <select id="participantRole" name="participantRole" required>
              <option value="">Select your role</option>
              <option value="student">Student</option>
              <option value="alumni">Alumni</option>
              <option value="guest">Guest</option>
            </select>
          </div>

          <div class="form-group">
            <label for="eventName">Event Name *</label>
            <input type="text" id="eventName" name="eventName" placeholder="Event name will appear here" readonly>
          </div>

          <div class="form-group">
            <label for="specialNotes">Special Notes</label>
            <textarea id="specialNotes" name="specialNotes" rows="3" placeholder="Any specific requests or comments?"></textarea>
          </div>

          <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="closeRegisterModal()">
            <span>Cancel</span>
          </button>
          <button type="submit" class="btn btn-primary">
            <span>Register Now</span>
          </button>
          </div>
        </form>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/events-board.js"></script>
  </body>
</html>
