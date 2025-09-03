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
        This button links to the Student registration page 
        <br>
        
        <a href="<?=ROOT?>/Student/Dashboard" class="btn btn-primary btn-lg">student Dashboard</a> <br>
        <a href="<?=ROOT?>/Alumni/Dashboard" class="btn btn-primary btn-lg">ALumni Dashboard</a>  <br>
        <a href="<?=ROOT?>/FacultyAdmin/Dashboard" class="btn btn-primary btn-lg">FacultyAdmin Dashboard</a>  <br>
        <a href="<?=ROOT?>/SuperAdmin/Dashboard" class="btn btn-primary btn-lg">SupAd Dashboard</a>  <br>
        <a href="<?=ROOT?>/Counselor/Dashboard" class="btn btn-primary btn-lg">Counselor Dashboard</a>  <br>
        <br>
        <a href="<?=ROOT?>/Student/Profile" class="btn btn-primary btn-lg">student Profile</a> <br>
        <a href="<?=ROOT?>/Alumni/Profile" class="btn btn-primary btn-lg">alumni Profile</a> <br>
        <br>

        <a href="<?=ROOT?>/SuperAdmin/UserManager" class="btn btn-primary btn-lg">SuperAdmin Usermanagement</a>  <br>
        <br>
        <a href="<?=ROOT?>/FacultyAdmin/Membership" class="btn btn-primary btn-lg">FacultyAdmin Membership</a>  <br>

        <!-- This button links to the Alumni registration page --> 
    </div>
</div> 
<?php require '../app/views/partials/footer.php'; ?>
   


