import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);
// Ignore mobile address-bar show/hide resizes, which otherwise force a full ScrollTrigger refresh mid-scroll.
ScrollTrigger.config({ ignoreMobileResize: true });

const root = document.documentElement;

// Wraps each word in a mask so headings can rise word by word. Nested markup (e.g. the coral highlight) is kept.
const splitWords = (element) => {
    const label = element.textContent.replace(/\s+/g, ' ').trim();
    const walk = (node) => {
        [...node.childNodes].forEach((child) => {
            if (child.nodeType === Node.ELEMENT_NODE) {
                walk(child);
                return;
            }
            if (child.nodeType !== Node.TEXT_NODE || !child.textContent.trim()) return;

            const fragment = document.createDocumentFragment();
            child.textContent.split(/(\s+)/).forEach((part) => {
                if (!part) return;
                if (/^\s+$/.test(part)) {
                    fragment.append(document.createTextNode(part));
                    return;
                }
                const mask = document.createElement('span');
                mask.className = 'split-line';
                mask.setAttribute('aria-hidden', 'true');
                const word = document.createElement('span');
                word.className = 'split-word';
                word.textContent = part;
                mask.append(word);
                fragment.append(mask);
            });
            child.replaceWith(fragment);
        });
    };

    walk(element);
    element.setAttribute('aria-label', label);
    return element.querySelectorAll('.split-word');
};

const revealAll = () => {
    root.classList.add('no-motion');
    document.querySelectorAll('[data-reveal], [data-hero], [data-reveal-image], [data-split]').forEach((element) => {
        element.classList.add('is-inview', 'is-split');
        element.style.opacity = '1';
    });
    document.querySelectorAll('[data-counter]').forEach((counter) => {
        counter.textContent = `${counter.dataset.counter}${counter.dataset.suffix ?? ''}`;
    });
};

export const initMotion = () => {
    root.classList.add('motion-ready');

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealAll();
        return;
    }

    // Hero entrance: quick and crisp, finished in well under a second.
    const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
    document.querySelectorAll('[data-split]').forEach((heading) => {
        const words = splitWords(heading);
        heading.classList.add('is-split');
        heading.style.opacity = '1';
        heroTimeline.fromTo(words, { yPercent: 105 }, { yPercent: 0, duration: 0.65, stagger: 0.018, clearProps: 'transform' }, 0);
    });
    const heroItems = gsap.utils.toArray('[data-hero]');
    if (heroItems.length) {
        heroTimeline.fromTo(heroItems, { autoAlpha: 0, y: 14 }, { autoAlpha: 1, y: 0, duration: 0.55, stagger: 0.05, clearProps: 'transform' }, 0.1);
    }

    // Image reveals: a short unmasking with a slight zoom-out.
    gsap.utils.toArray('[data-reveal-image]').forEach((frame) => {
        const isHero = frame.hasAttribute('data-hero-image');
        const image = frame.querySelector('img');
        const timeline = gsap.timeline({
            defaults: { duration: 0.85, ease: 'power3.out' },
            scrollTrigger: isHero ? undefined : { trigger: frame, start: 'top 90%', once: true },
            delay: isHero ? 0.05 : 0,
        });
        timeline.fromTo(frame, { autoAlpha: 0, clipPath: 'inset(6% 6% 6% 6% round 1rem)' }, { autoAlpha: 1, clipPath: 'inset(0% 0% 0% 0% round 0rem)', clearProps: 'clipPath' }, 0);
        // Parallax images manage their own scale.
        if (image && !image.hasAttribute('data-parallax')) timeline.fromTo(image, { scale: 1.08 }, { scale: 1, clearProps: 'scale' }, 0);
    });

    // Scroll reveals, batched so grids cascade quickly.
    ScrollTrigger.batch('[data-reveal]', {
        start: 'top 92%',
        once: true,
        onEnter: (batch) => {
            batch.forEach((element) => element.classList.add('is-inview'));
            gsap.fromTo(batch, { autoAlpha: 0, y: 18 }, { autoAlpha: 1, y: 0, duration: 0.55, ease: 'power2.out', stagger: 0.05, overwrite: true, clearProps: 'transform' });
        },
    });

    // Subtle parallax on large imagery (desktop with a fine pointer only).
    gsap.matchMedia().add('(min-width: 1024px) and (pointer: fine)', () => {
        gsap.utils.toArray('[data-parallax]').forEach((image) => {
            gsap.fromTo(image, { yPercent: -4, scale: 1.1 }, {
                yPercent: 4,
                scale: 1.1,
                ease: 'none',
                force3D: true,
                scrollTrigger: { trigger: image.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        });
    });

    // Counters.
    gsap.utils.toArray('[data-counter]').forEach((counter) => {
        const value = { current: 0 };
        const target = Number(counter.dataset.counter);
        const suffix = counter.dataset.suffix ?? '';
        gsap.to(value, {
            current: target,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: { trigger: counter, start: 'top 92%', once: true },
            onUpdate: () => { counter.textContent = `${Math.round(value.current).toLocaleString('fr-FR')}${suffix}`; },
        });
    });

    // Scroll progress bar in the header.
    const progress = document.querySelector('[data-scroll-progress]');
    if (progress) {
        gsap.to(progress, { scaleX: 1, ease: 'none', scrollTrigger: { start: 0, end: 'max', scrub: true } });
    }

    window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
};
