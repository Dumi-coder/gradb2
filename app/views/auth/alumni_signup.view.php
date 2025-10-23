<?php require '../app/views/partials/header.php'; ?>
    <!-- Password Validation Script -->
    <script src="<?=ROOT?>/assets/js/password-validation.js"></script>

    <!-- Alumni Signup Form -->
    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">Welcome to <span class="brand">GradBridge</span></h1>

          <form class="auth-form"  method="post">

          <?php if(!empty($errors)):?>
            <div class="alert alert-danger">       
                <?= implode("<br>",$errors)?> 
            </div>
            <?php endif;?>

            <input class="input" type="text" name="name" placeholder="Full Name" required />

            <!-- <input class="input" type="text" name="alumni_id" placeholder="Alumni ID" required /> -->

            <input class="input" type="email" name="email" placeholder="Work email address" required />
            
            <input class="input" type="text" name="alumni_id" placeholder="Alumni Membership Number" required />

            <!-- <input class="input" type="text" name="faculty" placeholder="Faculty" required /> -->
            <div>
                <select name="faculty" class="input">
                    <option value="">Select Faculty</option>
                    <option value="UCSC">UCSC</option>
                    <option value="FOA">FOA</option>
                    <option value="FOS">FOS</option>
                    <option value="FOM">FOM</option>
                    <option value="FOMF">FOMF</option>
                    <option value="FOL">FOL</option>
                    <option value="FOE">FOE</option>
                    <option value="FOT">FOT</option>
                    
                </select>
            </div>
            
            <div id="alumni-password-container">
                <input class="input" type="password" id="alumni-password" name="password" placeholder="Password" required />
                <input class="input" type="password" id="alumni-confirm-password" name="confirm_password" placeholder="Confirm password" required />
            </div>
            
            <div class="auth-actions">
              <button type="submit" class="btn btn-primary" style="width:100%">Create Account</button>
            </div>
            
          </form>

          <p class="auth-meta">Already have an account? <a href="<?=ROOT?>/alumni/Auth?action=login">Sign In</a></p>
        </div>
      </div>
    </section>

<?php require '../app/views/partials/footer.php'; ?><!--footer-->


