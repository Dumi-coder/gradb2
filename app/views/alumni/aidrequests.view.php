<?php require '../app/views/partials/alumni_header.php'; ?>

<!-- Alumni Aid Requests Content -->
<div class="dashboard-container">
    <?php 
    $current_page = 'aidrequests';
    require '../app/views/partials/alumni_sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="dashboard-header">
                <h1 class="dashboard-title">Aid Requests</h1>
                <p class="dashboard-subtitle">Review and respond to student aid requests — monetary or physical.</p>
            </header>

            <!-- Pending Aid Requests Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Pending Aid Requests</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Aid Request Card 1 - URGENT -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Michael Brown</h3>
                                <p class="request-type">Emergency Transportation</p>
                            </div>
                            <span class="card-badge urgent">Urgent</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Emergency financial assistance needed for transportation costs. I live far from campus and my usual transportation has become unavailable due to family circumstances. Need immediate help to continue attending classes.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Requested: <strong>$300</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <!-- Aid Request Card 2 - URGENT -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">John Doe</h3>
                                <p class="request-type">Boarding Cost Assistance</p>
                            </div>
                            <span class="card-badge urgent">Urgent</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I am facing difficulties with boarding costs for this semester. My family has experienced financial hardship due to recent medical expenses, and I need assistance to continue my studies without interruption.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Requested: <strong>$800</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <!-- Aid Request Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Sarah Johnson</h3>
                                <p class="request-type">Lab Equipment</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I need scientific calculator and lab equipment for my engineering courses. These tools are mandatory for coursework and I cannot afford to purchase them at this time.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Estimated Value: <strong>$150</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <!-- Aid Request Card 4 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Emily Rodriguez</h3>
                                <p class="request-type">Textbook Fund</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I need financial assistance for purchasing required textbooks for the current semester. The books are quite expensive and essential for my coursework in computer science.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Requested: <strong>$400</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <!-- Aid Request Card 5 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">David Kim</h3>
                                <p class="request-type">Laptop for Studies</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                My current laptop is very old and cannot run the required software for my programming courses. I need a replacement laptop to continue my studies effectively.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Estimated Value: <strong>$600</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>

                    <!-- Aid Request Card 6 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Lisa Wang</h3>
                                <p class="request-type">Medical Expenses</p>
                            </div>
                            <span class="card-badge pending">Pending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                I need financial assistance for medical expenses that are affecting my ability to continue my studies. Looking for support to cover necessary healthcare costs.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Requested: <strong>$500</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">Approve</button>
                            <button class="btn btn-secondary">Decline</button>
                        </div>
                    </div>
                </div>
                
                <!-- View All Link -->
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-text">View All Pending Aid Requests</button>
                </div>
            </section>

            <!-- Approved Aid Requests Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Recently Approved</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Approved Aid Request Card 1 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Mary Smith</h3>
                                <p class="request-type">Laptop for Programming</p>
                            </div>
                            <span class="card-badge active">Approved</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Laptop provided for computer science studies. Equipment has been delivered and student is making excellent progress in programming courses.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Provided Value: <strong>$650</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                            <button class="btn btn-secondary">Mark as Completed</button>
                        </div>
                    </div>

                    <!-- Approved Aid Request Card 2 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Alex Thompson</h3>
                                <p class="request-type">Emergency Fund</p>
                            </div>
                            <span class="card-badge active">Approved</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Emergency financial assistance provided for family crisis. Student was able to continue studies without interruption and maintain academic performance.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Provided: <strong>$750</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                            <button class="btn btn-secondary">Mark as Completed</button>
                        </div>
                    </div>

                    <!-- Approved Aid Request Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Jessica Martinez</h3>
                                <p class="request-type">Course Materials</p>
                            </div>
                            <span class="card-badge active">Approved</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Provided essential course materials and lab equipment for engineering studies. Student successfully completed all required coursework and projects.
                            </p>
                            <div class="aid-details">
                                <div class="aid-amount">Provided Value: <strong>$200</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                            <button class="btn btn-secondary">Mark as Completed</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Completed Aid Requests Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Completed Aid Requests</h2>
                </div>
                
                <div class="mentorship-grid">
                    <!-- Completed Aid Request Card 1 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Emma Wilson</h3>
                                <p class="request-type">Textbook Fund</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Successfully provided textbook funding for computer science courses. Student completed semester with excellent grades and expressed gratitude for the support.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Aug 28, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Provided: <strong>$350</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>

                    <!-- Completed Aid Request Card 2 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">James Rodriguez</h3>
                                <p class="request-type">Engineering Equipment</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Provided scientific calculator and lab equipment for mechanical engineering studies. Student successfully completed all lab requirements and advanced to next semester.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Aug 20, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Provided Value: <strong>$180</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>

                    <!-- Completed Aid Request Card 3 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Sophie Chen</h3>
                                <p class="request-type">Emergency Housing</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Emergency housing assistance provided during family crisis. Student was able to secure stable accommodation and continue studies without interruption.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Aug 15, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Provided: <strong>$900</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>

                    <!-- Completed Aid Request Card 4 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Marcus Johnson</h3>
                                <p class="request-type">Medical Emergency</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Emergency medical assistance provided for urgent dental treatment. Student received necessary care and recovered fully, allowing continued focus on academic studies.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Aug 10, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Provided: <strong>$450</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>

                    <!-- Completed Aid Request Card 5 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Aisha Patel</h3>
                                <p class="request-type">Art Supplies</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Provided professional art supplies and drawing tablet for graphic design coursework. Student created outstanding portfolio and secured internship at design agency.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Jul 30, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Provided Value: <strong>$320</strong></div>
                                <div class="aid-type">Type: <strong>Physical Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>

                    <!-- Completed Aid Request Card 6 -->
                    <div class="mentorship-card">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Daniel Lee</h3>
                                <p class="request-type">Conference Travel</p>
                            </div>
                            <span class="card-badge completed">Completed</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Travel assistance provided for academic conference presentation. Student successfully presented research, networked with industry professionals, and received job offers.
                            </p>
                            <div class="completion-date">
                                <strong>Completed:</strong> Jul 25, 2025
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Amount Provided: <strong>$600</strong></div>
                                <div class="aid-type">Type: <strong>Monetary Aid</strong></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>
                </div>
                
                <!-- View All Link -->
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-text">View All Completed Requests</button>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- CSS Files -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_mentorship.css">

