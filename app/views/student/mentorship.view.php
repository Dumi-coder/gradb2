<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with alumni mentors";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/mentorship.css">

<div class="dashboard-container">

      <!-- sidebar -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>
      
      <!-- Main Content Area -->
      <main class="main-content">
        <div class="content-container">
          <!-- Need Mentorship Section -->
          <section class="mentorship-section">
          <div class="mentorship-card">
            <h2 class="card-title">Need mentorship?</h2>
            <p class="card-description">Before requesting, please check if your query is already answered.</p>
            <a href="<?=ROOT?>/student/Faq">
              <button class="btn btn-outline btn-md faq-btn">
              <i class="fas fa-question-circle"></i>
              <span>View FAQs</span>
              </button>
            </a>
            <div class="divider"></div>
            <p class="question-text">Are your questions still unanswered?</p>
            <a href="<?=ROOT?>/student/MentorshipReq">
            <button class="btn btn-primary btn-lg request-btn">
              <i class="fas fa-plus"></i>
              <span>Yes, Request Mentorship</span>
            </button>  
            </a>         
          </div>
        </section>

        
        <!-- Your Mentorship Requests Section -->
        <section class="requests-section">
          <h2 class="section-title">Your mentorship requests</h2>
          
          <?php if (isset($data['requests']) && !empty($data['requests'])): ?>
            <div class="requests-grid">
              <?php foreach ($data['requests'] as $request): ?>
                <div class="request-card">
                  <div class="request-header">
                    <h3 class="request-title">Mentorship Request #<?= htmlspecialchars($request['request_id']) ?></h3>
                    <span class="status-badge status-<?= strtolower(str_replace('_', '-', $request['status'])) ?>">
                      <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                    </span>
                  </div>
                  <?php 
                    // Get category display name
                    $categoryDisplay = '';
                    if ($request['mentorship_category'] === 'other' && !empty($request['other_category'])) {
                      $categoryDisplay = $request['other_category'];
                    } else {
                      $categoryMap = [
                        'academic' => 'Academic Guidance',
                        'career' => 'Career Development',
                        'research' => 'Research & Projects',
                        'networking' => 'Professional Networking',
                        'skills' => 'Technical Skills',
                        'leadership' => 'Leadership & Management',
                        'entrepreneurship' => 'Entrepreneurship'
                      ];
                      $categoryDisplay = $categoryMap[$request['mentorship_category']] ?? ucfirst($request['mentorship_category']);
                    }
                  ?>
                  <div class="request-category">
                    <strong>Category:</strong> <?= htmlspecialchars($categoryDisplay) ?>
                  </div>
                  <p class="request-description"><?= htmlspecialchars($request['request_reason']) ?></p>
                  <div class="request-meta">
                    <small class="request-date">Created: <?= date('M j, Y', strtotime($request['created_at'])) ?></small>
                  </div>
                  <div class="request-actions">
                    <?php if (in_array($request['status'], ['pending_verification', 'open'])): ?>
                      <a href="<?=ROOT?>/student/MentorshipReq/edit/<?= $request['request_id'] ?>" class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                      </a>
                      <a href="<?=ROOT?>/student/MentorshipReq/delete/<?= $request['request_id'] ?>" 
                         class="btn btn-danger btn-sm" 
                         onclick="return confirm('Are you sure you want to delete this mentorship request?')">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                      </a>
                    <?php else: ?>
                      <button class="btn btn-outline btn-sm" disabled>
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <div class="empty-icon">
                <i class="fas fa-inbox"></i>
              </div>
              <h3>No mentorship requests yet</h3>
              <p>You haven't submitted any mentorship requests. Click the button above to get started!</p>
            </div>
          <?php endif; ?>
        </section>
        </div>
      </div>
    </div>
    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/profile-modals.js"></script>
    <script src="<?=ROOT?>/assets/js/profile-modals.js"></script>
  </body>
</html>
