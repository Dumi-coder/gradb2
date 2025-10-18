<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Board - GradBridge</title>
    <meta name="description" content="Explore alumni and student-organized events. Publish, register, and view event details." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-event-board.css">
    
  </head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Events</h1>
            <p class="header-subtitle">Explore alumni and student-organized events. Publish, register, and view event details.</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/alumni/logout" class="btn btn-primary logout-btn">Logout</a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Quick Actions Section -->
        <section class="dashboard-section quick-actions-section">
          <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
          </div>
          
          <div class="quick-action-item">
            <div class="action-content">
              <div class="action-info">
                <h3 class="action-title">Publish an Event</h3>
                <p class="action-description">Create a new event to connect with fellow alumni and students. Share knowledge, network, and build community.</p>
              </div>
              <button class="btn btn-primary action-btn" onclick="openPublishEventModal()">
                <i class="fas fa-plus"></i>
                Publish Event
              </button>
            </div>
          </div>
        </section>

        <!-- Pending Events Section -->
        <section class="dashboard-section pending-events-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="fas fa-clock" style="color: #3B82F6;"></i>
              Pending Events
            </h2>
            <span class="event-count-badge"><?= $eventData['statistics']['pending_events'] ?> events</span>
          </div>
          
          <div class="featured-events-grid">
            <?php foreach ($eventData['pending_events'] as $event): ?>
            <div class="featured-event-card">
              <div class="event-image">
                <div class="event-date">
                  <span class="day"><?= date('d', strtotime($event['date'])) ?></span>
                  <span class="month"><?= date('M', strtotime($event['date'])) ?></span>
                </div>
                <div class="event-status <?= $event['category'] ?>"><?= ucfirst($event['category']) ?></div>
              </div>
              <div class="event-content">
                <div class="event-category <?= $event['category'] ?>"><?= ucfirst($event['category']) ?></div>
                <h3 class="event-title"><?= esc($event['title']) ?></h3>
                <p class="event-description"><?= esc($event['description']) ?></p>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> <?= esc($event['date']) ?> at <?= esc($event['time']) ?></span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></span>
                </div>
                <div class="event-stats">
                  <span class="attendees"><i class="fas fa-users"></i> 0 registered</span>
                  <span class="spots-left"><i class="fas fa-ticket-alt"></i> 50 spots left</span>
                </div>
                <div class="event-actions">
                  <button type="button" class="btn btn-primary btn-md">
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
            <?php endforeach; ?>
          </div>
        </section>

        <!-- My Registered Events Section -->
        <section class="dashboard-section registered-events-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="fas fa-check-circle" style="color: #3B82F6;"></i>
              My Registered Events
            </h2>
            <span class="event-count-badge"><?= $eventData['statistics']['registered_events'] ?> events</span>
          </div>
          
          <div class="events-container">
            <?php foreach ($eventData['registered_events'] as $event): ?>
            <div class="event-card registered">
              <div class="registered-event-header">
                <div class="registered-event-tags">
                  <span class="badge-<?= $event['type'] ?>-event"><?= ucfirst($event['type']) ?> Event</span>
                  <span class="badge-registered-status">
                    <i class="fas fa-check" style="font-size: var(--font-xs);"></i>
                    Registered
                  </span>
                </div>
              </div>
              
              <div class="event-card-body">
                <h3 class="registered-event-title"><?= esc($event['title']) ?></h3>
                
                <div class="registered-event-details">
                  <div class="registered-event-detail">
                    <i class="fas fa-calendar"></i>
                    <span><?= esc($event['date']) ?> at <?= esc($event['time']) ?></span>
                  </div>
                  <div class="registered-event-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?= esc($event['location']) ?></span>
                  </div>
                  <div class="registered-event-detail">
                    <i class="fas fa-user"></i>
                    <span>Organized by <?= esc($event['organizer']) ?></span>
                  </div>
                </div>
                
                <div class="registered-event-actions">
                  <button class="unregister-btn">
                    <i class="fas fa-times"></i>
                    Unregister
                  </button>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Event Activity Statistics Section -->
        <section class="dashboard-section event-stats-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="fas fa-chart-bar" style="color: #0E2072;"></i>
              Event Activity
            </h2>
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

    <!-- Publish Event Modal -->
    <div id="publishEventModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Publish an Event</h2>
          <button class="modal-close" onclick="closePublishEventModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="publish-event-form">
          <div class="form-group">
            <label for="eventTitle">Event Title *</label>
            <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter a clear, descriptive title for your event" required>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="eventCategory">Category *</label>
              <select id="eventCategory" name="eventCategory" required>
                <option value="">Select a category</option>
                <option value="workshop">Workshop</option>
                <option value="networking">Networking</option>
                <option value="seminar">Seminar</option>
                <option value="social">Social</option>
                <option value="career">Career</option>
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
              <input type="text" id="eventTags" name="eventTags" placeholder="e.g., networking, career, workshop">
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="closePublishEventModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-calendar-plus"></i>
              <span>Publish Event</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script>
    // Publish Event Modal Functions
    function openPublishEventModal() {
      console.log('Opening Publish Event Modal');
      const modal = document.getElementById('publishEventModal');
      modal.style.display = 'block';
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
    
    function closePublishEventModal() {
      console.log('Closing Publish Event Modal');
      const modal = document.getElementById('publishEventModal');
      modal.style.display = 'none';
      modal.classList.remove('show');
      document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('publishEventModal');
      if (event.target === modal) {
        closePublishEventModal();
      }
    }
    
    // Form submission
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.publish-event-form');
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          alert('Event published successfully!');
          closePublishEventModal();
        });
      }
    });
    </script>
  </body>
</html>