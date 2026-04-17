<?php 
$page_title = "FAQ";
$page_subtitle = "Frequently Asked Questions";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/faq.css">

    <div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">

        <!-- FAQ Categories -->
        <section class="dashboard-section categories-section">
          <div class="section-header">
            <h2 class="card-title">Browse by Category</h2>
          </div>
          
          <div class="faq-categories">
            <button class="category-btn active" data-category="all">
              <i class="fas fa-th-large"></i>
              <span>All Questions</span>
            </button>
            <button class="category-btn" data-category="general">
              <i class="fas fa-info-circle"></i>
              <span>General</span>
            </button>
            <button class="category-btn" data-category="mentorship">
              <i class="fas fa-users"></i>
              <span>Mentorship</span>
            </button>
            <button class="category-btn" data-category="aid">
              <i class="fas fa-hands-helping"></i>
              <span>Aid Requests</span>
            </button>
            <button class="category-btn" data-category="technical">
              <i class="fas fa-cog"></i>
              <span>Technical</span>
            </button>
            <button class="category-btn" data-category="fundraiser">
              <i class="fas fa-hand-holding-usd"></i>
              <span>Fundraiser</span>
            </button>
          </div>
        </section>

        <!-- FAQ Items -->
        <section class="dashboard-section faq-section">
          <div class="section-header">
            <h2 class="card-title">Frequently Asked Questions</h2>
          </div>

          <div class="faq-list">
            <?php if (!empty($faqs)): ?>
              <?php foreach ($faqs as $faq): ?>
                <?php 
                  $category = strtolower($faq->category ?? 'general');
                ?>
                <div class="faq-item" data-category="<?= esc($category) ?>">
                  <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3><?= esc($faq->question) ?></h3>
                    <i class="fas fa-chevron-down"></i>
                  </div>
                  <div class="faq-answer">
                    <p><?= nl2br(esc($faq->answer)) ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No FAQs are available for your faculty yet. Please check back later.</p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Contact Support -->
        <section class="dashboard-section contact-section">
          <div class="section-header">
            <h2 class="card-title">Still Need Help?</h2>
          </div>
          
          <div class="contact-info">
            <div class="contact-card">
              <div class="contact-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="contact-content">
                <h3>Email Support</h3>
                <p>Get help via email within 24 hours</p>
                <a href="mailto:support@gradbridge.edu" class="contact-link">support@gradbridge.edu</a>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-icon">
                <i class="fas fa-phone"></i>
              </div>
              <div class="contact-content">
                <h3>Phone Support</h3>
                <p>Call us during business hours</p>
                <a href="tel:+1-555-0123" class="contact-link">+94 770975746</a>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>

    <!-- JS -->
    <script src="<?=ROOT?>/assets/js/faq.js"></script>
  </body>
</html>
