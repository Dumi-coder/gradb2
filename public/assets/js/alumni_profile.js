// Alumni Profile Page JS - extracted from inline script

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '70px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
            console.log('Sidebar toggled:', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        });
        console.log('Profile page sidebar toggle initialized successfully!');
    } else {
        console.error('Required elements not found for sidebar toggle');
    }
});
