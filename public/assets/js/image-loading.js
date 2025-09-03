/* image-loading.js - Image Loading Animations and Effects */

// Add loading animation for images
function initImageLoadingEffects() {
  const images = document.querySelectorAll('img');
  
  images.forEach(img => {
    img.style.opacity = '0';
    img.style.transition = 'opacity 0.5s ease';
    
    img.addEventListener('load', function() {
      this.style.opacity = '1';
    });
    
    // If image is already loaded
    if (img.complete) {
      img.style.opacity = '1';
    }
  });
}

// Export functions for use in main.js
export { initImageLoadingEffects };
