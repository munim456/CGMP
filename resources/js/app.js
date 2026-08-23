const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Mobile navigation ---------- */
const navToggle = document.getElementById('nav-toggle');
if (navToggle) {
    navToggle.addEventListener('click', () => {
        const open = document.body.classList.toggle('nav-open');
        navToggle.setAttribute('aria-expanded', String(open));
        navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.body.classList.contains('nav-open')) {
            document.body.classList.remove('nav-open');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.focus();
        }
    });
}

/* ---------- Sticky header ---------- */
const header = document.getElementById('site-header');
if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

/* ---------- Accessibility toolbar: font size + print ---------- */
const root = document.documentElement;
const storedStep = parseInt(localStorage.getItem('cgmp-font-step') || '0', 10);
let fontStep = Number.isNaN(storedStep) ? 0 : Math.min(Math.max(storedStep, 0), 3);
applyFontStep();

function applyFontStep() {
    for (let i = 1; i <= 3; i++) root.classList.remove(`a11y-step-${i}`);
    if (fontStep > 0) root.classList.add(`a11y-step-${fontStep}`);
    localStorage.setItem('cgmp-font-step', String(fontStep));
}

document.getElementById('font-smaller')?.addEventListener('click', () => {
    fontStep = Math.max(0, fontStep - 1);
    applyFontStep();
});
document.getElementById('font-larger')?.addEventListener('click', () => {
    fontStep = Math.min(3, fontStep + 1);
    applyFontStep();
});
document.getElementById('print-page')?.addEventListener('click', () => window.print());

/* ---------- Scroll reveal ---------- */
const revealEls = document.querySelectorAll('[data-reveal]');
if (revealEls.length && !prefersReducedMotion && 'IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                entry.target.style.transitionDelay = `${Math.min(i * 70, 280)}ms`;
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.14, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach((el) => revealObserver.observe(el));
} else {
    revealEls.forEach((el) => el.classList.add('is-visible'));
}

/* ---------- Count-up stats ---------- */
const counters = document.querySelectorAll('.count-up');
if (counters.length) {
    const animateCount = (el) => {
        const target = parseInt(el.dataset.countTo || '0', 10);
        if (prefersReducedMotion || !target) {
            el.textContent = target.toLocaleString();
            return;
        }
        const duration = 1600;
        const start = performance.now();
        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased).toLocaleString();
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const countObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                countObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach((el) => countObserver.observe(el));
}

/* ---------- Testimonial slider ---------- */
const slider = document.getElementById('testimonial-slider');
if (slider) {
    const slides = [...slider.querySelectorAll('.testimonial-slide')];
    const dotsWrap = document.getElementById('testimonial-dots');
    let current = 0;
    let timer = null;

    const show = (index) => {
        current = (index + slides.length) % slides.length;
        slides.forEach((s, i) => s.classList.toggle('is-active', i === current));
        dotsWrap.querySelectorAll('button').forEach((d, i) => d.setAttribute('aria-selected', String(i === current)));
    };

    const play = () => {
        if (prefersReducedMotion || slides.length < 2) return;
        stop();
        timer = setInterval(() => show(current + 1), 6500);
    };
    const stop = () => { if (timer) clearInterval(timer); };

    slides.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('role', 'tab');
        dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
        dot.setAttribute('aria-label', `Testimonial ${i + 1}`);
        dot.addEventListener('click', () => { show(i); play(); });
        dotsWrap.appendChild(dot);
    });

    slider.querySelector('.slider-btn--prev')?.addEventListener('click', () => { show(current - 1); play(); });
    slider.querySelector('.slider-btn--next')?.addEventListener('click', () => { show(current + 1); play(); });
    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', play);

    let touchStartX = null;
    slider.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', (e) => {
        if (touchStartX === null) return;
        const delta = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(delta) > 45) show(current + (delta < 0 ? 1 : -1));
        touchStartX = null;
        play();
    }, { passive: true });

    show(0);
    play();
}

/* ---------- Dismissible announcements ---------- */
document.querySelectorAll('[data-dismissible]').forEach((notice) => {
    const key = notice.dataset.noticeId;
    if (!key) return;

    const dismissed = JSON.parse(localStorage.getItem('cgmp-dismissed-notices') || '[]');
    if (dismissed.includes(key)) {
        notice.remove();
        return;
    }

    notice.querySelector('[data-dismiss-notice]')?.addEventListener('click', () => {
        dismissed.push(key);
        localStorage.setItem('cgmp-dismissed-notices', JSON.stringify(dismissed.slice(-30)));
        notice.style.display = 'none';
    });
});

/* ---------- Copy link button ---------- */
document.querySelectorAll('[data-copy-link]').forEach((btn) => {
    btn.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(btn.dataset.copyLink);
            btn.setAttribute('aria-label', 'Link copied');
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>';
            setTimeout(() => { btn.innerHTML = original; }, 1600);
        } catch {
            /* clipboard unavailable */
        }
    });
});
