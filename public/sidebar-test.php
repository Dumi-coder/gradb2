<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Test - GradBridge</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/buttons.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/badges.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard-header.css">
    
    <!-- JavaScript Files -->
    <script src="<?=ROOT?>/assets/js/sidebar-toggle.js"></script>
</head>
<body>
    <!-- Test Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="welcome-section">
                    <h1 class="welcome-text">Sidebar Responsive Test</h1>
                    <p class="header-subtitle">Testing sidebar collapse functionality</p>
                </div>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Test Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Sidebar Toggle Button -->
            <div class="sidebar-toggle">
                <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3 class="nav-section-title">MAIN</h3>
                    <a href="#" class="nav-item active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Home</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Mentorship</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-hands-helping"></i>
                        <span>Aid Requests</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-comments"></i>
                        <span>Discussion Forum</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events Board</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <h3 class="nav-section-title">RESOURCES</h3>
                    <a href="#" class="nav-item">
                        <i class="fas fa-folder-open"></i>
                        <span>Resources</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Fundraising</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-question-circle"></i>
                        <span>FAQ</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <h3 class="nav-section-title">ACCOUNT</h3>
                    <a href="#" class="nav-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-section">
                <h2 class="section-title">Responsive Sidebar Test</h2>
                <div class="test-content">
                    <p>This is a test page to verify the responsive sidebar functionality.</p>
                    <p><strong>Instructions:</strong></p>
                    <ul>
                        <li><strong>No toggle button visible</strong> - the sidebar automatically responds to screen size</li>
                        <li>Resize the browser window to below 1024px width to see automatic collapse</li>
                        <li>The sidebar should collapse to show only icons when collapsed</li>
                        <li>The sidebar is FIXED - it won't move when you scroll the page content</li>
                        <li>The main content adjusts its margin automatically based on screen size</li>
                        <li>On mobile (below 768px), sidebar automatically collapses to icon-only mode</li>
                    </ul>
                    
                    <div class="test-info">
                        <h3>Current State:</h3>
                        <p>Sidebar Width: <span id="sidebarWidth">280px</span></p>
                        <p>Main Content Margin: <span id="mainMargin">280px</span></p>
                        <p>Window Width: <span id="windowWidth"></span></p>
                    </div>
                    
                    <div class="scroll-test">
                        <h3>Scroll Test Content</h3>
                        <p>Scroll down to verify the sidebar stays fixed while content moves.</p>
                        <div style="height: 200vh; background: linear-gradient(to bottom, #f0f0f0, #e0e0e0); padding: 20px; border-radius: 8px;">
                            <h4>Long Content Area</h4>
                            <p>This is a tall content area to test scrolling behavior.</p>
                            <p>The sidebar should remain fixed at the left side while you scroll through this content.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                            <p>Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                            <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                            <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</p>
                            <p>Sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                            <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Update display information
        function updateDisplayInfo() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            document.getElementById('sidebarWidth').textContent = 
                sidebar.classList.contains('collapsed') ? '80px' : '280px';
            document.getElementById('mainMargin').textContent = 
                mainContent.style.marginLeft || (sidebar.classList.contains('collapsed') ? '80px' : '280px');
            document.getElementById('windowWidth').textContent = window.innerWidth + 'px';
        }
        
        // Update on resize
        window.addEventListener('resize', updateDisplayInfo);
        
        // Initial update
        updateDisplayInfo();
    </script>
</body>
</html>
