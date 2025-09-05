/* buttons.js - Button Interactions and Effects */

// Add click handlers for buttons with ripple effect
function initButtonRippleEffect() {
  const buttons = document.querySelectorAll('.btn');
  
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      // Add ripple effect
      const ripple = document.createElement('span');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;
      
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = x + 'px';
      ripple.style.top = y + 'px';
      ripple.classList.add('ripple');
      
      this.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Enhanced button interactions
function initEnhancedButtonInteractions() {
  const allButtons = document.querySelectorAll('.btn');
  
  allButtons.forEach(button => {
    // Add magnetic effect on hover
    button.addEventListener('mouseenter', function(e) {
      this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    });
    
    // Add subtle shake animation on click for feedback
    button.addEventListener('click', function() {
      this.style.animation = 'buttonClick 0.3s ease';
      
      setTimeout(() => {
        this.style.animation = '';
      }, 300);
    });
  });
}

// Export functions for use in main.js
export { initButtonRippleEffect, initEnhancedButtonInteractions };
