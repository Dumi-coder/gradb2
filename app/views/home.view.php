<?php require '../app/views/partials/header.php'; ?>

</main> 


<!-- <div>
    <nav> 
        <a href="<?=ROOT?>">Home</a> 
        <a href="<?=ROOT?>/login">Login</a>
        <a href="<?=ROOT?>/logout">Logout</a> 
    </nav>
</div>  -->


<h4>Hi,  <?=$username?></h4>

<div class="cta-container">
    <h2>Join our community today. Are you a...</h2>
    
    <div class="cta-buttons">
        This button links to the Student registration page -->
        <a href="<?=ROOT?>/auth/student_signup" class="btn btn-primary btn-lg">Student</a>
        
        <!-- This button links to the Alumni registration page --> -->
        <a href="<?=ROOT?>/auth/alumni_signup" class="btn btn-secondary btn-lg">Alumnus</a>
    </div>
</div> 
<?php require '../app/views/partials/footer.php'; ?>
   


