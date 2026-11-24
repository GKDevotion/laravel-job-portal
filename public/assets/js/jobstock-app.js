/**
 * JobStock frontend interactions.
 * Kept dependency-light: Bootstrap handles the responsive navbar and this file
 * handles small site-wide enhancements without jQuery or npm packages.
 */
(() => {
    'use strict';

    const backTop = document.querySelector('[data-backtop]');

    if (backTop) {
        const updateBackTop = () => {
            backTop.classList.toggle('is-visible', window.scrollY > 420);
        };

        window.addEventListener('scroll', updateBackTop, { passive: true });
        updateBackTop();

        backTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Close the mobile navigation after a visitor selects an in-page or site link.
    const navbar = document.getElementById('jobstockNavbar');
    if (navbar && window.bootstrap && window.bootstrap.Collapse) {
        navbar.querySelectorAll('.nav-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 1199.98px)').matches && navbar.classList.contains('show')) {
                    window.bootstrap.Collapse.getOrCreateInstance(navbar).hide();
                }
            });
        });
    }

    // Prevent empty search submissions from producing confusing query strings.
    document.querySelectorAll('.hero-search-card form, .filter-panel').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const keyword = form.querySelector('[name="keyword"]');
            const location = form.querySelector('[name="location"]');
            if (keyword && location && !keyword.value.trim() && !location.value.trim()) {
                keyword.focus();
                event.preventDefault();
            }
        });
    });
})();
