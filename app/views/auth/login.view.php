<!-- <form method="post">            
    <h1>Please sign in</h1>
           <div>
             <input  name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
             <label for="floatingInput">Email address</label>
            </div>
            <div>
                 <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                 <label for="floatingPassword">Password</label>
            </div>
            <div>
                <input  type="checkbox" value="remember-me" id="checkDefault">
                <label for="checkDefault">Remember me</label>
            </div> 
            <button type="submit">Sign in</button> 
            <a href="<?=ROOT?>/home">Home</a>
            <a href="<?=ROOT?>/signup">Signup</a>
            
        </form>  -->        
        
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


