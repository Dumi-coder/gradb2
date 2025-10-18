<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GradBridge Components - Code Examples</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 3px solid #0E2072;
            padding-bottom: 10px;
        }
        .component {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fafafa;
        }
        .component h2 {
            color: #0E2072;
            margin-top: 0;
        }
        .component h3 {
            color: #555;
            margin-top: 20px;
        }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: var(--font-sm);
            margin: 10px 0;
        }
        .usage-note {
            background: #e7f3ff;
            border-left: 4px solid #0E2072;
            padding: 15px;
            margin: 15px 0;
        }
        .file-path {
            background: #e8e8e8;
            padding: 5px 10px;
            border-radius: 3px;
            font-family: monospace;
            color: #666;
            display: inline-block;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>GradBridge Components - Code Examples</h1>
        
        <!-- Header Component -->
        <div class="component">
            <h2>Header Component</h2>
            <p>Standard header with navigation and logo</p>
            
            <div class="file-path">assets/css/header.css</div>
            
            <h3>HTML Structure:</h3>
            <div class="code-block">
&lt;header class="header"&gt;
  &lt;div class="container"&gt;
    &lt;div class="header-content"&gt;
      &lt;div class="logo"&gt;GradBridge&lt;/div&gt;
      &lt;nav class="nav"&gt;
        &lt;a href="#" class="nav-link"&gt;Home&lt;/a&gt;
        &lt;a href="#" class="nav-link"&gt;About&lt;/a&gt;
        &lt;a href="#" class="nav-link"&gt;Features&lt;/a&gt;
      &lt;/nav&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/header&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include header.css and use the header class structure above
            </div>
        </div>

        <!-- Footer Component -->
        <div class="component">
            <h2>Footer Component</h2>
            <p>Consistent footer with links and social media</p>
            
            <div class="file-path">assets/css/footer.css</div>
            
            <h3>HTML Structure:</h3>
            <div class="code-block">
&lt;footer class="footer"&gt;
  &lt;div class="container"&gt;
    &lt;div class="footer-content"&gt;
      &lt;div class="footer-links"&gt;
        &lt;a href="#" class="footer-link"&gt;Privacy Policy&lt;/a&gt;
        &lt;a href="#" class="footer-link"&gt;Contact&lt;/a&gt;
        &lt;a href="#" class="footer-link"&gt;Terms of Service&lt;/a&gt;
      &lt;/div&gt;
      &lt;div class="footer-social"&gt;
        &lt;a href="#" class="social-link"&gt;Twitter&lt;/a&gt;
        &lt;a href="#" class="social-link"&gt;GitHub&lt;/a&gt;
      &lt;/div&gt;
      &lt;div class="footer-copy"&gt;
        © 2024 GradBridge. All rights reserved.
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/footer&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include footer.css and use the footer class structure above
            </div>
        </div>

        <!-- Sidebar Component -->
        <div class="component">
            <h2>Sidebar Navigation Component</h2>
            <p>Dashboard sidebar with consistent navigation</p>
            
            <div class="file-path">assets/css/sidebar.css</div>
            
            <h3>HTML Structure:</h3>
            <div class="code-block">
&lt;aside class="sidebar"&gt;
  &lt;nav class="sidebar-nav"&gt;
    &lt;div class="nav-section"&gt;
      &lt;h3 class="nav-section-title"&gt;Main&lt;/h3&gt;
      &lt;a href="#" class="nav-item active"&gt;
        &lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt;
        &lt;span&gt;Dashboard&lt;/span&gt;
      &lt;/a&gt;
      &lt;a href="#" class="nav-item"&gt;
        &lt;i class="fas fa-users"&gt;&lt;/i&gt;
        &lt;span&gt;Mentorship&lt;/span&gt;
      &lt;/a&gt;
    &lt;/div&gt;
    
    &lt;div class="nav-section"&gt;
      &lt;h3 class="nav-section-title"&gt;Resources&lt;/h3&gt;
      &lt;a href="#" class="nav-item"&gt;
        &lt;i class="fas fa-calendar-alt"&gt;&lt;/i&gt;
        &lt;span&gt;Events&lt;/span&gt;
      &lt;/a&gt;
    &lt;/div&gt;
  &lt;/nav&gt;
&lt;/aside&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include sidebar.css and use the sidebar class structure above. Add 'active' class to current page.
            </div>
        </div>

        <!-- Dashboard Header Component -->
        <div class="component">
            <h2>Dashboard Header Component</h2>
            <p>Fixed header for dashboard pages with user info and actions</p>
            
            <div class="file-path">assets/css/dashboard.css (header section)</div>
            
            <h3>HTML Structure:</h3>
            <div class="code-block">
&lt;header class="dashboard-header"&gt;
  &lt;div class="container"&gt;
    &lt;div class="header-content"&gt;
      &lt;div class="welcome-section"&gt;
        &lt;h1 class="welcome-text"&gt;Welcome, &lt;span class="student-name"&gt;Sarah Johnson&lt;/span&gt;&lt;/h1&gt;
        &lt;p class="student-role"&gt;Computer Science • Year 3&lt;/p&gt;
      &lt;/div&gt;
      
      &lt;div class="header-actions"&gt;
        &lt;button class="btn btn-outline notification-btn"&gt;
          &lt;i class="fas fa-bell"&gt;&lt;/i&gt;
          &lt;span class="notification-badge"&gt;3&lt;/span&gt;
        &lt;/button&gt;
        &lt;button class="btn btn-primary logout-btn"&gt;Logout&lt;/button&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/header&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include dashboard.css and use the dashboard-header class structure above
            </div>
        </div>

        <!-- Button Components -->
        <div class="component">
            <h2>Button Components</h2>
            <p>Standardized button system with multiple variants</p>
            
            <div class="file-path">assets/css/buttons.css</div>
            
            <h3>Button Variants:</h3>
            <div class="code-block">
&lt;!-- Primary Button --&gt;
&lt;button class="btn btn-primary"&gt;Primary Button&lt;/button&gt;

&lt;!-- Secondary Button --&gt;
&lt;button class="btn btn-secondary"&gt;Secondary Button&lt;/button&gt;

&lt;!-- Outline Button --&gt;
&lt;button class="btn btn-outline"&gt;Outline Button&lt;/button&gt;

&lt;!-- Light Button --&gt;
&lt;button class="btn btn-light"&gt;Light Button&lt;/button&gt;
            </div>
            
            <h3>Button Sizes:</h3>
            <div class="code-block">
&lt;!-- Small Button --&gt;
&lt;button class="btn btn-primary btn-sm"&gt;Small&lt;/button&gt;

&lt;!-- Medium Button (default) --&gt;
&lt;button class="btn btn-primary btn-md"&gt;Medium&lt;/button&gt;

&lt;!-- Large Button --&gt;
&lt;button class="btn btn-primary btn-lg"&gt;Large&lt;/button&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include buttons.css and use btn class with variant and size modifiers
            </div>
        </div>

        <!-- Edit Profile Component -->
        <div class="component">
            <h2>Edit Profile Component</h2>
            <p>Complete profile editing with picture upload and form handling</p>
            
            <div class="file-path">assets/css/edit-profile.css + assets/js/edit-profile.js</div>
            
            <h3>Profile Picture HTML:</h3>
            <div class="code-block">
&lt;div class="profile-avatar-container"&gt;
  &lt;div class="profile-avatar" id="profileAvatar"&gt;
    &lt;img src="" alt="Profile Picture" id="profileImage" style="display: none;"&gt;
    &lt;i class="fas fa-user-graduate" id="defaultAvatar"&gt;&lt;/i&gt;
  &lt;/div&gt;
  &lt;div class="avatar-upload-overlay"&gt;
    &lt;label for="profilePictureInput" class="avatar-upload-btn"&gt;
      &lt;i class="fas fa-camera"&gt;&lt;/i&gt;
    &lt;/label&gt;
    &lt;input type="file" id="profilePictureInput" accept="image/*" style="display: none;"&gt;
  &lt;/div&gt;
&lt;/div&gt;
            </div>
            
            <h3>JavaScript Usage:</h3>
            <div class="code-block">
&lt;script&gt;
  // Initialize the component
  const editProfile = new EditProfileComponent();
  
  // Show the edit profile modal
  editProfile.show();
&lt;/script&gt;
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> Include both CSS and JS files, then initialize EditProfileComponent class
            </div>
        </div>

        <!-- CSS Variables -->
        <div class="component">
            <h2>CSS Design System Variables</h2>
            <p>Consistent design tokens used across all components</p>
            
            <div class="file-path">assets/css/Main.css</div>
            
            <h3>Color Variables:</h3>
            <div class="code-block">
:root {
  /* Primary Colors */
  --primary: #0E2072;
  --primary-foreground: hsl(0, 0%, 100%);
  
  /* Background Colors */
  --background: hsl(0, 0%, 100%);
  --foreground: hsl(0, 0%, 8%);
  
  /* Secondary Colors */
  --secondary: hsl(210, 11%, 96%);
  --muted-foreground: hsl(0, 0%, 45%);
  
  /* Border Colors */
  --border: hsl(210, 11%, 90%);
  
  /* Spacing */
  --spacing-xs: 0.5rem;
  --spacing-sm: 1rem;
  --spacing-md: 1.5rem;
  --spacing-lg: 2rem;
  --spacing-xl: 3rem;
  
  /* Border Radius */
  --radius-sm: 0.5rem;
  --radius-md: 1rem;
  --radius-lg: 1.5rem;
  
  /* Button Height */
  --button-height: 48px;
}
            </div>
            
            <div class="usage-note">
                <strong>Usage:</strong> These variables are automatically available when including Main.css
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="component">
            <h2>Quick Start Guide</h2>
            <p>How to use these components in your pages</p>
            
            <h3>1. Include Required CSS Files:</h3>
            <div class="code-block">
&lt;!-- Always include Main.css first for variables --&gt;
&lt;link rel="stylesheet" href="assets/css/Main.css"&gt;

&lt;!-- Then include component CSS files --&gt;
&lt;link rel="stylesheet" href="assets/css/buttons.css"&gt;
&lt;link rel="stylesheet" href="assets/css/header.css"&gt;
&lt;link rel="stylesheet" href="assets/css/sidebar.css"&gt;
&lt;link rel="stylesheet" href="assets/css/footer.css"&gt;
            </div>
            
            <h3>2. Include Required JavaScript Files:</h3>
            <div class="code-block">
&lt;!-- For Edit Profile Component --&gt;
&lt;script src="assets/js/edit-profile.js"&gt;&lt;/script&gt;
            </div>
            
            <h3>3. Use Component HTML Structure:</h3>
            <div class="code-block">
&lt;!-- Copy the HTML structure from the component examples above --&gt;
&lt;!-- Make sure to include Font Awesome for icons --&gt;
&lt;link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"&gt;

&lt;!-- Button Structure: Always wrap text in &lt;span&gt; tags for proper spacing --&gt;
&lt;button class="btn btn-primary"&gt;
  &lt;i class="fas fa-plus"&gt;&lt;/i&gt;
  &lt;span&gt;Button Text&lt;/span&gt;
&lt;/button&gt;
            </div>
            
            <div class="usage-note">
                <strong>Note:</strong> All components use the same design system and will work together seamlessly
            </div>
        </div>
    </div>
</body>
</html>
