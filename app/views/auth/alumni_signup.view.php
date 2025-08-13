<?php require '../app/views/partials/header.php'; ?>

<link rel="stylesheet" href="http://localhost/gradb2/public/assets/css/login&signup.css">

<main class="main-content">
    <div class="login-container">
        <h1 class="welcome-title">Welcome <span class="back-text">Back!</span></h1>
        
        <form  method="POST" class="login-form">

            <?php if(!empty($errors)):?>
            <div class="alert alert-danger">       
                <?= implode("<br>",$errors)?> 
            </div>
            <?php endif;?>

            <div class="form-group">
                <input 
                    type="name" 
                    name="name" 
                    class="form-input" 
                    placeholder="Full Name" 
                    value="<?=$_POST['name'] ?? ''?>"
                    required
                >
            </div>
            <div class="form-group">
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="University email address" 
                    value="<?=$_POST['email'] ?? ''?>"
                    required
                >
                <!-- <div id="email-error" style="color:red;"></div> -->
            </div>
            <div class="form-group">
                <input 
                    type="alumni_id" 
                    name="alumni_id" 
                    class="form-input" 
                    placeholder="Alumni ID" 
                    value="<?=$_POST['alumni_id'] ?? ''?>"
                    required
                >
                <!-- <div id="student-id-error" style="color:red;"></div> -->
            </div>
            <div class="form-group">
                <input 
                    type="faculty" 
                    name="faculty" 
                    class="form-input" 
                    placeholder="Faculty" 
                    value="<?=$_POST['faculty'] ?? ''?>"
                    required
                >
            </div>
            <div class="form-group">
                <input 
                    type="number" 
                    name="graduation_year" 
                    class="form-input" 
                    placeholder="Graduation Year" 
                    value="<?= $_POST['graduation_year'] ?? '' ?>"
                    min="1900" 
                    max="<?= date('Y') ?>"
                    required
                >
            </div>
            
            
            <div class="form-group">
                <input 
                type="password" 
                name="password" 
                class="form-input" 
                placeholder="Password" 
            
                required
                >
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    name="confirm_password" 
                    class="form-input" 
                    placeholder="Confirm Password" 
                    required
                >
            </div>
            <button type="submit" class="login-btn" name="signup">Sign Up</button>

        </form>
        
        <div class="signup-link">
            Already have an account?  <a href="<?=ROOT?>/auth/login">Sing In</a>
        </div>
    </div>
</main> 

<?php require '../app/views/partials/footer.php'; ?>

