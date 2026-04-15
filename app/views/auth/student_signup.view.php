<?php require '../app/views/partials/header.php'; ?>
    <!-- Password Validation Script -->
    <script src="<?=ROOT?>/assets/js/password-validation.js"></script>
    
    <!-- Signup Form -->
    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">Welcome to <span class="brand">GradBridge</span></h1>
          <form class="auth-form" method="post" action="" id="student-signup-form">

            <input class="input" type="text" name="name" placeholder="Full Name" required />

            <input class="input" type="email" name="email" placeholder="University email address" required />

            <input class="input" type="text" name="student_id" placeholder="University ID" required />

            <input class="input" type="text" name="academic_year" placeholder="Academic Year" required />

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

            <div id="student-password-container">
              <input class="input" type="password" id="student-password" name="password" placeholder="Password" minlength="8" autocomplete="new-password" required />
              <input class="input" type="password" id="student-confirm-password" name="confirm_password" placeholder="Confirm password" minlength="8" autocomplete="new-password" required />
              <small style="display:block; margin-top:8px; color:#6b7280;">Password must be at least 8 characters long and include a letter, a number, and a special character.</small>
            </div>

            <div class="auth-actions">
              <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Create Account</button>
            </div>

          </form>

          <p class="auth-meta">Already have an account? <a href="<?=ROOT?>/student/Auth?action=login">Sign In</a></p>

        </div>
      </div>
    </section>
    
    <script>
        // Ensure form doesn't submit normally - run immediately
        (function() {
            const form = document.querySelector('.auth-form');
            if (form) {
                form.onsubmit = function(e) {
                    e.preventDefault();
                    return false;
                };
            }
        })();
    </script>
    <script src="<?=ROOT?>/assets/js/student-signup.js"></script>

<?php require '../app/views/partials/footer.php'; ?><!--footer-->



