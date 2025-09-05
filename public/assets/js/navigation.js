/* navigation.js - Navigation and Header Functionality */

// Smooth scrolling for navigation links
function initSmoothScrolling() {
  const navLinks = document.querySelectorAll('a[href^="#"]');
  
  navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // allow normal nav if href is just '#'
      if (this.getAttribute('href') === '#') return;
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      const targetSection = document.querySelector(targetId);
      
      if (targetSection) {
        targetSection.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Add scroll effect to header
function initHeaderScrollEffect() {
  const header = document.querySelector('.header');
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav');
  let lastScrollY = window.scrollY;

  window.addEventListener('scroll', function() {
    const currentScrollY = window.scrollY;
    
    if (currentScrollY > 100) {
      header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
    } else {
      header.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
    }
    
    lastScrollY = currentScrollY;
  });

  // Mobile menu toggle
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      nav.classList.toggle('open');
    });
    // Close after clicking a link
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
      nav.classList.remove('open');
    }));
  }
}

// Export functions for use in main.js
export { initSmoothScrolling, initHeaderScrollEffect };
