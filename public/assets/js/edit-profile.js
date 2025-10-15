// // edit-profile.js - Edit Profile Component

// class EditProfileComponent {
//     constructor() {
//         this.profileData = {
//             name: 'Sarah Johnson',
//             email: 'sarah.johnson@university.edu',
//             major: 'Computer Science',
//             year: '3',
//             bio: 'Passionate computer science student with interest in AI/ML and software development.',
//             phone: '+1 (555) 123-4567',
//             location: 'New York, NY',
//             linkedin: 'linkedin.com/in/sarahjohnson',
//             github: 'github.com/sarahjohnson'
//         };
        
//         this.init();
//     }
    
//     init() {
//         this.createEditProfileModal();
//         this.initializeProfilePictureUpload();
//         this.bindEvents();
//         this.loadSavedProfilePicture();
//     }
    
//     createEditProfileModal() {
//         // Remove existing modal if any
//         const existingModal = document.querySelector('.edit-profile-modal-overlay');
//         if (existingModal) {
//             existingModal.remove();
//         }
        
//         const modalOverlay = document.createElement('div');
//         modalOverlay.className = 'edit-profile-modal-overlay';
//         modalOverlay.innerHTML = `
//             <div class="edit-profile-modal">
//                 <div class="edit-profile-header">
//                     <h3>Edit Profile</h3>
//                     <button class="edit-profile-close" id="editProfileClose">&times;</button>
//                 </div>
//                 <div class="edit-profile-body">
//                     <form class="edit-profile-form" id="editProfileForm">
//                         <!-- Profile Picture Section -->
//                         <div class="profile-picture-section">
//                             <h4>Profile Picture</h4>
//                             <div class="profile-avatar-container">
//                                 <div class="profile-avatar" id="modalProfileAvatar">
//                                     <img src="" alt="Profile Picture" id="modalProfileImage" style="display: none;">
//                                     <i class="fas fa-user-graduate" id="modalDefaultAvatar"></i>
//                                 </div>
//                                 <div class="avatar-upload-overlay">
//                                     <label for="modalProfilePictureInput" class="avatar-upload-btn" title="Change Profile Picture">
//                                         <i class="fas fa-camera"></i>
//                                     </label>
//                                     <input type="file" id="modalProfilePictureInput" accept="image/*" style="display: none;">
//                                 </div>
//                             </div>
//                         </div>
                        
//                         <!-- Personal Information -->
//                         <div class="form-row">
//                             <div class="form-group">
//                                 <label for="profile-name">Full Name</label>
//                                 <input type="text" id="profile-name" value="${this.profileData.name}" required>
//                             </div>
//                             <div class="form-group">
//                                 <label for="profile-email">Email</label>
//                                 <input type="email" id="profile-email" value="${this.profileData.email}" required>
//                             </div>
//                         </div>
                        
//                         <div class="form-row">
//                             <div class="form-group">
//                                 <label for="profile-major">Major</label>
//                                 <input type="text" id="profile-major" value="${this.profileData.major}" required>
//                             </div>
//                             <div class="form-group">
//                                 <label for="profile-year">Year</label>
//                                 <select id="profile-year">
//                                     <option value="1" ${this.profileData.year === '1' ? 'selected' : ''}>Year 1</option>
//                                     <option value="2" ${this.profileData.year === '2' ? 'selected' : ''}>Year 2</option>
//                                     <option value="3" ${this.profileData.year === '3' ? 'selected' : ''}>Year 3</option>
//                                     <option value="4" ${this.profileData.year === '4' ? 'selected' : ''}>Year 4</option>
//                                 </select>
//                             </div>
//                         </div>
                        
//                         <div class="form-row">
//                             <div class="form-group">
//                                 <label for="profile-phone">Phone Number</label>
//                                 <input type="tel" id="profile-phone" value="${this.profileData.phone}">
//                             </div>
//                             <div class="form-group">
//                                 <label for="profile-location">Location</label>
//                                 <input type="text" id="profile-location" value="${this.profileData.location}">
//                             </div>
//                         </div>
                        
//                         <div class="form-row">
//                             <div class="form-group">
//                                 <label for="profile-linkedin">LinkedIn</label>
//                                 <input type="url" id="profile-linkedin" value="${this.profileData.linkedin}" placeholder="linkedin.com/in/username">
//                             </div>
//                             <div class="form-group">
//                                 <label for="profile-github">GitHub</label>
//                                 <input type="url" id="profile-github" value="${this.profileData.github}" placeholder="github.com/username">
//                             </div>
//                         </div>
                        
