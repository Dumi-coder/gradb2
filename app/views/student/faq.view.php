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
            <!-- General Questions -->
            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What is GradBridge and how does it work?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>GradBridge is a comprehensive platform that connects students with verified alumni for mentorship, financial aid, and community support. The system allows students to request mentorship guidance, apply for financial aid, participate in discussion forums, access shared resources, and register for alumni networking events. All interactions are monitored by counselors and administrators to ensure quality and safety.</p>
              </div>
            </div>

            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I navigate the dashboard?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Your dashboard is divided into sections accessible through the sidebar: Profile (view/edit your information), Mentorship (request and track mentorship), Aid Requests (apply for financial aid), Discussion Forum (engage with community), Events (register for alumni events), Resources (access shared materials), and Fundraising (view/support fundraising campaigns). Each section provides relevant actions and information specific to your needs.</p>
              </div>
            </div>

            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Who can see my profile and information?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Your basic profile (name, faculty, year) is visible to verified alumni and other students within the platform. Sensitive information like your student ID, contact details, and aid request details are only accessible to authorized counselors, administrators, and alumni who have accepted your mentorship requests. You can update your profile privacy settings at any time.</p>
              </div>
            </div>

            <div class="faq-item" data-category="general">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How long does it take for requests to be approved?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Mentorship requests are typically reviewed by alumni within 3-5 business days. Aid requests go through counselor verification first (2-3 days), then are presented to alumni for acceptance (3-7 days). You'll receive email notifications at each stage of the process and can track the status in your dashboard.</p>
              </div>
            </div>

            <!-- Mentorship/Career Guidance Questions -->
            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How should I prepare for a job interview?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Research the company thoroughly, understand their products/services, culture, and recent news. Practice common interview questions like "Tell me about yourself," "Why this company?", and behavioral questions using the STAR method (Situation, Task, Action, Result). Prepare 2-3 specific examples from your projects or experiences. Dress professionally, arrive 10-15 minutes early, bring extra copies of your resume, and prepare thoughtful questions to ask the interviewer about the role and team.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I answer "Tell me about yourself" in an interview?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Keep your answer to 1-2 minutes focusing on your professional journey. Start with your current status (e.g., "I'm a final year Computer Science student"), mention 2-3 relevant achievements or experiences, explain why you're interested in this role/company, and end with what you're looking for next. Structure: Present (who you are now) → Past (relevant experiences) → Future (why this role). Avoid personal details like hobbies unless directly relevant to the job.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What should I include in my resume as a student?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Include: Contact information, education (GPA if above 3.5), relevant coursework, technical skills, internships/work experience (use action verbs and quantify achievements), academic projects (describe what you built and technologies used), leadership roles in clubs/organizations, certifications, and awards. Keep it to 1 page, use clear formatting, and tailor it to each job by highlighting relevant experiences. Avoid: photos, personal information (age, marital status), irrelevant hobbies, or vague descriptions.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How can I build my professional network while still in university?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Attend career fairs, industry conferences, and university alumni events. Join professional organizations related to your field. Connect with professors, guest speakers, and classmates on LinkedIn. Participate in hackathons, workshops, and competitions. Reach out to alumni for informational interviews (15-20 minute conversations to learn about their career). Engage meaningfully on LinkedIn by sharing insights and commenting on industry topics. Join student chapters of professional organizations. Remember: networking is about building genuine relationships, not just collecting contacts.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I choose between multiple job offers or career paths?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Evaluate each option based on: (1) Learning opportunities - which role helps you develop more relevant skills? (2) Career growth - does the company have clear advancement paths? (3) Company culture and values alignment (4) Work-life balance and location (5) Compensation package (salary, benefits, bonuses) (6) Team and manager quality (7) Industry growth potential (8) First role impact on future opportunities. Don't just chase the highest salary - prioritize learning and growth early in your career. Talk to current employees if possible and trust your instincts about where you'll thrive.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What technical skills should I focus on developing for my career?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>For Software/IT: Master programming fundamentals (data structures, algorithms), learn version control (Git), database management (SQL), and at least one modern framework relevant to your field (React, Django, Spring, etc.). For Business: Excel/data analysis, presentation skills, financial modeling, project management tools. For all fields: Communication skills (written and verbal), problem-solving, time management, and collaboration tools (Slack, Teams, Jira). Build a portfolio of projects that demonstrate your skills. Stay updated with industry trends by following tech blogs, taking online courses, and contributing to open-source projects.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How do I get my first internship with no experience?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Start by building your skills through personal projects, online courses, and contributing to open-source projects. Create a strong portfolio showcasing these projects on GitHub and a personal website. Network actively - attend career fairs, join LinkedIn, and reach out to alumni. Apply broadly and don't just focus on big companies; startups and smaller companies often provide better learning opportunities. Tailor your resume to each application, emphasizing coursework and projects relevant to the role. Consider voluntary positions, research assistantships, or club projects to build initial experience. Quality applications (10 well-targeted) are better than 100 generic ones.</p>
              </div>
            </div>

            <div class="faq-item" data-category="mentorship">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What should I ask at the end of an interview?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Ask thoughtful questions that show genuine interest: "What does a typical day look like in this role?", "What are the biggest challenges facing the team right now?", "How do you measure success in this position?", "What opportunities are there for learning and professional development?", "Can you describe the team culture and dynamics?", "What are the next steps in the hiring process?". Avoid asking about salary/benefits in the first interview unless they bring it up. Prepare 5-7 questions as some might be answered during the interview.</p>
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

            <!-- Fundraiser Questions -->
            <div class="faq-item" data-category="fundraiser">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What is the process for starting a fundraiser?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>To start a fundraiser, you must first obtain proof of request or documentation for the cause of the fundraiser. If the fundraiser is from a club or organization, get an official letter from the club. Next, submit your proposal for approval by the student board. Once approved by the board, obtain the director or dean's approval. Finally, upload the signed approval letter along with your fundraiser details in the system. The fundraiser will go live after admin verification.</p>
              </div>
            </div>

            <div class="faq-item" data-category="fundraiser">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>What documents do I need to submit for fundraiser approval?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Required documents include: (1) Proof of request or cause documentation explaining the purpose, (2) Official letter from your club/organization if applicable, (3) Board approval letter, (4) Director or Dean's signed approval letter, (5) Detailed budget breakdown, and (6) Timeline for the fundraising campaign. All documents must be uploaded in PDF format.</p>
              </div>
            </div>

            <div class="faq-item" data-category="fundraiser">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>How long does it take for a fundraiser to be approved?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>The approval process typically takes 7-14 business days. Board approval may take 3-5 days, director/dean approval another 3-5 days, and final admin verification 2-3 days. Plan ahead and submit your fundraiser request well in advance of when you need it to go live. You'll receive notifications at each approval stage.</p>
              </div>
            </div>

            <div class="faq-item" data-category="fundraiser">
              <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Can individuals start fundraisers or only clubs?</h3>
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="faq-answer">
                <p>Both individuals and clubs can start fundraisers, but the approval process differs slightly. Individual fundraisers require stronger justification and additional verification. Club fundraisers need the official club letter. All fundraisers must have a legitimate educational or community purpose and require director/dean approval regardless of who initiates them.</p>
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
