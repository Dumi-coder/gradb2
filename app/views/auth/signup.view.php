<!-- <div>
    
    <form method="post">
            <h1>Create account</h1>
            
            <div class="form-group">
                <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>

            <div class="form-group">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>

            <div>
                <label>
                    <input name="terms" type="checkbox" value="1" >Accept terms
                </label>
            </div>
            
            <button class="btn btn-primary" type="submit">Create</button> 
            <a href="<?=ROOT?>/home">Home</a>
            <a href="<?=ROOT?>/login">Login</a>
            
        </form> 
</div> -->

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
                    
                    required
                >
            </div>
            <div class="form-group">
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="University email address" 
                    required
                >
                <div id="email-error" style="color:red;"></div>
            </div>
            <div class="form-group">
                <input 
                    type="student_id" 
                    name="student_id" 
                    class="form-input" 
                    placeholder="University ID" 
                    required
                >
                <div id="student-id-error" style="color:red;"></div>
            </div>
            <div class="form-group">
                <input 
                    type="faculty" 
                    name="faculty" 
                    class="form-input" 
                    placeholder="Faculty" 
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
            

            <!-- Add this in your signup.view.php above </form> -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Email real-time validation
            document.querySelector('input[name="email"]').addEventListener('blur', function() {
                let email = this.value;
                fetch('<?=ROOT?>/ajax/validate_email.php?email=' + encodeURIComponent(email))
                    .then(response => response.json())
                    .then(data => {
                        let errorDiv = document.getElementById('email-error');
                        if(data.error) {
                            errorDiv.textContent = data.error;
                        } else {
                            errorDiv.textContent = '';
                        }
                    });
            });
        
            // Student ID real-time validation
            document.querySelector('input[name="student_id"]').addEventListener('blur', function() {
                let studentId = this.value;
                fetch('<?=ROOT?>/ajax/validate_student_id.php?student_id=' + encodeURIComponent(studentId))
                    .then(response => response.json())
                    .then(data => {
                        let errorDiv = document.getElementById('student-id-error');
                        if(data.error) {
                            errorDiv.textContent = data.error;
                        } else {
                            errorDiv.textContent = '';
                        }
                    });
            });
        });
        </script>

        </form>
        
        <div class="signup-link">
            Already have an account?  <a href="<?=ROOT?>/login">Sing In</a>
        </div>
    </div>
</main> 

<?php require '../app/views/partials/footer.php'; ?>

