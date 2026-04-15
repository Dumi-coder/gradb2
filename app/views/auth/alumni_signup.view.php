<?php require '../app/views/partials/header.php'; ?>
    <!-- Password Validation Script -->
    <script src="<?=ROOT?>/assets/js/password-validation.js"></script>

    <style>
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            gap: 10px;
        }
        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d1d5db;
            transition: all 0.3s ease;
        }
        .step-dot.active {
            background: #3b82f6;
            transform: scale(1.3);
        }
        .step-dot.completed {
            background: #10b981;
        }
        .step-title {
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .form-navigation {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .form-navigation .btn {
            flex: 1;
        }
        .btn-back {
            background: #ffffff;
            color: #000000;
            border: 2px solid #e5e7eb;
        }
        .btn-back:hover {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
        }
    </style>

    <!-- Alumni Signup Form -->
    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">Welcome to <span class="brand">GradBridge</span></h1>

          <!-- Step Indicator -->
          <div class="step-indicator">
            <div class="step-dot active" data-step="1"></div>
            <div class="step-dot" data-step="2"></div>
            <div class="step-dot" data-step="3"></div>
          </div>

          <form class="auth-form" method="post" action="" id="alumni-signup-form">

            <!-- Step 1: Academic Information -->
            <div class="form-step active" data-step="1">
              <div class="step-title">Step 1 of 3: Academic Information</div>
              
              <input class="input" type="text" name="alumni_id" placeholder="Alumni Membership Number *" required />

              <div>
                <select name="faculty" class="input" required>
                  <option value="">Select Faculty *</option>
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

              <input class="input" type="number" name="graduated_year" placeholder="Graduation Year (e.g., 2020) *" min="1900" max="2025" required />

              <div class="form-navigation">
                <button type="button" class="btn btn-primary" onclick="nextStep(1)" style="width:100%">Next</button>
              </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div class="form-step" data-step="2">
              <div class="step-title">Step 2 of 3: Personal Information</div>
              
              <input class="input" type="text" name="name" placeholder="Full Name *" required />

              <input class="input" type="email" name="email" placeholder="Work Email Address *" required />

              <input class="input" type="tel" name="mobile" placeholder="Mobile Phone Number *" required />

              <input class="input" type="text" name="degrees" placeholder="Degree (e.g., BSc Computer Science) *" required />

              <div class="form-navigation">
                <button type="button" class="btn btn-back" onclick="prevStep(2)">Back</button>
                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
              </div>
            </div>

            <!-- Step 3: Professional Information & Password -->
            <div class="form-step" data-step="3">
              <div class="step-title">Step 3 of 3: Professional Details & Security</div>
              
              <input class="input" type="text" name="current_workplace" placeholder="Current Workplace *" required />

              <input class="input" type="text" name="expertise_area" placeholder="Area of Expertise *" required />

              <div>
                <select name="is_mentor" class="input" required>
                  <option value="">Are you willing to be a mentor? *</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
              
              <div id="alumni-password-container">
                <input class="input" type="password" id="alumni-password" name="password" placeholder="Password *" required />
                <input class="input" type="password" id="alumni-confirm-password" name="confirm_password" placeholder="Confirm Password *" required />
              </div>

              <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" class="js-toggle-password" />
                Show password
              </label>

              <div class="form-navigation">
                <button type="button" class="btn btn-back" onclick="prevStep(3)">Back</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
              </div>
            </div>
            
          </form>

          <p class="auth-meta">Already have an account? <a href="<?=ROOT?>/alumni/Auth?action=login">Sign In</a></p>
        </div>
      </div>
    </section>

    <script>
        let currentStep = 1;

        function nextStep(step) {
            // Validate current step fields
            const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#ef4444';
                    setTimeout(() => {
                        input.style.borderColor = '';
                    }, 2000);
                } else {
                    input.style.borderColor = '';
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields');
                return;
            }

            // Move to next step
            currentStep = step + 1;
            updateStepDisplay();
        }

        function prevStep(step) {
            currentStep = step - 1;
            updateStepDisplay();
        }

        function updateStepDisplay() {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.remove('active');
            });

            // Show current step
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');

            // Update step indicators
            document.querySelectorAll('.step-dot').forEach(dot => {
                const dotStep = parseInt(dot.dataset.step);
                dot.classList.remove('active', 'completed');
                
                if (dotStep === currentStep) {
                    dot.classList.add('active');
                } else if (dotStep < currentStep) {
                    dot.classList.add('completed');
                }
            });
        }

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
    <script src="<?=ROOT?>/assets/js/alumni-signup.js"></script>

<?php require '../app/views/partials/footer.php'; ?><!--footer-->


