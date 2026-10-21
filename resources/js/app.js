import { createLeadTracker, readConfirmedLead } from './lead-tracking';
import { initMotion } from './motion';

const trackLead = createLeadTracker(window, readConfirmedLead(document));
trackLead();

document.querySelectorAll('[data-contact-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const button = form.querySelector('[data-contact-submit]');
        if (button?.disabled) {
            event.preventDefault();
            return;
        }
        if (!form.checkValidity()) return;

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.querySelector('[data-contact-submit-label]').textContent = 'Envoi en cours…';
    });
});

const { lenis } = initMotion();

const navbar = document.querySelector('[data-navbar]');
const toggle = document.getElementById('menu-toggle');
const menu = document.getElementById('mobile-menu');

const updateNavbar = () => navbar?.classList.toggle('is-scrolled', window.scrollY > 12);
updateNavbar();
window.addEventListener('scroll', updateNavbar, { passive: true });

const setMobileMenu = (isOpen) => {
    if (!menu || !toggle) return;
    menu.classList.toggle('is-open', isOpen);
    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
    menu.setAttribute('aria-hidden', String(!isOpen));
    menu.inert = !isOpen;
    document.body.classList.toggle('mobile-menu-open', isOpen);
    if (isOpen) lenis?.stop();
    else lenis?.start();
};

if (menu) menu.inert = true;

toggle?.addEventListener('click', () => {
    const isOpen = !menu.classList.contains('is-open');
    setMobileMenu(isOpen);
    if (isOpen) menu.querySelector('a')?.focus();
});

menu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    setMobileMenu(false);
}));

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menu?.classList.contains('is-open')) {
        setMobileMenu(false);
        toggle?.focus();
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024 && menu?.classList.contains('is-open')) setMobileMenu(false);
});

// "Nos sites" dropdown: hover on desktop, click/keyboard everywhere.
const officeMenu = document.querySelector('[data-office-menu]');
const officeTrigger = officeMenu?.querySelector('[data-office-trigger]');

if (officeMenu && officeTrigger) {
    const setOfficeMenu = (isOpen) => {
        officeMenu.classList.toggle('is-open', isOpen);
        officeTrigger.setAttribute('aria-expanded', String(isOpen));
    };

    officeTrigger.addEventListener('click', () => setOfficeMenu(!officeMenu.classList.contains('is-open')));
    document.addEventListener('click', (event) => {
        if (!officeMenu.contains(event.target)) setOfficeMenu(false);
    });
    officeMenu.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        setOfficeMenu(false);
        officeTrigger.focus();
    });
}

document.querySelectorAll('[data-office-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-office-track]');
    const panels = [...carousel.querySelectorAll('[data-office-panel]')];
    const tabs = [...carousel.querySelectorAll('[data-office-tab]')];
    const previous = carousel.querySelector('[data-office-previous]');
    const next = carousel.querySelector('[data-office-next]');
    const status = carousel.querySelector('[data-office-status]');
    let activeIndex = 0;

    const showOffice = (index) => {
        activeIndex = (index + panels.length) % panels.length;
        track.style.transform = `translateX(-${activeIndex * 100}%)`;

        tabs.forEach((tab, tabIndex) => {
            const active = tabIndex === activeIndex;
            tab.setAttribute('aria-selected', String(active));
            tab.classList.toggle('border-coral', active);
            tab.classList.toggle('bg-coral', active);
            tab.classList.toggle('text-white', active);
            tab.classList.toggle('border-slate-200', !active);
            tab.classList.toggle('bg-white', !active);
            tab.classList.toggle('text-charcoal', !active);
        });

        panels.forEach((panel, panelIndex) => {
            const hidden = panelIndex !== activeIndex;
            panel.setAttribute('aria-hidden', String(hidden));
            panel.inert = hidden;
        });
        if (status) status.textContent = `${activeIndex + 1} / ${panels.length} · ${tabs[activeIndex].textContent.trim()}`;
    };

    tabs.forEach((tab, index) => tab.addEventListener('click', () => showOffice(index)));
    tabs.forEach((tab, index) => tab.addEventListener('keydown', (event) => {
        if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
        event.preventDefault();
        const targetIndex = index + (event.key === 'ArrowRight' ? 1 : -1);
        showOffice(targetIndex);
        tabs[(targetIndex + tabs.length) % tabs.length].focus();
    }));
    previous?.addEventListener('click', () => showOffice(activeIndex - 1));
    next?.addEventListener('click', () => showOffice(activeIndex + 1));
    showOffice(0);
});

const cookieNotice = document.querySelector('[data-cookie-notice]');

if (cookieNotice) {
    const hideCookieNotice = () => {
        cookieNotice.hidden = true;
    };

    let consent = null;
    try {
        consent = window.localStorage.getItem('mccg_analytics_consent');
    } catch (_) {
        // The notice remains available when browser storage is restricted.
    }

    if (!consent) cookieNotice.hidden = false;

    cookieNotice.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        try {
            window.localStorage.setItem('mccg_analytics_consent', 'accepted');
        } catch (_) {
            // Consent still applies for the current page when storage is unavailable.
        }
        window.mccgSetGoogleConsent?.('accepted');
        trackLead();
        hideCookieNotice();
    });

    cookieNotice.querySelector('[data-cookie-reject]')?.addEventListener('click', () => {
        try {
            window.localStorage.setItem('mccg_analytics_consent', 'declined');
        } catch (_) {
            // Tracking stays disabled even when the preference cannot be persisted.
        }
        window.mccgSetGoogleConsent?.('declined');
        hideCookieNotice();
    });
}
