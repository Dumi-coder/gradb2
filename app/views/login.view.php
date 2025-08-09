<?php require '../app/views/partials/header.php'; ?>
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

<main class="main-content">
    <div class="login-container">
        <h1 class="welcome-title">Welcome <span class="back-text">Back!</span></h1>
        
        <form action="login_process.php" method="POST" class="login-form">

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
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
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

<style>
/* Login Page Specific Styles */
.main-content {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 200px);
    padding: 40px 20px;
    background-color: #f5f5f5;
}

.login-container {
    display: flex;
    width: 460px;
    padding: 30px 82px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 30px;
    border-radius: 7px;
    border: 1px solid #000;
    background: #FFF;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.welcome-title {
    color: #000;
    text-align: center;
    font-family: 'Poppins', sans-serif;
    font-size: 28px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    margin-bottom: 20px;
}

.welcome-title .back-text {
    color:#0E2072;;
}

.login-form {
    width: 100%;
}

.form-group {
    width: 100%;
    margin-bottom: 20px;
}

.form-input {
    width: 100%;
    height: 50px;
    padding: 15px 20px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    background: #F8F9FA;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 400;
    color: #6B7280;
    transition: all 0.3s ease;
    outline: none;
}

.form-input:focus {
    border-color: #4F46E5;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-input::placeholder {
    color: #9CA3AF;
    font-weight: 400;
}

.login-btn {
    width: 100%;
    height: 50px;
    background: #000000;
    border: none;
    border-radius: 8px;
    color: #FFFFFF;
    font-family: 'Poppins', sans-serif;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin: 10px 0;
}

.login-btn:hover {
    background: #333333;
}

.forgot-password {
    color: #4F46E5;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    margin-top: 15px;
    transition: color 0.3s ease;
}

.forgot-password:hover {
    color: #3730A3;
    text-decoration: underline;
}

.signup-link {
    color: #6B7280;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 400;
    text-align: center;
    margin-top: 20px;
}

.signup-link a {
    color: #4F46E5;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s ease;
}

.signup-link a:hover {
    color: #3730A3;
    text-decoration: underline;
}

.error-message {
    width: 100%;
    padding: 12px 16px;
    background: #FEE2E2;
    border: 1px solid #FECACA;
    border-radius: 6px;
    color: #DC2626;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    margin-bottom: 20px;
    text-align: center;
}

.success-message {
    width: 100%;
    padding: 12px 16px;
    background: #D1FAE5;
    border: 1px solid #A7F3D0;
    border-radius: 6px;
    color: #065F46;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    margin-bottom: 20px;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-container {
        width: 90%;
        max-width: 400px;
        padding: 50px 40px;
    }
}

@media (max-width: 480px) {
    .login-container {
        padding: 30px 20px;
    }
    .welcome-title {
        font-size: 24px;
    }
}
</style>


   
        <!-- <script src="<?=ROOT?>/assets/js/bootstrap.bundle.min.js" class="astro-vvvwv3sm"></script> -->
<?php require '../app/views/partials/footer.php'; ?>