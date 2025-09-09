<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events Board - GradBridge</title>
    <meta name="description" content="Discover and join campus events, workshops, and social activities." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/events-board.css">
  </head>

  <body>
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Events Board</h1>
            <p class="student-role">Discover & Join Campus Activities</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
           <a href="<?=ROOT?>/student/Logout"><button class="btn btn-primary logout-btn">Logout</button></a>

          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Events Header Section -->
        <section class="dashboard-section events-header-section">
          <div class="section-header">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="header-actions">
              <button class="btn btn-outline btn-md" onclick="openFilterModal()">
                <i class="fas fa-filter"></i>
                <span>Filter Events</span>
              </button>
              <button class="btn btn-primary btn-md" onclick="openNewEventModal()">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
              </button>
            </div>
          </div>
          
          <!-- Event Categories -->
          <div class="event-categories">
            <div class="category-tab active" data-category="all">
              <i class="fas fa-calendar"></i>
              <span>All Events</span>
            </div>
            <div class="category-tab" data-category="academic">
              <i class="fas fa-graduation-cap"></i>
              <span>Academic</span>
            </div>
            <div class="category-tab" data-category="social">
              <i class="fas fa-users"></i>
              <span>Social</span>
            </div>
            <div class="category-tab" data-category="career">
              <i class="fas fa-briefcase"></i>
              <span>Career</span>
            </div>
            <div class="category-tab" data-category="workshop">
              <i class="fas fa-tools"></i>
              <span>Workshops</span>
            </div>
          </div>
        </section>

        <!-- Featured Events Section -->
        <section class="dashboard-section featured-events-section">
          <div class="section-header">
            <h2 class="section-title">Featured Events</h2>
            <button class="btn btn-outline btn-sm" onclick="viewAllFeatured()">
              <span>View All</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </div>
          
          <div class="featured-events-grid">
            <div class="featured-event-card">
              <div class="event-image">
                <div class="event-date">
                  <span class="day">15</span>
                  <span class="month">Dec</span>
                </div>
                <div class="event-status featured">Featured</div>
              </div>
              <div class="event-content">
                <div class="event-category academic">Academic</div>
                <h3 class="event-title">Computer Science Career Fair 2024</h3>
                <p class="event-description">Connect with top tech companies and discover internship opportunities in software development, AI, and data science.</p>
                <div class="event-meta">
                  <span class="event-time"><i class="fas fa-clock"></i> 10:00 AM - 4:00 PM</span>
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> Main Campus Hall</span>
                </div>
                <div class="event-stats">
                  <span class="attendees"><i class="fas fa-users"></i> 156 registered</span>
                  <span class="spots-left"><i class="fas fa-ticket-alt"></i> 44 spots left</span>
                </div>
                <div class="event-actions">
                  <button class="btn btn-primary btn-sm">
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
                <div class="event-date">
                  <span class="day">18</span>
                  <span class="month">Dec</span>
                </div>
                <div class="event-status workshop">Workshop</div>
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
                  <span class="attendees"><i class="fas fa-users"></i> 89 registered</span>
                  <span class="spots-left"><i class="fas fa-ticket-alt"></i> 11 spots left</span>
                </div>
                <div class="event-actions">
                  <button class="btn btn-primary btn-sm">
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

        <!-- Upcoming Events Section -->
        <section class="dashboard-section upcoming-events-section">
          <div class="section-header">
            <h2 class="section-title">This Week's Events</h2>
            <div class="section-actions">
              <button class="btn btn-outline btn-sm" onclick="viewCalendar()">
                <i class="fas fa-calendar-alt"></i>
                <span>Calendar View</span>
              </button>
              <button class="btn btn-outline btn-sm" onclick="exportEvents()">
                <i class="fas fa-download"></i>
                <span>Export</span>
              </button>
            </div>
          </div>
          
          <div class="events-timeline">
            <div class="timeline-day">
              <div class="day-header">
                <h3 class="day-title">Monday, December 16</h3>
                <span class="event-count">3 events</span>
              </div>
              
              <div class="day-events">
                <div class="timeline-event">
                  <div class="event-time">09:00 AM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Study Group: Algorithms</h4>
                      <span class="event-category academic">Academic</span>
                    </div>
                    <p class="event-description">Weekly study session focusing on algorithm design and problem-solving techniques.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Library Study Room 3</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 2 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Join Group</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">02:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Resume Writing Workshop</h4>
                      <span class="event-category career">Career</span>
                    </div>
                    <p class="event-description">Learn how to create a compelling resume that stands out to employers.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Career Center</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 1.5 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Register</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">06:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Movie Night: The Social Network</h4>
                      <span class="event-category social">Social</span>
                    </div>
                    <p class="event-description">Movie screening followed by discussion about entrepreneurship and tech innovation.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Student Lounge</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 3 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Join Event</button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="timeline-day">
              <div class="day-header">
                <h3 class="day-title">Tuesday, December 17</h3>
                <span class="event-count">2 events</span>
              </div>
              
              <div class="day-events">
                <div class="timeline-event">
                  <div class="event-time">10:00 AM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Python Programming Workshop</h4>
                      <span class="event-category workshop">Workshop</span>
                    </div>
                    <p class="event-description">Learn Python basics and build your first web application.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Computer Lab B</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 3 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Register</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">04:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Networking Mixer</h4>
                      <span class="event-category career">Career</span>
                    </div>
                    <p class="event-description">Connect with alumni and industry professionals in a relaxed setting.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Alumni Hall</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 2 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">RSVP</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Event Statistics Section -->
        <section class="dashboard-section event-stats-section">
          <div class="section-header">
            <h2 class="section-title">Event Activity</h2>
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
                <i class="fas fa-star"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number">4.8</h3>
                <p class="stat-label">Average Rating</p>
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

        <!-- My Events Section -->
        <section class="dashboard-section my-events-section">
          <div class="section-header">
            <h2 class="section-title">My Events</h2>
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
                  <span class="event-location"><i class="fas fa-map-marker-alt"></i> Main Campus Hall</span>
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
                  <button class="btn btn-primary btn-sm">
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
            <button type="button" class="btn btn-outline btn-md" onclick="closeNewEventModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-calendar-plus"></i>
              <span>Create Event</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Filter Events Modal -->
    <div id="filterModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Filter Events</h2>
          <button class="modal-close" onclick="closeFilterModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="filter-content">
          <div class="filter-section">
            <h3>Categories</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" value="academic" checked>
                <span>Academic</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="social" checked>
                <span>Social</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="career" checked>
                <span>Career</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="workshop" checked>
                <span>Workshops</span>
              </label>
            </div>
          </div>
          
          <div class="filter-section">
            <h3>Date Range</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="radio" name="dateRange" value="today" checked>
                <span>Today</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="week">
                <span>This Week</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="month">
                <span>This Month</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="custom">
                <span>Custom Range</span>
              </label>
            </div>
          </div>
          
          <div class="filter-section">
            <h3>Time of Day</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" value="morning" checked>
                <span>Morning (6 AM - 12 PM)</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="afternoon" checked>
                <span>Afternoon (12 PM - 6 PM)</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="evening" checked>
                <span>Evening (6 PM - 12 AM)</span>
              </label>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button class="btn btn-outline btn-md" onclick="resetFilters()">
            <span>Reset Filters</span>
          </button>
          <button class="btn btn-primary btn-md" onclick="applyFilters()">
            <span>Apply Filters</span>
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/events-board.js"></script>
  </body>
</html>

