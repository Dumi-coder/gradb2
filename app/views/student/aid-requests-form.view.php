<?php 
$page_title = "Aid Requests";
$page_subtitle = "Manage your financial aid requests";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-request-form.css">

<style>
  /* Center the form without sidebar */
  .aid-form-container {
    max-width: 900px;
    margin: 0 auto;
    padding: var(--spacing-lg);
  }
  
  /* Ensure no sidebar offset */
  body {
    margin: 0;
    padding: 0;
  }
</style>

<div class="aid-form-container">
      <!-- Main Content Area -->
      <main>
        <!-- Form Progress -->
        <section class="dashboard-section progress-section">
          <div class="section-header">
            <h2 class="card-title">Application Progress</h2>
          </div>
          
          <div class="progress-steps">
            <div class="step active" data-step="1">
              <div class="step-number">1</div>
              <div class="step-label">Personal Info</div>
            </div>
            <div class="step" data-step="2">
              <div class="step-number">2</div>
              <div class="step-label">Aid Details</div>
            </div>
            <div class="step" data-step="3">
              <div class="step-number">3</div>
              <div class="step-label">Documents</div>
            </div>
            <div class="step" data-step="4">
              <div class="step-number">4</div>
              <div class="step-label">Review</div>
            </div>
          </div>
        </section>

        <!-- Application Form -->
        <section class="dashboard-section form-section">
          <form class="aid-request-form" id="aidRequestForm">
            <!-- Step 1: Personal Information -->
            <div class="form-step active" data-step="1">
              <div class="step-header">
                <h3 class="step-title">Personal Information</h3>
                <p class="step-description">Please provide your basic personal details</p>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label for="firstName">First Name *</label>
                  <input type="text" id="firstName" name="firstName" required>
                </div>

                <div class="form-group">
                  <label for="lastName">Last Name *</label>
                  <input type="text" id="lastName" name="lastName" required>
                </div>

                <div class="form-group">
                  <label for="studentId">Student Registration Number *</label>
                  <input type="text" id="studentId" name="studentId" required placeholder="2021/CS/077" pattern="[0-9]{4}/[A-Z]+/[0-9]{3}">
                  <small style="color: var(--muted-foreground); font-size: var(--font-xs); margin-top: 4px; display: block;">Format: Year/Faculty/Number (e.g., 2021/CS/077 or 2021/S/077)</small>
                </div>

                <div class="form-group">
                  <label for="email">Email Address *</label>
                  <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                  <label for="phone">Phone Number *</label>
                  <div style="display: flex; gap: 8px; align-items: center;">
                    <span style="padding: 0.75rem; background-color: var(--background); border: 1px solid var(--border); border-radius: var(--radius-md); font-weight: 500;">+94</span>
                    <input type="tel" id="phone" name="phone" required placeholder="771234567" pattern="[0-9]{9}" maxlength="9" style="flex: 1;">
                  </div>
                  <small style="color: var(--muted-foreground); font-size: var(--font-xs); margin-top: 4px; display: block;">Enter 9 digits without leading zero (e.g., 771234567)</small>
                </div>

                <div class="form-group">
                  <label for="major">Degree Program *</label>
                  <input type="text" id="major" name="major" required>
                </div>

                <div class="form-group">
                  <label for="year">Academic Year *</label>
                  <select id="year" name="year" required>
                    <option value="">Select year</option>
                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>
                    <option value="4">Year 4</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="gpa">Current GPA</label>
                  <input type="number" id="gpa" name="gpa" step="0.01" min="0" max="4" placeholder="e.g., 3.5">
                </div>
              </div>
            </div>

            <!-- Step 2: Aid Details -->
            <div class="form-step" data-step="2">
              <div class="step-header">
                <h3 class="step-title">Aid Request Details</h3>
                <p class="step-description">Tell us about the type of assistance you need</p>
              </div>

              <div class="form-group">
                <label for="aidType">Type of Aid Requested *</label>
                <select id="aidType" name="aidType" required onchange="toggleAmountField()">
                  <option value="">Select aid type</option>
                  <option value="laptop">Laptop</option>
                  <option value="textbooks">Textbooks</option>
                  <option value="stationery">Stationery Items</option>
                  <option value="calculator">Scientific Calculator</option>
                  <option value="lab-equipment">Lab Equipment</option>
                  <option value="monetary-medical">Monetary Aid - Medical Expenses</option>
                  <option value="monetary-transport">Monetary Aid - Transportation</option>
                  <option value="monetary-emergency">Monetary Aid - Emergency Fund</option>
                  <option value="other">Other (Specify in reason)</option>
                </select>
              </div>

              <div class="form-group" id="amountGroup">
                <label for="amountRequested">Amount Requested (Rs) <span id="amountRequired">*</span></label>
                <input type="number" id="amountRequested" name="amountRequested" min="1" placeholder="Enter amount in Rupees">
                <small style="color: var(--muted-foreground); font-size: var(--font-xs); margin-top: 4px; display: block;">Required only for monetary aid requests</small>
              </div>

              <div class="form-group">
                <label for="urgency">Urgency Level *</label>
                <select id="urgency" name="urgency" required>
                  <option value="">Select urgency</option>
                  <option value="low">Low - Can wait 2+ weeks</option>
                  <option value="medium">Medium - Needed within 1-2 weeks</option>
                  <option value="high">High - Needed within 3-7 days</option>
                  <option value="urgent">Urgent - Needed within 24-48 hours</option>
                </select>
              </div>

              <div class="form-group">
                <label for="reason">Reason for Request *</label>
                <textarea id="reason" name="reason" rows="4" required placeholder="Please explain your situation and why you need financial assistance..."></textarea>
              </div>

              <div class="form-group">
                <label for="previousAid">Previous Aid Received</label>
                <textarea id="previousAid" name="previousAid" rows="3" placeholder="List any previous financial aid or assistance you have received (optional)"></textarea>
              </div>
            </div>

            <!-- Step 3: Documents -->
            <div class="form-step" data-step="3">
              <div class="step-header">
                <h3 class="step-title">Supporting Documents</h3>
                <p class="step-description">Upload any relevant documents to support your application</p>
              </div>

              <div class="form-group">
                <label>Required Documents</label>
                <div class="document-upload">
                  <div class="upload-item">
                    <label for="financialStatement">Financial Statement/Bank Statement *</label>
                    <div class="file-upload-area" onclick="document.getElementById('financialStatement').click()">
                      <input type="file" id="financialStatement" name="financialStatement" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                      <div class="upload-placeholder">
                        <i class="fas fa-file-upload"></i>
                        <p>Click to upload or drag and drop</p>
                        <small>PDF, JPG, PNG (Max 5MB)</small>
                      </div>
                    </div>
                  </div>

                  <div class="upload-item">
                    <label for="enrollmentProof">Proof of Enrollment *</label>
                    <div class="file-upload-area" onclick="document.getElementById('enrollmentProof').click()">
                      <input type="file" id="enrollmentProof" name="enrollmentProof" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                      <div class="upload-placeholder">
                        <i class="fas fa-file-upload"></i>
                        <p>Click to upload or drag and drop</p>
                        <small>PDF, JPG, PNG (Max 5MB)</small>
                      </div>
                    </div>
                  </div>

                  <div class="upload-item">
                    <label for="additionalDocs">Additional Supporting Documents</label>
                    <div class="file-upload-area" onclick="document.getElementById('additionalDocs').click()">
                      <input type="file" id="additionalDocs" name="additionalDocs" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" multiple>
                      <div class="upload-placeholder">
                        <i class="fas fa-file-upload"></i>
                        <p>Click to upload or drag and drop</p>
                        <small>PDF, JPG, PNG, DOC, DOCX (Max 5MB each)</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 4: Review -->
            <div class="form-step" data-step="4">
              <div class="step-header">
                <h3 class="step-title">Review Your Application</h3>
                <p class="step-description">Please review all information before submitting</p>
              </div>

              <div class="review-section">
                <div class="review-item">
                  <h4>Personal Information</h4>
                  <div class="review-content" id="personalReview"></div>
                </div>

                <div class="review-item">
                  <h4>Aid Request Details</h4>
                  <div class="review-content" id="aidReview"></div>
                </div>

                <div class="review-item">
                  <h4>Supporting Documents</h4>
                  <div class="review-content" id="documentsReview"></div>
                </div>
              </div>

              <div class="agreements-section" style="margin-top: var(--spacing-lg); padding: var(--spacing-md); background-color: var(--background); border: 1px solid var(--border); border-radius: var(--radius-md);">
                <div style="margin-bottom: var(--spacing-md);">
                  <label class="checkbox-label" style="font-size: var(--font-sm); line-height: 1.5;">
                    <input type="checkbox" id="termsAgreement" name="termsAgreement" required>
                    <span class="checkmark"></span>
                    I agree to the <a href="#" onclick="openTermsModal(); return false;" style="color: var(--primary); text-decoration: underline; cursor: pointer;">Terms and Conditions</a> and confirm that all information provided is accurate *
                  </label>
                </div>

                <div>
                  <label class="checkbox-label" style="font-size: var(--font-sm); line-height: 1.5;">
                    <input type="checkbox" id="privacyAgreement" name="privacyAgreement" required>
                    <span class="checkmark"></span>
                    I consent to the processing of my personal data as described in the <a href="#" onclick="openPrivacyModal(); return false;" style="color: var(--primary); text-decoration: underline; cursor: pointer;">Privacy Policy</a> *
                  </label>
                </div>
              </div>
            </div>

            <!-- Form Navigation -->
            <div class="form-navigation">
              <button type="button" class="btn btn-outline" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                <i class="fas fa-arrow-left"></i>
                <span>Previous</span>
              </button>
              
              <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                <span>Next</span>
                <i class="fas fa-arrow-right"></i>
              </button>
              
              <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                <i class="fas fa-paper-plane"></i>
                <span>Submit Application</span>
              </button>
            </div>
          </form>
        </section>
      </main>
