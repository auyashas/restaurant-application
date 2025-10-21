// --- SCROLL TO TOP BUTTON LOGIC ---

document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");

    // 1. Show or hide the button based on scroll position
    window.onscroll = function() {
        // A scroll position of > 300px is a good trigger point
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            scrollToTopBtn.classList.add("show");
        } else {
            scrollToTopBtn.classList.remove("show");
        }
    };

    // 2. Scroll to the top when the button is clicked
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // This creates the smooth scrolling effect
        });
    });
});
