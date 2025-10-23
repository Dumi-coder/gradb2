<?php 
$page_title = "FAQ";
$page_subtitle = "Frequently Asked Questions";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/faq.css">

<div class="dashboard-container">
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>
   
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
          </div>
        </section>

        <!-- FAQ Items -->
        <section class="dashboard-section faq-section">
          <div class="section-header">
            <h2 class="card-title">Frequently Asked Questions</h2>
          </div>

          <div class="faq-list">
            <!-- General Questions -->
            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What is GradBridge?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>GradBridge is a comprehensive platform designed to connect students with alumni mentors, provide access to financial aid resources, and foster a supportive academic community. We help students navigate their educational journey through mentorship, resource sharing, and community support.</p>
              </div>
            </div>

            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I create an account?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>To create an account, click the "Sign Up" button on the homepage and choose whether you're a student or alumni. Fill in your basic information, verify your email address, and complete your profile. Students will need to provide their university email and student ID for verification.</p>
              </div>
            </div>

            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Is GradBridge free to use?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Yes, GradBridge is completely free for students and alumni. We believe in providing accessible support for education and career development without financial barriers.</p>
              </div>
            </div>

            <!-- Mentorship Questions -->
            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How does the mentorship program work?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Students can browse available alumni mentors based on their field of study, career interests, and location. Once you find a suitable mentor, you can send a mentorship request explaining your goals and what you hope to learn. Mentors review requests and can accept or decline based on their availability and expertise.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What should I include in my mentorship request?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Your mentorship request should include: your academic background, career goals, specific areas where you need guidance, your availability for meetings, and what you hope to achieve through the mentorship. Be clear about your expectations and respectful of the mentor's time.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How often should I meet with my mentor?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>The frequency of meetings depends on your mentor's availability and your needs. Most successful mentorship relationships involve monthly or bi-weekly meetings. You can discuss and agree on a schedule that works for both parties during your initial conversation.</p>
              </div>
            </div>

            <!-- Aid Request Questions -->
            <div class="faq-item" data-category="aid">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What types of financial aid are available?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>We offer various types of financial support including emergency funds for unexpected expenses, scholarship opportunities, textbook assistance, technology grants for laptops and software, and meal plan subsidies. Each type has specific eligibility criteria and application processes.</p>
              </div>
            </div>

            <div class="faq-item" data-category="aid">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How long does it take to process aid requests?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Standard aid requests are typically processed within 5-7 business days. Emergency requests are prioritized and can be processed within 24-48 hours. You'll receive email notifications at each step of the process, and you can track your application status in your dashboard.</p>
              </div>
            </div>

            <div class="faq-item" data-category="aid">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What documents do I need for aid applications?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Required documents vary by aid type but commonly include: proof of enrollment, financial statements, tax returns or income verification, personal statement explaining your need, and any relevant supporting documentation (medical bills, job loss notices, etc.). Check the specific requirements for each aid type.</p>
              </div>
            </div>

            <!-- Technical Questions -->
            <div class="faq-item" data-category="technical">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>I'm having trouble logging in. What should I do?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>First, try resetting your password using the "Forgot Password" link on the login page. If you're still having issues, check that you're using the correct email address and that your account is verified. Contact our support team if problems persist.</p>
              </div>
            </div>

            <div class="faq-item" data-category="technical">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I update my profile information?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Go to your dashboard and click on "Profile" in the sidebar. From there, you can edit your personal information, academic details, career interests, and contact information. Remember to save your changes before navigating away from the page.</p>
              </div>
            </div>

            <div class="faq-item" data-category="technical">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Can I use GradBridge on my mobile device?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Yes, GradBridge is fully responsive and works on all devices including smartphones and tablets. You can access all features through your mobile browser. We're also working on a dedicated mobile app for an even better experience.</p>
              </div>
            </div>
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
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/faq.js"></script>
  </body>
</html>
