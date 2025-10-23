// Profile Modals UI Interactions (Demo with hardcoded data)

document.addEventListener('DOMContentLoaded', function() {
  // Student Profile Modal (For Alumni viewing student profiles)
  const studentProfileModal = document.getElementById('studentProfileModal');
  const viewStudentProfileButtons = document.querySelectorAll('.view-student-profile-btn');
  
  // Alumni Profile Modal (For Students viewing alumni profiles)
  const alumniProfileModal = document.getElementById('alumniProfileModal');
  const viewAlumniProfileButtons = document.querySelectorAll('.view-alumni-profile-btn');

  // Close buttons
  const closeButtons = document.querySelectorAll('.profile-modal-close, .close-profile-btn');

  // Open Student Profile Modal
  viewStudentProfileButtons.forEach(button => {
    button.addEventListener('click', function() {
      const studentName = this.getAttribute('data-student-name');
      const studentMajor = this.getAttribute('data-student-major');
      const studentYear = this.getAttribute('data-student-year');
      const studentGPA = this.getAttribute('data-student-gpa');
      const studentEmail = this.getAttribute('data-student-email');
      const studentInterests = this.getAttribute('data-student-interests');

      // Populate modal with student data
      document.getElementById('studentProfileName').textContent = studentName;
      document.getElementById('studentProfileMajor').textContent = studentMajor;
      document.getElementById('studentProfileYear').textContent = studentYear;
      document.getElementById('studentProfileGPA').textContent = studentGPA;
      document.getElementById('studentProfileEmail').textContent = studentEmail;
      document.getElementById('studentProfileInterests').textContent = studentInterests;

      // Show modal
      studentProfileModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  // Open Alumni Profile Modal
  viewAlumniProfileButtons.forEach(button => {
    button.addEventListener('click', function() {
      const alumniName = this.getAttribute('data-alumni-name');
      const alumniTitle = this.getAttribute('data-alumni-title');
      const alumniGraduation = this.getAttribute('data-alumni-graduation');
      const alumniExpertise = this.getAttribute('data-alumni-expertise');
      const alumniCompany = this.getAttribute('data-alumni-company');
      const alumniExperience = this.getAttribute('data-alumni-experience');
      const alumniEmail = this.getAttribute('data-alumni-email');

      // Populate modal with alumni data
      document.getElementById('alumniProfileName').textContent = alumniName;
      document.getElementById('alumniProfileTitle').textContent = alumniTitle;
      document.getElementById('alumniProfileGraduation').textContent = alumniGraduation;
      document.getElementById('alumniProfileExpertise').textContent = alumniExpertise;
      document.getElementById('alumniProfileCompany').textContent = alumniCompany;
      document.getElementById('alumniProfileExperience').textContent = alumniExperience;
      document.getElementById('alumniProfileEmail').textContent = alumniEmail;

      // Show modal
      alumniProfileModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  // Close modal functions
  function closeAllModals() {
    if (studentProfileModal) {
      studentProfileModal.style.display = 'none';
    }
    if (alumniProfileModal) {
      alumniProfileModal.style.display = 'none';
    }
    document.body.style.overflow = 'auto';
  }

  // Close buttons event listeners
  closeButtons.forEach(button => {
    button.addEventListener('click', closeAllModals);
  });

  // Close modal when clicking outside
  if (studentProfileModal) {
    studentProfileModal.addEventListener('click', function(e) {
      if (e.target === studentProfileModal) {
        closeAllModals();
      }
    });
  }

  if (alumniProfileModal) {
    alumniProfileModal.addEventListener('click', function(e) {
      if (e.target === alumniProfileModal) {
        closeAllModals();
      }
    });
  }

  // Close modal on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeAllModals();
    }
  });
});

