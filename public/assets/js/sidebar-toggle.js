/**
 * Sidebar Toggle Functionality
 * Handles responsive sidebar collapse/expand behavior
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (!sidebar) {
        console.warn('Sidebar element not found');
        return;
    }

    // Handle window resize for automatic collapse
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            handleResponsiveCollapse();
        }, 150);
    });

    /**
     * Handle automatic collapse based on screen size
     */
    function handleResponsiveCollapse() {
        const screenWidth = window.innerWidth;
        
        // Auto-collapse on smaller screens (below 1024px)
        if (screenWidth < 1024) {
            if (!sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                updateMainContentMargin();
            }
        } else {
            // Auto-expand on larger screens
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                updateMainContentMargin();
            }
        }
    }

    /**
     * Update main content margin based on sidebar state
     */
    function updateMainContentMargin() {
        if (mainContent) {
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '80px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
        }
    }

    // Initialize responsive state
    handleResponsiveCollapse();
});

/**
 * Utility function to check if sidebar is collapsed
 */
window.isSidebarCollapsed = function() {
    const sidebar = document.getElementById('sidebar');
    return sidebar ? sidebar.classList.contains('collapsed') : false;
};