</div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
      <div style="background: white; border-radius: 10px; padding: 30px; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h2 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 24px;">Terms and Conditions</h2>
        <div style="color: #555; line-height: 1.6; font-size: 14px;">
          <h3>1. Acceptance of Terms</h3>
          <p>By submitting an aid request through GradBridge, you agree to abide by these terms and conditions.</p>
          
          <h3>2. Accuracy of Information</h3>
          <p>You confirm that all information provided in this application is true, accurate, and complete to the best of your knowledge. Providing false or misleading information may result in the rejection of your application or termination of aid.</p>
          
          <h3>3. Verification Process</h3>
          <p>Your application will be reviewed by authorized counselors and administrators. You agree to provide additional documentation if requested during the verification process.</p>
          
          <h3>4. Aid Distribution</h3>
          <p>Aid is provided based on need, availability of resources, and approval by alumni donors. GradBridge does not guarantee approval of all aid requests.</p>
          
          <h3>5. Proper Use of Aid</h3>
          <p>You agree to use the aid received solely for the purpose stated in your application. Misuse of funds or resources may result in disciplinary action.</p>
          
          <h3>6. Updates and Communication</h3>
          <p>You agree to receive notifications regarding your application status via email and to respond promptly to any requests for information.</p>
        </div>
        <div style="margin-top: 20px; text-align: right;">
          <button onclick="closeTermsModal()" class="btn btn-primary">Close</button>
        </div>
      </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
      <div style="background: white; border-radius: 10px; padding: 30px; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h2 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 24px;">Privacy Policy</h2>
        <div style="color: #555; line-height: 1.6; font-size: 14px;">
          <h3>1. Information Collection</h3>
          <p>We collect personal information including your name, student registration number, contact details, academic information, and financial documentation necessary to process your aid request.</p>
          
          <h3>2. Use of Information</h3>
          <p>Your information will be used to:</p>
          <ul>
            <li>Verify your eligibility for financial aid</li>
            <li>Match you with appropriate alumni donors</li>
            <li>Process and distribute aid</li>
            <li>Communicate with you regarding your application</li>
            <li>Maintain records for institutional purposes</li>
          </ul>
          
          <h3>3. Information Sharing</h3>
          <p>Your personal information will only be shared with:</p>
          <ul>
            <li>Authorized counselors and administrators</li>
            <li>Alumni donors who approve your aid request</li>
            <li>University officials as required by institutional policy</li>
          </ul>
          
          <h3>4. Data Security</h3>
          <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p>
          
          <h3>5. Your Rights</h3>
          <p>You have the right to access, correct, or request deletion of your personal data. Contact our support team for any privacy-related concerns.</p>
          
          <h3>6. Data Retention</h3>
          <p>We retain your information for the duration of your aid request and as required by university policies and legal obligations.</p>
        </div>
        <div style="margin-top: 20px; text-align: right;">
          <button onclick="closePrivacyModal()" class="btn btn-primary">Close</button>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/aid-request-form.js"></script>
    <script>
      // Toggle amount field requirement based on aid type
      function toggleAmountField() {
        const aidType = document.getElementById('aidType').value;
        const amountInput = document.getElementById('amountRequested');
        const amountRequired = document.getElementById('amountRequired');
        const amountGroup = document.getElementById('amountGroup');
        
        // Check if selected aid type is monetary
        const isMonetary = aidType.startsWith('monetary-');
        
        if (isMonetary) {
          amountInput.required = true;
          amountRequired.style.display = 'inline';
          amountGroup.style.opacity = '1';
        } else {
          amountInput.required = false;
          amountInput.value = ''; // Clear the value
          amountRequired.style.display = 'none';
          amountGroup.style.opacity = '0.6';
        }
      }
      
      // Initialize on page load
      document.addEventListener('DOMContentLoaded', function() {
        toggleAmountField();
      });
      
      // Terms and Conditions Modal Functions
      function openTermsModal() {
        document.getElementById('termsModal').style.display = 'flex';
      }
      
      function closeTermsModal() {
        document.getElementById('termsModal').style.display = 'none';
      }
      
      // Privacy Policy Modal Functions
      function openPrivacyModal() {
        document.getElementById('privacyModal').style.display = 'flex';
      }
      
      function closePrivacyModal() {
        document.getElementById('privacyModal').style.display = 'none';
      }
      
      // Close modals when clicking outside
      document.getElementById('termsModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeTermsModal();
        }
      });
      
      document.getElementById('privacyModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closePrivacyModal();
        }
      });
    </script>
  </body>
</html>
