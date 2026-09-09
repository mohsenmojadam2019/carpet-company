(() => {
    'use strict';

    const header = document.querySelector('[data-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const onScroll = () => {
        if (header) header.classList.toggle('is-scrolled', window.scrollY > 10);
        if (!reducedMotion) {
            document.querySelectorAll('[data-parallax]').forEach((node) => {
                const speed = Number(node.dataset.parallax || 0.05);
                const rect = node.getBoundingClientRect();
                const center = rect.top + rect.height / 2 - window.innerHeight / 2;
                node.style.transform = `translate3d(0, ${center * -speed}px, 0)`;
            });
        }
    };

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            onScroll();
            ticking = false;
        });
    }, { passive: true });
    onScroll();

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -4% 0px' });
        document.querySelectorAll('.reveal').forEach((node) => observer.observe(node));
    } else {
        document.querySelectorAll('.reveal').forEach((node) => node.classList.add('is-visible'));
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!expanded));
            document.body.classList.toggle('mobile-menu-open', !expanded);
        });
    }

    document.querySelectorAll('[data-rug-peel]').forEach((stage) => {
        const top = stage.querySelector('[data-rug-top]');
        const handle = stage.querySelector('[data-rug-handle]');
        if (!top || !handle) return;

        let dragging = false;
        let peel = 100;

        const apply = (clientX) => {
            const rect = stage.getBoundingClientRect();
            const x = Math.max(rect.left, Math.min(clientX, rect.right));
            const normalized = ((x - rect.left) / rect.width) * 100;
            peel = Math.max(18, Math.min(100, normalized));
            top.style.setProperty('--peel', `${peel}%`);
            handle.style.left = `calc(${Math.max(2, peel - 8)}% - 39px)`;
        };

        handle.addEventListener('pointerdown', (event) => {
            dragging = true;
            handle.setPointerCapture(event.pointerId);
            apply(event.clientX);
        });
        handle.addEventListener('pointermove', (event) => {
            if (dragging) apply(event.clientX);
        });
        handle.addEventListener('pointerup', (event) => {
            dragging = false;
            if (handle.hasPointerCapture(event.pointerId)) handle.releasePointerCapture(event.pointerId);
        });
        handle.addEventListener('keydown', (event) => {
            if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
            event.preventDefault();
            peel += event.key === 'ArrowLeft' ? -8 : 8;
            peel = Math.max(18, Math.min(100, peel));
            top.style.setProperty('--peel', `${peel}%`);
            handle.style.left = `calc(${Math.max(2, peel - 8)}% - 39px)`;
        });
    });

    window.setTimeout(() => document.querySelectorAll('.toast').forEach((node) => node.remove()), 4800);
})();