<!-- Additional CSS for Aid Request specific styling -->
<style>
.aid-details {
    background-color: #f8fafc;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-top: 1rem;
    border: 1px solid #e2e8f0;
}

.aid-amount, .aid-type {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.aid-amount:last-child, .aid-type:last-child {
    margin-bottom: 0;
}

.aid-amount strong, .aid-type strong {
    color: #475569;
    font-weight: 600;
}

/* Status badge color adjustments for aid requests */
.mentorship-card .card-badge.approved {
    background-color: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.mentorship-card .card-badge.declined {
    background-color: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* Responsive adjustments for aid details */
@media (max-width: 768px) {
    .aid-details {
        padding: 0.5rem;
    }
    
    .aid-amount, .aid-type {
        font-size: 0.75rem;
    }
}
</style>

<?php require '../app/views/partials/footer.php'; ?>

<!-- JavaScript -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>

<!-- Additional script to ensure sidebar functionality and aid request interactions -->
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
        
        // Set initial margin
        if (window.innerWidth <= 1024) {
            sidebar.classList.add('collapsed');
            mainContent.style.marginLeft = '70px';
        } else {
            mainContent.style.marginLeft = '280px';
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '70px';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.style.marginLeft = '280px';
            }
        });
    }
    
    // Handle Approve/Decline button interactions
    const approveButtons = document.querySelectorAll('.btn-primary');
    const declineButtons = document.querySelectorAll('.btn-secondary');
    
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.textContent.trim() === 'Approve') {
                const card = this.closest('.mentorship-card');
                const badge = card.querySelector('.card-badge');
                const cardActions = card.querySelector('.card-actions');
                
                // Update badge
                badge.textContent = 'Approved';
                badge.className = 'card-badge approved';
                
                // Update button
                cardActions.innerHTML = '<button class="btn btn-primary">View Details</button>';
                
                // Show success message (simple alert for now)
                alert('Aid request approved successfully!');
            }
        });
    });
    
    declineButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.textContent.trim() === 'Decline') {
                const card = this.closest('.mentorship-card');
                const badge = card.querySelector('.card-badge');
                const cardActions = card.querySelector('.card-actions');
                
                // Update badge
                badge.textContent = 'Declined';
                badge.className = 'card-badge declined';
                
                // Update button
                cardActions.innerHTML = '<button class="btn btn-primary">View Details</button>';
                
                // Show success message (simple alert for now)
                alert('Aid request declined successfully!');
            }
        });
    });
});
</script>