<?php require '../app/views/partials/header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/auth.css?v=<?=time()?>">
    <!-- Password Validation Script -->
    <script src="<?=ROOT?>/assets/js/password-validation.js"></script>

  <style>
    .auth-form .input {
      margin-bottom: 8px;
    }
    #student-password-container .input {
      margin-bottom: 12px;
    }
    .auth-actions {
      margin-top: 4px;
    }
  </style>
    
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
                <input class="input" type="password" id="student-password" name="password" placeholder="Password" required />
                <input class="input" type="password" id="student-confirm-password" name="confirm_password" placeholder="Confirm password" required />

                <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem; margin-top:-4px;">
                  <input type="checkbox" class="js-toggle-password" />
                  Show password
                </label>
            </div>

            <div class="auth-actions">
              <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Create Account</button>
            </div>

          </form>

          <p class="auth-meta">Already have an account? <a href="<?=ROOT?>/login">Sign In</a></p>

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
    <script>
      (function() {
        const form = document.querySelector('.auth-form');
        if (!form) {
          return;
        }
        const toggle = form.querySelector('.js-toggle-password');
        if (!toggle) {
          return;
        }
        const fields = form.querySelectorAll('input[type="password"]');
        toggle.addEventListener('change', function() {
          fields.forEach(function(field) {
            field.type = toggle.checked ? 'text' : 'password';
          });
        });
      })();
    </script>
    <script src="<?=ROOT?>/assets/js/student-signup.js"></script>

<?php require '../app/views/partials/footer.php'; ?><!--footer-->



