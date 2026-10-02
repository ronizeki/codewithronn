import AOS from 'aos';

document.documentElement.classList.add('js');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
if (!reducedMotion.matches) {
    AOS.init({ duration: 600, once: true, offset: 35, disableMutationObserver: true });
    document.documentElement.classList.add('aos-ready');
}

const toggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#primary-nav');
function closeMenu(returnFocus = false) {
    nav?.classList.remove('is-open');
    toggle?.setAttribute('aria-expanded', 'false');
    toggle?.setAttribute('aria-label', 'Open navigation');
    if (returnFocus) toggle?.focus();
}
toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    nav.classList.toggle('is-open', open);
});
nav?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => closeMenu()));
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && toggle?.getAttribute('aria-expanded') === 'true') closeMenu(true);
});
document.addEventListener('click', event => {
    if (!event.target.closest('.site-header')) closeMenu();
});
window.matchMedia('(min-width: 821px)').addEventListener('change', () => closeMenu());

const sections = document.querySelectorAll('section[id]');
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
        for (const entry of entries) {
            if (!entry.isIntersecting) continue;
            const current = document.querySelector(`[data-nav="${entry.target.id}"]`);
            if (!current) continue;
            document.querySelectorAll('[data-nav]').forEach(link => link.removeAttribute('aria-current'));
            current.setAttribute('aria-current', 'location');
        }
    }, { rootMargin: '-15% 0px -60% 0px' });
    sections.forEach(section => observer.observe(section));
}

const filters = document.querySelector('.project-filters');
if (filters) {
    filters.hidden = false;
    filters.querySelectorAll('button').forEach(button => button.addEventListener('click', () => {
        filters.querySelectorAll('button').forEach(item => item.setAttribute('aria-pressed', String(item === button)));
        let count = 0;
        document.querySelectorAll('.project-card').forEach(card => {
            card.hidden = button.dataset.filter !== 'All projects' && card.dataset.category !== button.dataset.filter;
            if (!card.hidden) { count++; card.classList.add('aos-animate'); }
        });
        document.querySelector('#filter-status').textContent = `${count} projects shown`;
        AOS.refresh();
    }));
}

// Anonymous conversion events only: never send form values, email addresses, or phone numbers.
function track(event, properties = {}) {
    if (typeof window.gtag === 'function') window.gtag('event', event, properties);
    else { window.dataLayer = window.dataLayer || []; window.dataLayer.push({ event, ...properties }); }
}
document.addEventListener('click', event => {
    const link = event.target.closest('[data-track]');
    if (link) track(link.dataset.track, link.dataset.project ? { project: link.dataset.project } : {});
});
if (document.querySelector('[data-contact-delivered]')) track('contact_form_submitted');
const form = document.querySelector('#contact-form');
form?.addEventListener('submit', () => {
    const submit = form.querySelector('[type="submit"]');
    submit.disabled = true;
    submit.textContent = 'Sending your message…';
});
window.addEventListener('pageshow', () => {
    const submit = form?.querySelector('[type="submit"]');
    if (submit) { submit.disabled = false; submit.textContent = 'Send Message ↗'; }
});
document.querySelector('[data-form-result]')?.focus({ preventScroll: true });
