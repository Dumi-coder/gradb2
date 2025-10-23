<?php 
$page_title = "Event Board";
$page_subtitle = "Explore and organize alumni events";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">

<style>
    /* Event Board Specific Styles - Match Student Events */
    .featured-events-grid {
      display: grid !important;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)) !important;
      gap: 24px !important;
    }
    
    .featured-event-card {
      background-color: white !important;
      border: 1px solid #E5E7EB !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      transition: all 0.3s ease !important;
      cursor: pointer !important;
    }
    
    .featured-event-card:hover {
      border-color: #0E2072 !important;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
      transform: translateY(-4px) !important;
    }
    
    .event-image {
      height: 200px !important;
      background: linear-gradient(135deg, #0E2072 0%, #7C3AED 100%) !important;
      position: relative !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      color: white !important;
    }
    
    .event-date {
      text-align: center !important;
      background-color: rgba(255, 255, 255, 0.2) !important;
      padding: 16px !important;
      border-radius: 12px !important;
      backdrop-filter: blur(10px) !important;
    }
    
    .event-date .day {
      display: block !important;
      font-size: 2rem !important;
      font-weight: 700 !important;
      line-height: 1 !important;
    }
    
    .event-date .month {
      display: block !important;
      font-size: 14px !important;
      font-weight: 500 !important;
      text-transform: uppercase !important;
      letter-spacing: 1px !important;
    }
    
    .event-status {
      position: absolute !important;
      top: 16px !important;
      right: 16px !important;
      padding: 6px 12px !important;
      border-radius: 20px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
    }
    
    .event-status.featured {
      background-color: #f59e0b !important;
      color: white !important;
    }
    
    .event-status.workshop {
      background-color: #10b981 !important;
      color: white !important;
    }
    
    .event-content {
      padding: 24px !important;
    }
    
    .event-category {
      display: inline-block !important;
      padding: 4px 8px !important;
      border-radius: 20px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      margin-bottom: 12px !important;
    }
    
    .event-category.workshop {
      background-color: #10b981 !important;
      color: white !important;
    }
    
    .event-category.bootcamp {
      background-color: #8b5cf6 !important;
      color: white !important;
    }
    
    .event-title {
      font-size: 18px !important;
      font-weight: 600 !important;
      color: #1F2937 !important;
      margin: 0 0 12px 0 !important;
      line-height: 1.3 !important;
    }
    
    .event-description {
      color: #6B7280 !important;
      margin: 0 0 16px 0 !important;
      line-height: 1.5 !important;
    }
    
    .event-meta {
      display: flex !important;
      flex-direction: column !important;
      gap: 8px !important;
      margin-bottom: 16px !important;
    }
    
    .event-meta span {
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      font-size: 14px !important;
      color: #6B7280 !important;
    }
    
    .event-meta i {
      color: #0E2072 !important;
      width: 16px !important;
    }
    
    .event-stats {
      display: flex !important;
      gap: 16px !important;
      margin-bottom: 16px !important;
      font-size: 14px !important;
      color: #6B7280 !important;
    }
    
    .event-stats span {
      display: flex !important;
      align-items: center !important;
      gap: 6px !important;
    }
    
    .event-stats i {
      color: #0E2072 !important;
    }
    
    .event-actions {
      display: flex !important;
      gap: 12px !important;
    }
    
    .event-card.registered {
      background-color: white !important;
      border: 1px solid #E5E7EB !important;
    }
    
    .registered-event-header {
      display: flex !important;
      align-items: center !important;
      gap: 12px !important;
      margin-bottom: 16px !important;
      padding-bottom: 16px !important;
      border-bottom: 1px solid #FEE5A0 !important;
    }
    
    .registered-event-tags {
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
    }
    
    .badge-student-event {
      background-color: #E0F7ED !important;
      color: #28A745 !important;
      padding: 6px 12px !important;
      border-radius: 20px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
    }
    
    .badge-alumni-event {
      background-color: #E0F0FF !important;
      color: #007BFF !important;
      padding: 6px 12px !important;
      border-radius: 20px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
    }
    
    .badge-registered-status {
      background-color: #FFD700 !important;
      color: #333333 !important;
      padding: 6px 12px !important;
      border-radius: 20px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      display: flex !important;
      align-items: center !important;
      gap: 4px !important;
    }
    
    .registered-event-title {
      font-size: 20px !important;
      font-weight: 700 !important;
      color: #333333 !important;
      margin: 0 !important;
      line-height: 1.3 !important;
    }
    
    .registered-event-details {
      display: flex !important;
      flex-direction: column !important;
      gap: 8px !important;
      margin-bottom: 20px !important;
      padding-bottom: 16px !important;
      border-bottom: 1px solid #FEE5A0 !important;
    }
    
    .registered-event-detail {
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      font-size: 14px !important;
      color: #6C757D !important;
    }
    
    .registered-event-detail i {
      font-size: 14px !important;
      width: 16px !important;
      text-align: center !important;
    }
    
    .registered-event-actions {
      display: flex !important;
      gap: 12px !important;
    }
    
    .unregister-btn {
      background-color: #000000 !important;
      color: white !important;
      border: none !important;
      border-radius: 8px !important;
      padding: 10px 16px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
      display: flex !important;
      align-items: center !important;
      gap: 6px !important;
      transition: all 0.2s ease !important;
    }
    
    .unregister-btn:hover {
      background-color: #333333 !important;
    }
    
    /* Event Activity Statistics Section */
    .event-stats-section {
      border: 2px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      background-color: white !important;
      margin-top: 20px !important;
      margin-bottom: 30px !important;
    }
    
    .stats-grid {
      display: grid !important;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
      gap: 24px !important;
    }
    
    .stat-card {
      background-color: white !important;
      border: 1px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      display: flex !important;
      align-items: center !important;
      gap: 16px !important;
      transition: all 0.3s ease !important;
    }
    
    .stat-card:hover {
      border-color: #0E2072 !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
      transform: translateY(-2px) !important;
    }
    
    .stat-icon {
      width: 50px !important;
      height: 50px !important;
      background-color: #0E2072 !important;
      border-radius: 8px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      color: white !important;
      font-size: 20px !important;
    }
    
    .stat-content {
      flex: 1 !important;
    }
    
    .stat-number {
      font-size: 28px !important;
      font-weight: 700 !important;
      color: #0E2072 !important;
      margin: 0 0 4px 0 !important;
      line-height: 1 !important;
    }
    
    .stat-label {
      color: #6B7280 !important;
      font-size: 14px !important;
      margin: 0 !important;
      font-weight: 500 !important;
    }
    
    .event-header {
      margin-bottom: 20px !important;
    }
    
    .event-type-badge {
      display: inline-block !important;
      padding: 6px 12px !important;
      border-radius: 20px !important;
      font-size: 11px !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      margin-bottom: 12px !important;
    }
    
    .event-title {
      font-size: 22px !important;
      font-weight: 800 !important;
      color: #1F2937 !important;
      margin: 0 0 12px 0 !important;
      line-height: 1.2 !important;
    }
    
    .event-description {
      font-size: 15px !important;
      color: #6B7280 !important;
      line-height: 1.5 !important;
      margin: 0 0 20px 0 !important;
    }
    
    .event-details {
      display: flex !important;
      flex-direction: column !important;
      gap: 12px !important;
      margin-bottom: 24px !important;
    }
    
    .event-detail {
      display: flex !important;
      align-items: center !important;
      gap: 10px !important;
      font-size: 14px !important;
      color: #6B7280 !important;
    }
    
    .event-detail i {
      font-size: 16px !important;
      width: 16px !important;
      text-align: center !important;
    }
    
    .event-detail strong {
      color: #1F2937 !important;
      font-weight: 600 !important;
    }
    
    .event-actions {
      display: flex !important;
      gap: 16px !important;
    }
    
    .btn-primary {
      background-color: #000000 !important;
      color: white !important;
      border: none !important;
      border-radius: 12px !important;
      padding: 12px 20px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
    }
    
    .btn-primary:hover {
      background-color: #333333 !important;
      transform: translateY(-1px) !important;
    }
    
    .btn-outline {
      background-color: white !important;
      color: #000000 !important;
      border: 2px solid #000000 !important;
      border-radius: 12px !important;
      padding: 12px 20px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
    }
    
    .btn-outline:hover {
      background-color: #000000 !important;
      color: white !important;
    }
    
    .event-type-badge {
      padding: 4px 8px !important;
      border-radius: 6px !important;
      font-size: 12px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
    }
    
    .badge-alumni {
      background-color: #E3F2FD !important;
      color: #1976D2 !important;
    }
    
    .badge-student {
      background-color: #E8F5E8 !important;
      color: #2E7D32 !important;
    }
    
    .badge-registered {
      background-color: #FEF3C7 !important;
      color: #D97706 !important;
    }
    
    .badge-pending {
      background-color: #FEF3C7 !important;
      color: #D97706 !important;
    }
    
    .section-header {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      margin-bottom: 20px !important;
    }
    
    .section-title {
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      font-size: 20px !important;
      font-weight: 700 !important;
      color: #1F2937 !important;
      margin: 0 !important;
    }
    
    .event-count-badge {
      background-color: #F3F4F6 !important;
      color: #374151 !important;
      padding: 4px 12px !important;
      border-radius: 20px !important;
      font-size: 14px !important;
      font-weight: 500 !important;
    }
    
    .quick-actions-section {
      border: 2px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      background-color: white !important;
      margin-top: 20px !important;
      margin-bottom: 30px !important;
    }
    
    .pending-events-section {
      border: 2px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      background-color: white !important;
      margin-top: 20px !important;
      margin-bottom: 30px !important;
    }
    
    .registered-events-section {
      border: 2px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      background-color: white !important;
      margin-top: 20px !important;
      margin-bottom: 30px !important;
    }
    
    .events-container {
      display: flex !important;
      flex-direction: column !important;
      gap: 20px !important;
    }
    
    .quick-action-item {
      background-color: white !important;
      border: 1px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      margin-bottom: 20px !important;
    }
    
    .action-content {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      gap: 24px !important;
    }
    
    .action-info {
      flex: 1 !important;
    }
    
    .action-title {
      font-size: 18px !important;
      font-weight: 700 !important;
      color: #1F2937 !important;
      margin: 0 0 8px 0 !important;
    }
    
    .action-description {
      font-size: 14px !important;
      color: #6B7280 !important;
      margin: 0 !important;
      line-height: 1.5 !important;
    }
    
    .action-btn {
      white-space: nowrap !important;
      font-weight: 600 !important;
      padding: 12px 20px !important;
      background-color: #000000 !important;
      color: white !important;
      border: none !important;
      border-radius: 8px !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
    }
    
    .action-btn:hover {
      background-color: #333333 !important;
      transform: translateY(-1px) !important;
    }
    
    /* Publish Event Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }
    
    .modal.show {
      display: block !important;
    }
    
    .modal-content {
      background-color: white !important;
      margin: 5% auto !important;
      padding: 0 !important;
      border-radius: 12px !important;
      width: 90% !important;
      max-width: 600px !important;
      max-height: 90vh !important;
      overflow-y: auto !important;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
      animation: modalSlideIn 0.3s ease-out !important;
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
      padding: 24px !important;
      border-bottom: 1px solid #E5E7EB !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
    }
    
    .modal-title {
      font-size: 20px !important;
      font-weight: 600 !important;
      color: #1F2937 !important;
      margin: 0 !important;
    }
    
    .modal-close {
      background: none !important;
      border: none !important;
      font-size: 18px !important;
      color: #6B7280 !important;
      cursor: pointer !important;
      padding: 4px !important;
      border-radius: 4px !important;
      transition: all 0.2s ease !important;
    }
    
    .modal-close:hover {
      background-color: #F3F4F6 !important;
      color: #1F2937 !important;
    }
    
    .publish-event-form {
      padding: 24px !important;
    }
    
    .form-group {
      margin-bottom: 20px !important;
    }
    
    .form-row {
      display: grid !important;
      grid-template-columns: 1fr 1fr !important;
      gap: 16px !important;
    }
    
    .form-group label {
      display: block !important;
      font-weight: 500 !important;
      color: #1F2937 !important;
      margin-bottom: 8px !important;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100% !important;
      padding: 12px !important;
      border: 1px solid #D1D5DB !important;
      border-radius: 8px !important;
      font-size: 14px !important;
      background-color: white !important;
      color: #1F2937 !important;
      transition: border-color 0.2s ease !important;
      box-sizing: border-box !important;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none !important;
      border-color: #0E2072 !important;
      box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1) !important;
    }
    
    .form-group textarea {
      resize: vertical !important;
      min-height: 100px !important;
    }
    
    .form-actions {
      display: flex !important;
      gap: 12px !important;
      justify-content: flex-end !important;
      margin-top: 24px !important;
      padding-top: 20px !important;
      border-top: 1px solid #E5E7EB !important;
    }
    
    .btn-outline {
      background-color: white !important;
      color: #374151 !important;
      border: 1px solid #D1D5DB !important;
      border-radius: 8px !important;
      padding: 12px 20px !important;
      font-weight: 500 !important;
      font-size: 14px !important;
      cursor: pointer !important;
      transition: all 0.2s ease !important;
    }
    
    .btn-outline:hover {
      background-color: #000000 !important;
      color: white !important;
      border-color: #000000 !important;
    }
    
    .btn-primary {
      background-color: #000000 !important;
      color: white !important;
      border: none !important;
      border-radius: 8px !important;
      padding: 12px 20px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
      cursor: pointer !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
    }
    
    .btn-primary:hover {
      background-color: #333333 !important;
    }
    </style>
    
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
                  <button type="button" class="btn btn-primary">
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
                    <i class="fas fa-check" style="font-size: 10px;"></i>
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
            <button type="button" class="btn btn-outline" onclick="closePublishEventModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
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
