/* emotional-points.js - Emotional Points Stacking Animation */

// Emotional Points Stacking Animation
function initEmotionalPointsAnimation() {
  const pointCards = document.querySelectorAll('.point-card');
  
  function handlePointCardsScroll() {
    const scrollY = window.scrollY;
    const emotionalSection = document.querySelector('.emotional-points');
    
    if (!emotionalSection) return;
    
    const sectionTop = emotionalSection.offsetTop;
    const sectionBottom = sectionTop + emotionalSection.offsetHeight;
    const viewportHeight = window.innerHeight;
    
    pointCards.forEach((card, index) => {
      const triggerPoint = sectionTop + (index * viewportHeight * 0.8);
      const nextTriggerPoint = sectionTop + ((index + 1) * viewportHeight * 0.8);
      const isVisible = scrollY >= triggerPoint - viewportHeight * 0.3;
      const isNextVisible = index < pointCards.length - 1 ? scrollY >= nextTriggerPoint - viewportHeight * 0.3 : false;
      
      // Special handling for the last card (third card)
      if (index === pointCards.length - 1) {
        const endTriggerPoint = sectionBottom - viewportHeight * 1.5;
        const shouldPushUp = scrollY >= endTriggerPoint;
        
        if (isVisible && !shouldPushUp) {
          // Third card is active and visible
          card.classList.add('active');
          card.style.transform = 'translateY(0)';
          card.style.opacity = '1';
          card.style.zIndex = 10 + index;
        } else if (isVisible && shouldPushUp) {
          // Third card should be pushed up at the end
          card.classList.add('active');
          const pushOffset = Math.min(100, (scrollY - endTriggerPoint) * 0.8);
          card.style.transform = `translateY(-${pushOffset}px)`;
          card.style.opacity = Math.max(0.3, 1 - (pushOffset / 100));
          card.style.zIndex = 10 + index;
        } else {
          // Third card is not yet visible
          card.classList.remove('active');
          card.style.transform = 'translateY(100px)';
          card.style.opacity = '0';
          card.style.zIndex = 11;
        }
      } else {
        // Handle first and second cards normally
        if (isVisible && !isNextVisible) {
          // Card is active and visible
          card.classList.add('active');
          card.style.transform = 'translateY(0)';
          card.style.opacity = '1';
          card.style.zIndex = 10 + index;
        } else if (isVisible && isNextVisible) {
          // Card should be pushed up by the next card
          card.classList.add('active');
          const pushOffset = Math.min(50, (scrollY - nextTriggerPoint + viewportHeight * 0.3) * 0.5);
          card.style.transform = `translateY(-${pushOffset}px)`;
          card.style.opacity = '1';
          card.style.zIndex = 10 + index;
        } else {
          // Card is not yet visible
          card.classList.remove('active');
          card.style.transform = 'translateY(100px)';
          card.style.opacity = '0';
          card.style.zIndex = 13 - index;
        }
      }
    });
  }
  
  // Add scroll listener for point cards
  window.addEventListener('scroll', handlePointCardsScroll);
  
  // Initial check
  handlePointCardsScroll();
}

// Export functions for use in main.js
export { initEmotionalPointsAnimation };
