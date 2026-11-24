(() => {
    'use strict';
    const backTop = document.querySelector('[data-backtop]');
    if (!backTop) return;
    const updateBackTop = () => backTop.classList.toggle('is-visible', window.scrollY > 420);
    window.addEventListener('scroll', updateBackTop, { passive: true });
    updateBackTop();
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();
