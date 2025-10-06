// For the mobile hamburger menu
const hamburgerMenu = document.querySelector('.hamburger-menu');
const navLinks = document.querySelector('.nav-links');

hamburgerMenu.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

// For the logout confirmation
const logoutButton = document.querySelector('.logout-btn');

if (logoutButton) {
    logoutButton.addEventListener('click', (event) => {
        const confirmLogout = confirm("Are you sure you want to logout?");
        if (!confirmLogout) {
            // This stops the link from going to logout.php if they click "Cancel"
            event.preventDefault();
        }
    });
}