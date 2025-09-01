            
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
                   type="text" 
                   name="counselor_id" 
                   id="CounselorIDInput" 
                   class="form-input"
                   placeholder="Counselor ID" 
                   required>
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
            
            <button type="submit" class="login-btn" name="login">Login</button>
        </form>
        
        
        <div class="signup-link">
            Don't have an account? 
            
            <a href="<?=ROOT?>/counselor/signup">Counselor Sign Up</a>
        </div>
        
        <div class="forgot-password">
            <a href="<?=ROOT?>/forgot_password">Forgot Password?</a>
        </div>
    </div>
</main>

<?php require '../app/views/partials/footer.php'; ?>


