// /* scroll-effects.js - Scroll-Based Effects and Background Changes */

// // Add dynamic background color changes on scroll
// function initScrollBackgroundEffects() {
//   const sections = document.querySelectorAll('section');
  
//   window.addEventListener('scroll', function() {
//     const scrollPosition = window.scrollY;
    
//     sections.forEach((section, index) => {
//       const sectionTop = section.offsetTop;
//       const sectionHeight = section.offsetHeight;
      
//       if (scrollPosition >= sectionTop - 100 && scrollPosition < sectionTop + sectionHeight - 100) {
//         // Add subtle color transitions based on current section
//         if (section.classList.contains('hero')) {
//           document.body.style.backgroundColor = 'hsl(0, 0%, 100%)';
//         } else if (section.classList.contains('emotional-points')) {
//           document.body.style.backgroundColor = 'hsl(210, 11%, 98%)';
//         } else if (section.classList.contains('features')) {
//           document.body.style.backgroundColor = 'hsl(0, 0%, 100%)';
//         }
//       }
//     });
//   });
// }

// // Export functions for use in main.js
// export { initScrollBackgroundEffects };
