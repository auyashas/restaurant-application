// Wait for the HTML document to be fully loaded before running any scripts
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. Initialize AOS (Animate On Scroll) ---
    AOS.init({
        duration: 800, // Animation duration in milliseconds
        once: true    // Whether animation should happen only once
    });

    // --- 2. Mobile Hamburger Menu Logic ---
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const navLinks = document.querySelector('.nav-links');

    if (hamburgerMenu && navLinks) {
        hamburgerMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // --- 3. Logout Confirmation Logic ---
    const logoutButton = document.querySelector('.logout-btn');

    if (logoutButton) {
        logoutButton.addEventListener('click', (event) => {
            const confirmLogout = confirm("Are you sure you want to logout?");
            if (!confirmLogout) {
                event.preventDefault(); // Stop the link from redirecting if they click "Cancel"
            }
        });
    }

    // --- 4. Homepage-Specific Scripts ---
    // We check if the body has the 'home-page' class before running these
    if (document.body.classList.contains('home-page')) {
        
        // a) Transparent Header on Scroll Logic
        const header = document.querySelector('header');
        if (header) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }

        // b) Initialize Swiper Slider Logic
        const swiperContainer = document.querySelector('.mySwiper');
        if (swiperContainer) {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        }
    }
});