//                         <div class="form-group full-width">
//                             <label for="profile-bio">Bio</label>
//                             <textarea id="profile-bio" rows="4" placeholder="Tell us about yourself...">${this.profileData.bio}</textarea>
//                             <small>Brief description of your interests, goals, and experience</small>
//                         </div>
                        
//                         <div class="edit-profile-actions">
//                             <button type="button" class="btn btn-outline" id="editProfileCancel">Cancel</button>
//                             <button type="submit" class="btn btn-primary">Save Changes</button>
//                         </div>
//                     </form>
//                 </div>
//             </div>
//         `;
        
//         // Add modal styles if not already present
//         if (!document.querySelector('#edit-profile-modal-styles')) {
//             this.addModalStyles();
//         }
        
//         document.body.appendChild(modalOverlay);
//         this.modalOverlay = modalOverlay;
//     }
    
//     addModalStyles() {
//         const style = document.createElement('style');
//         style.id = 'edit-profile-modal-styles';
//         style.textContent = `
//             .edit-profile-modal-overlay {
//                 position: fixed;
//                 top: 0;
//                 left: 0;
//                 width: 100%;
//                 height: 100%;
//                 background-color: rgba(0, 0, 0, 0.5);
//                 display: flex;
//                 align-items: center;
//                 justify-content: center;
//                 z-index: 10000;
//                 animation: fadeIn 0.3s ease;
//             }
            
//             @keyframes fadeIn {
//                 from { opacity: 0; }
//                 to { opacity: 1; }
//             }
//         `;
//         document.head.appendChild(style);
//     }
    
//     initializeProfilePictureUpload() {
//         const profilePictureInput = document.getElementById('modalProfilePictureInput');
//         const profileImage = document.getElementById('modalProfileImage');
//         const defaultAvatar = document.getElementById('modalDefaultAvatar');
        
//         if (profilePictureInput) {
//             profilePictureInput.addEventListener('change', (e) => {
//                 const file = e.target.files[0];
                
//                 if (file) {
//                     // Validate file type
//                     if (!file.type.startsWith('image/')) {
//                         this.showMessage('Please select a valid image file.', 'error');
//                         return;
//                     }
                    
//                     // Validate file size (max 5MB)
//                     if (file.size > 5 * 1024 * 1024) {
//                         this.showMessage('Image file size must be less than 5MB.', 'error');
//                         return;
//                     }
                    
//                     const reader = new FileReader();
                    
//                     reader.onload = (e) => {
//                         profileImage.src = e.target.result;
//                         profileImage.style.display = 'block';
//                         defaultAvatar.style.display = 'none';
                        
//                         // Update main profile picture as well
//                         this.updateMainProfilePicture(e.target.result);
                        
//                         // Save to localStorage for persistence
//                         localStorage.setItem('profilePicture', e.target.result);
                        
//                         // Show success message
//                         this.showMessage('Profile picture updated successfully!', 'success');
//                     };
                    
//                     reader.readAsDataURL(file);
//                 }
//             });
//         }
//     }
    
//     updateMainProfilePicture(imageSrc) {
//         const mainProfileImage = document.getElementById('profileImage');
//         const mainDefaultAvatar = document.getElementById('defaultAvatar');
        
//         if (mainProfileImage && mainDefaultAvatar) {
//             mainProfileImage.src = imageSrc;
//             mainProfileImage.style.display = 'block';
//             mainDefaultAvatar.style.display = 'none';
//         }
//     }
    
//     loadSavedProfilePicture() {
//         const profileImage = document.getElementById('modalProfileImage');
//         const defaultAvatar = document.getElementById('modalDefaultAvatar');
//         const savedPicture = localStorage.getItem('profilePicture');
        
//         if (savedPicture && profileImage && defaultAvatar) {
//             profileImage.src = savedPicture;
//             profileImage.style.display = 'block';
//             defaultAvatar.style.display = 'none';
//         }
//     }
    
//     bindEvents() {
//         // Close modal events
//         const closeBtn = document.getElementById('editProfileClose');
//         const cancelBtn = document.getElementById('editProfileCancel');
//         const modalOverlay = this.modalOverlay;
        
//         if (closeBtn) {
//             closeBtn.addEventListener('click', () => this.closeModal());
//         }
        
