<?php require '../app/views/partials/header.php'; ?>
<main>
    <?php if(Auth::logged_in()): ?>
        <!-- Logged in user content -->
        <h4>Welcome back, <?= esc(Auth::user()->name) ?>!</h4>
        <h1>Your Gateway to Alumni-Student Connections</h1>
        <p class="lead">Connect with fellow students and alumni, find mentors, explore career opportunities, and build meaningful professional relationships.</p>
        
        <div class="cta-container">
            <h2>What would you like to do today?</h2>
            <div class="cta-buttons">
                <?php if(Auth::is_student()): ?>
                    <a href="<?=ROOT?>/student/dashboard" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    <a href="<?=ROOT?>/student/alumni_network" class="btn btn-secondary btn-lg">Browse Alumni Network</a>
                <?php elseif(Auth::is_alumni()): ?>
                    <a href="<?=ROOT?>/alumni/dashboard" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    <a href="<?=ROOT?>/alumni/student_network" class="btn btn-secondary btn-lg">Connect with Students</a>
                <?php endif; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Guest user content -->
        <h1>Welcome to GradBridge</h1>
        <p class="lead">Connect students with alumni for mentorship, career guidance, and professional networking. Build bridges between current students and successful graduates.</p>
        
        <div class="cta-container">
            <h2>Join our community today. Are you a...</h2>
            
            <div class="cta-buttons">
                <!-- This button links to the Student registration page -->
                <a href="<?=ROOT?>/auth/student_signup" class="btn btn-primary btn-lg">Student</a>
                
                <!-- This button links to the Alumni registration page -->
                <a href="<?=ROOT?>/auth/alumni_signup" class="btn btn-secondary btn-lg">Alumnus</a>
            </div>
            
            <div class="login-link" style="margin-top: 20px;">
                <p>Already have an account? <a href="<?=ROOT?>/auth/login">Sign in here</a></p>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require '../app/views/partials/footer.php'; ?>

          <!-- <div>
              <nav> 
              <a href="<?=ROOT?>">Home</a> 
              <a href="<?=ROOT?>/login">Login</a>
              <a href="<?=ROOT?>/logout">Logout</a> 
              </nav>
          </div>  -->

          
              <!-- <h4>Hi,  <?=$username?></h4> -->

    <!-- <div class="cta-container">
        <h2>Join our community today. Are you a...</h2>
        
        <div class="cta-buttons">
            This button links to the Student registration page -->
            <!-- <a href="<?=ROOT?>/auth/student_signup" class="btn btn-primary btn-lg">Student</a>
            
             This button links to the Alumni registration page -->
            <!-- <a href="<?=ROOT?>/auth/alumni_signup" class="btn btn-secondary btn-lg">Alumnus</a> -->
        <!-- </div> -->
    <!-- </div>  -->


