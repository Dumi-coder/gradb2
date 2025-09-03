
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- <title>GradBridge</title> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="http://localhost/gradb2/public/css/style.css"> -->
     <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/header.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/popupstyle.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/footer.css">
    <!-- <link rel="stylesheet" href="http://localhost/gradb2/public/assets/css/login.css"> -->
    <!-- Note: Using the full URL for CSS is more reliable -->
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="#" class="logo">
                <img src="<?=ROOT?>/assets/images/icons/sitelogo.png" alt="GradBridge Logo" class="logo-image">
                <span class="logo-text">GradBridge</span>
            </a>
        </div>
        
        <div class="header-right">
            <nav>
                <ul class="nav-links">
                    <li><a href="/gradb2/public/home" class="nav-link">Home</a></li>
                    <li><a href="/gradb2/public/About" class="nav-link">About</a></li>
                    <li><a href="/gradb2/public/Features" class="nav-link">Features</a></li>
                    <!-- <li><a class="nav-link" id="loginBtn">Login</a></li>  -->
                    <li><a id="loginBtn" class="nav-link">Login</a></li>
                    <button id="registerBtn" class="register-btn">Register</button>
                
                    <!-- <li><a href="/gradb2/public/login" class="nav-link">Login</a></li> -->
                </ul>
            </nav>
            <!-- <button class="register-btn" onclick="window.location.href='/gradb2/public/signup'">Register</button> -->
            
        </div>
    </header>

    <!-- role selection modal -->
     <div id="roleModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeModalBtn">&times;</span>
            <h2 id="modalTitle">Choose your role</h2>
            <p id="modalPrompt">Please select your role to continue</p>
            <div class="modal-buttons">
                <a href="#" id="studentLink" class="btn btn-lg btn-student">I'm a Student</a>
                <a href="#" id="alumniLink" class="btn btn-lg btn-alumni">I'm an Alumnus</a>
                <br>
                <a href="#" id="counselorLink" class="btn btn-lg btn-alumni">Counselor</a>
            </div>
        </div>
     </div>

    <main>