//         if (cancelBtn) {
//             cancelBtn.addEventListener('click', () => this.closeModal());
//         }
        
//         if (modalOverlay) {
//             modalOverlay.addEventListener('click', (e) => {
//                 if (e.target === modalOverlay) {
//                     this.closeModal();
//                 }
//             });
//         }
        
//         // Form submission
//         const form = document.getElementById('editProfileForm');
//         if (form) {
//             form.addEventListener('submit', (e) => this.handleFormSubmit(e));
//         }
        
//         // Close with Escape key
//         document.addEventListener('keydown', (e) => {
//             if (e.key === 'Escape' && this.modalOverlay) {
//                 this.closeModal();
//             }
//         });
//     }
    
//     handleFormSubmit(e) {
//         e.preventDefault();
        
//         // Collect form data
//         const formData = {
//             name: document.getElementById('profile-name').value,
//             email: document.getElementById('profile-email').value,
//             major: document.getElementById('profile-major').value,
//             year: document.getElementById('profile-year').value,
//             phone: document.getElementById('profile-phone').value,
//             location: document.getElementById('profile-location').value,
//             linkedin: document.getElementById('profile-linkedin').value,
//             github: document.getElementById('profile-github').value,
//             bio: document.getElementById('profile-bio').value
//         };
        
//         // Update profile data
//         this.profileData = { ...this.profileData, ...formData };
        
//         // Update main profile display
//         this.updateMainProfileDisplay(formData);
        
//         // Save to localStorage
//         localStorage.setItem('profileData', JSON.stringify(this.profileData));
        
//         // Show success message
//         this.showMessage('Profile updated successfully!', 'success');
        
//         // Close modal
//         setTimeout(() => {
//             this.closeModal();
//         }, 1500);
//     }
    
//     updateMainProfileDisplay(data) {
//         // Update main profile name
//         const profileName = document.querySelector('.profile-name');
//         if (profileName) {
//             profileName.textContent = data.name;
//         }
        
//         // Update main profile email
//         const profileEmail = document.querySelector('.profile-email');
//         if (profileEmail) {
//             profileEmail.textContent = data.email;
//         }
        
//         // Update main profile major and year
//         const profileMajor = document.querySelector('.profile-major');
//         if (profileMajor) {
//             profileMajor.textContent = `${data.major} • Year ${data.year}`;
//         }
        
//         // Update welcome text
//         const welcomeText = document.querySelector('.welcome-text');
//         if (welcomeText) {
//             const studentNameSpan = welcomeText.querySelector('.student-name');
//             if (studentNameSpan) {
//                 studentNameSpan.textContent = data.name;
//             }
//         }
        
//         // Update student role
//         const studentRole = document.querySelector('.student-role');
//         if (studentRole) {
//             studentRole.textContent = `${data.major} • Year ${data.year}`;
//         }
//     }
    
//     showMessage(message, type = 'info') {
//         // Remove existing messages
//         const existingMessages = document.querySelectorAll('.profile-update-message');
//         existingMessages.forEach(msg => msg.remove());
        
//         const messageDiv = document.createElement('div');
//         messageDiv.className = `profile-update-message profile-update-message-${type}`;
//         messageDiv.textContent = message;
        
//         // Add type-specific styling
//         if (type === 'error') {
//             messageDiv.style.backgroundColor = '#ef4444';
//         } else if (type === 'success') {
//             messageDiv.style.backgroundColor = '#10b981';
//         }
        
//         document.body.appendChild(messageDiv);
        
//         // Remove message after 3 seconds
//         setTimeout(() => {
//             messageDiv.style.animation = 'slideOutRight 0.3s ease';
//             setTimeout(() => {
//                 if (messageDiv.parentNode) {
//                     messageDiv.parentNode.removeChild(messageDiv);
//                 }
//             }, 300);
//         }, 3000);
//     }
    
//     closeModal() {
//         if (this.modalOverlay) {
//             this.modalOverlay.remove();
//             this.modalOverlay = null;
//         }
//     }
    
//     show() {
//         if (!this.modalOverlay) {
//             this.createEditProfileModal();
//             this.bindEvents();
//         }
//     }
// }

// // Export for use in other files
// if (typeof module !== 'undefined' && module.exports) {
//     module.exports = EditProfileComponent;
// } else {
//     window.EditProfileComponent = EditProfileComponent;
// }
