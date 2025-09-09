// /* feature-cards.js - Feature Cards Animations and Interactions */

// // Enhanced feature cards with pop-up effect on hover
// function initFeatureCards() {
//   const featureCards = document.querySelectorAll('.feature-card');
  
//   featureCards.forEach(card => {
//     // Mouse enter - pop up effect
//     card.addEventListener('mouseenter', function() {
//       this.style.transform = 'translateY(-15px) scale(1.02)';
//       this.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.15)';
//       this.style.borderColor = 'var(--primary)';
      
//       // Add a subtle glow effect
//       this.style.background = 'linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(33, 102, 217, 0.05))';
//     });
    
//     // Mouse leave - return to normal
//     card.addEventListener('mouseleave', function() {
//       this.style.transform = 'translateY(0) scale(1)';
//       this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
//       this.style.borderColor = 'var(--border)';
//       this.style.background = 'var(--card)';
//     });
//   });
// }

// // Enhanced Intersection Observer for animations with stacking support
// function initFeatureCardAnimations() {
//   const observerOptions = {
//     threshold: 0.1,
//     rootMargin: '0px 0px -50px 0px'
//   };

//   const observer = new IntersectionObserver(function(entries) {
//     entries.forEach(entry => {
//       if (entry.isIntersecting) {
//         // Skip point cards as they have their own animation
//         if (!entry.target.classList.contains('point-card')) {
//           entry.target.style.opacity = '1';
//           entry.target.style.transform = 'translateY(0)';
          
//           // Add staggered animation for feature cards
//           if (entry.target.classList.contains('feature-card')) {
//             const cards = document.querySelectorAll('.feature-card');
//             const index = Array.from(cards).indexOf(entry.target);
//             entry.target.style.transitionDelay = `${index * 0.1}s`;
//           }
//         }
//       }
//     });
//   }, observerOptions);

//   // Observe elements for animation (excluding point cards)
//   const animatedElements = document.querySelectorAll('.feature-card');
//   animatedElements.forEach(el => {
//     el.style.opacity = '0';
//     el.style.transform = 'translateY(30px)';
//     el.style.transition = 'opacity 0.6s ease, transform 0.6s ease, box-shadow 0.4s ease, border-color 0.4s ease';
//     observer.observe(el);
//   });
// }

// // Export functions for use in main.js
// export { initFeatureCards, initFeatureCardAnimations };
