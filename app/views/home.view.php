
  <?php require '../app/views/partials/header.php'; ?>
    <!-- Hero Section -->
    <section id="home" class="hero-wrapper">
      <div class="hero-container">
        <div class="container">
          <div class="hero-content">
            <div class="hero-text">
              <p class="hero-tagline">Connect. Learn. Grow.</p>
              <h1 class="hero-title">
                Together with <span class="hero-accent">Alumni</span>
              </h1>
              
              <p class="hero-subtitle">
                The premier platform connecting students with verified alumni for mentorship, career guidance, financial aid, and professional growth opportunities.
              </p>

              <div class="hero-buttons">
                <a class="btn btn-secondary" href="<?=ROOT?>/Student/Auth?action=signup">
                  Join as Student
                </a>
                <a class="btn btn-outline" href="<?=ROOT?>/Alumni/Auth?action=signup">
                  Join as Alumni
                </a>
              </div>
            </div>
            
            <div class="hero-image">
              <img src="<?=ROOT?>/assets/images/image3.jpg" alt="GradBridge Community">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about gradient-subtle">
      <div class="container">
        <div class="about-content">
          <div class="about-badge">
            <i class="fas fa-info-circle"></i>
            <span>What is GradBridge?</span>
          </div>
          <h2 class="section-title">Your Bridge to Success</h2>
          <p class="section-subtitle">
            GradBridge is a trusted platform built to connect students and alumni, fostering mentorship, opportunity, and growth. 
            From verified profiles and discussions to events and shared resources, we help you learn from experience and accelerate your journey.
          </p>
          
          <!-- Key Benefits Grid -->
          <div class="benefits-grid">
            <div class="benefit-item student-benefit">
              <h3>For Students</h3>
              <p>Get personalized mentorship, financial aid, and career guidance from alumni who've walked your path.</p>
            </div>
            <div class="benefit-item alumni-benefit">
              <h3>For Alumni</h3>
              <p>Give back to your community, share your expertise, and stay connected with your alma mater.</p>
            </div>
            <div class="benefit-item institution-benefit">
              <h3>For Institutions</h3>
              <p>Foster a strong alumni network, track engagement, and provide better support for your students.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

   
    

    <!-- Feature Highlights -->
    <section id="features" class="features">
      <div class="container">
        <div class="features-badge">
          <i class="fas fa-star"></i>
          <span>Platform Features</span>
        </div>
        <h2 class="section-title">Everything You Need to Succeed</h2>
        <p class="section-subtitle">
          GradBridge provides a comprehensive suite of tools to facilitate meaningful connections, mentorship, and growth opportunities.
        </p>

        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">🎓</span>
              <h3 class="feature-title">Mentorship Guidance</h3>
            </div>
            <p class="feature-description">Connect with experienced alumni for personalized career advice and professional development.</p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">✓</span>
              <h3 class="feature-title">Verified Connections</h3>
            </div>
            <p class="feature-description">All alumni profiles are verified, ensuring authentic and credible mentorship relationships.</p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">💡</span>
              <h3 class="feature-title">Financial and resource aid</h3>
            </div>
            <p class="feature-description">Request and receive financial, academic, or resource support from willing alumni.</p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">💬</span>
              <h3 class="feature-title">Discussion Space</h3>
            </div>
            <p class="feature-description">Engage in meaningful conversations through forums and community discussions.</p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">📅</span>
              <h3 class="feature-title">Community Events</h3>
            </div>
            <p class="feature-description">Participate in networking events, workshops, and exclusive alumni gatherings.</p>
          </div>

          <div class="feature-card">
            <div class="feature-header">
              <span class="feature-icon">📚</span>
              <h3 class="feature-title">Shared Resources</h3>
            </div>
            <p class="feature-description">Access a library of shared materials, job postings, and educational content.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta gradient-hero">
      <div class="container">
        <div class="cta-content">
          <div class="cta-badge">
            <i class="fas fa-rocket"></i>
            <span>Start Your Journey</span>
          </div>
          
          <h2 class="cta-title">
            Join GradBridge Today
          </h2>
          
          <p class="cta-subtitle">
            Connect with verified alumni, get mentorship, access financial aid, and unlock opportunities that accelerate your career growth.
          </p>

          <div class="cta-buttons">
            <a href="<?=ROOT?>/Student/Auth?action=signup" class="btn btn-light ">
              Sign Up as Student
            </a>
            <a href="<?=ROOT?>/Alumni/Auth?action=signup" class="btn btn-outline-light ">
              Sign Up as Alumni
            </a>
          </div>
        </div>
      </div>
    </section>
<?php require '../app/views/partials/footer.php'; ?>

    