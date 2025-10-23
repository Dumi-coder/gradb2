<?php 
$page_title = "Aid Requests";
$page_subtitle = "Manage your financial aid requests";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-request-form.css">

<div class="dashboard-container">

      <!-- Main Content Area -->
      <main class="main-content">
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
                  <label for="studentId">Student ID *</label>
                  <input type="text" id="studentId" name="studentId" required>
                </div>

                <div class="form-group">
                  <label for="email">Email Address *</label>
                  <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                  <label for="phone">Phone Number *</label>
                  <input type="number" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                  <label for="major">Major/Program *</label>
                  <input type="text" id="major" name="major" required>
                </div>

                <div class="form-group">
                  <label for="year">Academic Year *</label>
                  <select id="year" name="year" required>
                    <option value="">Select year</option>
                    <option value="freshman">Freshman</option>
                    <option value="sophomore">Sophomore</option>
                    <option value="junior">Junior</option>
                    <option value="senior">Senior</option>
                    <option value="graduate">Graduate</option>
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
                <select id="aidType" name="aidType" required>
                  <option value="">Select aid type</option>
                  <option value="emergency">Emergency Fund</option>
                  <option value="tuition">Tuition Assistance</option>
                  <option value="textbooks">Textbook Support</option>
                  <option value="technology">Technology Grant</option>
                  <option value="meal-plan">Meal Plan Subsidy</option>
                  <option value="transportation">Transportation</option>
                  <option value="medical">Medical Expenses</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="form-group">
                <label for="amountRequested">Amount Requested *</label>
                <input type="number" id="amountRequested" name="amountRequested" min="1" required placeholder="Enter amount in USD">
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

              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" id="termsAgreement" name="termsAgreement" required>
                  <span class="checkmark"></span>
                  I agree to the <a href="#" target="_blank">Terms and Conditions</a> and confirm that all information provided is accurate *
                </label>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" id="privacyAgreement" name="privacyAgreement" required>
                  <span class="checkmark"></span>
                  I consent to the processing of my personal data as described in the <a href="#" target="_blank">Privacy Policy</a> *
                </label>
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

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/aid-request-form.js"></script>
  </body>
</html>
