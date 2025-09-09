// /* hero.js - Hero Section Animations and Effects */

// // Hero image subtle floating animation enhancement
// function initHeroImageEffects() {
//   const heroImage = document.querySelector('.hero-image');
//   if (heroImage) {
//     let mouseX = 0;
//     let mouseY = 0;
    
//     // Track mouse movement for subtle parallax effect
//     document.addEventListener('mousemove', function(e) {
//       mouseX = (e.clientX / window.innerWidth) * 10 - 5;
//       mouseY = (e.clientY / window.innerHeight) * 10 - 5;
//     });
    
//     // Apply subtle movement based on mouse position
//     setInterval(() => {
//       if (heroImage) {
//         const currentTransform = heroImage.style.transform || '';
//         heroImage.style.transform = `${currentTransform} translateX(${mouseX}px) translateY(${mouseY}px)`;
//       }
//     }, 50);
//   }
// }

// // Export functions for use in main.js
// export { initHeroImageEffects };
