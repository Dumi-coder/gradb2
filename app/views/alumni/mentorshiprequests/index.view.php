<?php require '../app/views/partials/alumni_header.php'; ?>

<!-- Alumni Mentorship Content -->
<div class="dashboard-container">
    <?php 
    $current_page = 'mentorship';
    require '../app/views/partials/alumni_sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Mentorship</h1>
                <p class="page-subtitle">Connect with students seeking guidance and make a difference in their academic and career journey.</p>
            </div>

            <!-- Mentorship Requests Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Student Mentorship Requests</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Mentorship Request Card 1 - URGENT (moved to top) -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Michael Chen</h3>
                                <p class="request-type">Startup Guidance</p>
                            </div>
                            <span class="card-badge urgent">Urgent</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I'm planning to start my own tech startup after graduation and need mentorship on business planning 
                                and funding strategies from experienced professionals.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>

                    <!-- Mentorship Request Card 2 - URGENT (moved to top) -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Emily Rodriguez</h3>
                                <p class="request-type">Research Guidance</p>
                            </div>
                            <span class="card-badge urgent">Urgent</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I'm working on my thesis project in data science and machine learning. I need guidance on research 
                                methodology and data analysis techniques.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>

                    <!-- Mentorship Request Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Sarah Johnson</h3>
                                <p class="request-type">Career Guidance</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I'm a final year computer science student looking for guidance on transitioning into the tech industry. 
                                I would appreciate advice on career paths and interview preparation.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>

                    <!-- Mentorship Request Card 4 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">David Kim</h3>
                                <p class="request-type">Industry Transition</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I'm currently in mechanical engineering but interested in transitioning to software development. 
                                I need advice on skill development and portfolio building.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>

                    <!-- Mentorship Request Card 5 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Lisa Wang</h3>
                                <p class="request-type">Academic Guidance</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I'm considering pursuing graduate studies and need guidance on program selection and the application process 
                                for advanced academic research in my field.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>

                    <!-- Mentorship Request Card 6 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Alex Thompson</h3>
                                <p class="request-type">Networking Guidance</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I need help building professional networks and developing interpersonal skills for career advancement. 
                                Looking for advice on effective networking strategies.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">Accept</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#FF0000'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Decline</button>
                        </div>
                    </div>
                </div>
                
                <!-- View All Link -->
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-text">View All Student Mentorship Requests</button>
                </div>
            </section>

            <!-- Active Mentorships Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Active Mentorships</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Active Mentorship Card 1 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Sarah Johnson</h3>
                                <p class="request-type">Career Development</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Currently working on interview preparation and professional skill development. 
                                Making excellent progress with resume optimization and technical interview practice.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#90EE90'; this.style.color='#000000';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Mark as Completed</button>
                        </div>
                    </div>

                    <!-- Active Mentorship Card 2 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Michael Chen</h3>
                                <p class="request-type">Startup Strategy</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Developing business plan and exploring funding opportunities for tech startup. 
                                Regular sessions focusing on market research and product development strategies.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#90EE90'; this.style.color='#000000';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Mark as Completed</button>
                        </div>
                    </div>

                    <!-- Active Mentorship Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Lisa Wang</h3>
                                <p class="request-type">Graduate School Preparation</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Preparing applications for graduate programs in psychology. 
                                Working on research proposals and academic writing improvement.
                            </p>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                            <button class="btn btn-secondary" style="border: 2px solid #000000 !important; color: #000000 !important; background-color: white !important; font-weight: bold !important;" onmouseover="this.style.backgroundColor='#90EE90'; this.style.color='#000000';" onmouseout="this.style.backgroundColor='white'; this.style.color='#000000';">Mark as Completed</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Completed Mentorships Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Completed Mentorships</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Completed Mentorship Card 1 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Emily Rodriguez</h3>
                                <p class="request-type">Research Methodology</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Successfully completed thesis project in data science and machine learning. 
                                Published research paper and secured PhD position at prestigious university.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Aug 25, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>

                    <!-- Completed Mentorship Card 2 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">David Kim</h3>
                                <p class="request-type">Career Transition</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Successfully transitioned from mechanical engineering to software development. 
                                Secured full-time position as junior developer at major tech company.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Jul 18, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>

                    <!-- Completed Mentorship Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Alex Thompson</h3>
                                <p class="request-type">Professional Networking</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Built strong professional network and improved communication skills. 
                                Successfully launched marketing career with increased confidence and connections.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Jun 30, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>

                    <!-- Completed Mentorship Card 4 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Jessica Martinez</h3>
                                <p class="request-type">Entrepreneurship</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Launched successful e-commerce business with comprehensive mentorship support. 
                                Achieved break-even within 6 months and expanding to new markets.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> May 15, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>

                    <!-- Completed Mentorship Card 5 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Ryan Davis</h3>
                                <p class="request-type">Career Pivot</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Successfully transitioned from finance to data science through structured mentorship program. 
                                Completed relevant certifications and secured senior analyst position at fintech startup.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Apr 28, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>

                    <!-- Completed Mentorship Card 6 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Priya Singh</h3>
                                <p class="request-type">Academic Excellence</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Achieved academic excellence through targeted study strategies and research guidance. 
                                Graduated summa cum laude and accepted into prestigious PhD program with full scholarship.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Apr 10, 2025
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary" style="background-color: #000000 !important; color: white !important; border: 2px solid #000000 !important; font-weight: bold !important;">More Details</button>
                        </div>
                    </div>
                </div>
                
                <!-- View All Link -->
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-text">View All Completed Mentorships</button>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- CSS Files -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_mentorship.css">

<!-- JavaScript -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>

<!-- Additional script to ensure sidebar functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure sidebar toggle works
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Update main content margin
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '70px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
        });
        
    }
});
</script>