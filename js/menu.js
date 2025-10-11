document.addEventListener('DOMContentLoaded', function () {
    const mainHeader = document.querySelector('header');
    const categoryNav = document.querySelector('.category-nav');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const sections = document.querySelectorAll('.menu-section');

    if (!categoryNav) return; // Don't run script if nav doesn't exist

    // Smooth scrolling with offset for sticky navs
    categoryButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                const mainHeaderHeight = mainHeader ? mainHeader.offsetHeight : 0;
                const categoryNavHeight = categoryNav ? categoryNav.offsetHeight : 0;
                const offset = mainHeaderHeight + categoryNavHeight + 20; // 20px buffer

                window.scrollTo({
                    top: targetSection.offsetTop - offset,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Highlight active section on scroll
    window.addEventListener('scroll', function () {
        let currentSectionId = '';
        const mainHeaderHeight = mainHeader ? mainHeader.offsetHeight : 0;
        const categoryNavHeight = categoryNav ? categoryNav.offsetHeight : 0;
        const offset = mainHeaderHeight + categoryNavHeight + 100;

        sections.forEach(section => {
            if (window.scrollY >= section.offsetTop - offset) {
                currentSectionId = '#' + section.getAttribute('id');
            }
        });

        categoryButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('href') === currentSectionId) {
                btn.classList.add('active');
            }
        });
    });
});