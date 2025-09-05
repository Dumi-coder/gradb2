/* css-animations.js - Dynamic CSS Animations and Styles */

// Add CSS animations dynamically
function initCSSAnimations() {
  const style = document.createElement('style');
  style.textContent = `
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      animation: ripple-animation 0.6s linear;
      pointer-events: none;
    }
    
    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    
    @keyframes buttonClick {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(0.95); }
    }
    
    .hero-image {
      animation: heroFloat 6s ease-in-out infinite;
    }
    
    @keyframes heroFloat {
      0%, 100% {
        transform: translateY(0px) translateX(0px);
      }
      25% {
        transform: translateY(-10px) translateX(5px);
      }
      50% {
        transform: translateY(0px) translateX(-5px);
      }
      75% {
        transform: translateY(10px) translateX(3px);
      }
    }
    
    .feature-card {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(33, 102, 217, 0.05), rgba(33, 102, 217, 0.1));
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 1;
      pointer-events: none;
    }
    
    .feature-card:hover::before {
      opacity: 1;
    }
    
    .feature-card > * {
      position: relative;
      z-index: 2;
    }
  `;

  document.head.appendChild(style);
}

// Export functions for use in main.js
export { initCSSAnimations };
