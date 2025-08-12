            
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
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="University email address" 
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
            
            <button type="submit" class="login-btn" name="login">Login</button>
        </form>
        
        <a href="forgot_password.php" class="forgot-password">Forgot Password</a>
        
        <div class="signup-link">
            Don't have an Account? <a href="<?=ROOT?>/signup">Sign Up</a>
        </div>
    </div>
</main>

<?php require '../app/views/partials/footer.php'; ?>


