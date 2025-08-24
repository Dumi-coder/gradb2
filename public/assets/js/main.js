// Wait for the entire page to load before running the script
document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('roleModal');
    if (!modal) return; // Stop if modal doesn't exist on the page

    // Get all the elements we need to interact with
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    
    const modalTitle = document.getElementById('modalTitle');
    const modalPrompt = document.getElementById('modalPrompt');
    const studentLink = document.getElementById('studentLink');
    const alumniLink = document.getElementById('alumniLink');
    
    
    const ROOT_URL = 'http://localhost/gradb2/public'; 

    /**
     * Opens and configures the modal based on the action type ('login' or 'register').
     * @param {string} actionType - The type of action, either 'login' or 'register'.
     */
    const openModal = (actionType) => {
        if (actionType === 'login') {
            modalTitle.textContent = 'Login As';
            modalPrompt.textContent = 'Please select your role to proceed.';
    
            studentLink.href = `${ROOT_URL}/student/login`; 
            alumniLink.href = `${ROOT_URL}/alumni/login`;
        } else { // It's a 'register' action
            modalTitle.textContent = 'Register As';
            modalPrompt.textContent = 'Please select your role to begin registration.';
            
            studentLink.href = `${ROOT_URL}/student/signup`;
            alumniLink.href = `${ROOT_URL}/alumni/signup`;
        }
        // Show the modal with a smooth fade-in
        modal.classList.add('active');
    };

    /**
     * Closes the modal.
     */
    const closeModal = () => {
        modal.classList.remove('active');
    };

    // --- Assign Event Listeners ---

    
    if (loginBtn) {
        loginBtn.addEventListener('click', () => openModal('login'));
    }

    
    if (registerBtn) {
        registerBtn.addEventListener('click', () => openModal('register'));
    }

    // Close modal when the 'x' is clicked
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Close modal if the user clicks on the dark overlay background
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close modal if the user presses the 'Escape' key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
});