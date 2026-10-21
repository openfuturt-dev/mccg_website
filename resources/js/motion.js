import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

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
        return { lenis: null };
    }

    // Smooth, weighted scrolling (wheel only; touch devices keep native scrolling).
    const lenis = new Lenis({ lerp: 0.1, anchors: { offset: -96 } });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    // Hero entrance: split heading first, then the supporting elements.
    const heroTimeline = gsap.timeline({ defaults: { ease: 'power4.out' }, delay: 0.1 });
    document.querySelectorAll('[data-split]').forEach((heading) => {
        const words = splitWords(heading);
        heading.classList.add('is-split');
        heading.style.opacity = '1';
        heroTimeline.fromTo(words, { yPercent: 110 }, { yPercent: 0, duration: 1.1, stagger: 0.035 }, 0);
    });
    const heroItems = gsap.utils.toArray('[data-hero]');
    if (heroItems.length) {
        heroTimeline.fromTo(heroItems, { autoAlpha: 0, y: 24 }, { autoAlpha: 1, y: 0, duration: 1, stagger: 0.09, clearProps: 'transform' }, 0.15);
    }

    // Image reveals: a soft unmasking with a slight zoom-out.
    gsap.utils.toArray('[data-reveal-image]').forEach((frame) => {
        const image = frame.querySelector('img');
        const timeline = gsap.timeline({
            defaults: { duration: 1.4, ease: 'power3.out' },
            scrollTrigger: frame.hasAttribute('data-hero-image') ? null : { trigger: frame, start: 'top 85%', once: true },
            delay: frame.hasAttribute('data-hero-image') ? 0.2 : 0,
        });
        timeline.fromTo(frame, { autoAlpha: 0, clipPath: 'inset(8% 8% 8% 8% round 1rem)' }, { autoAlpha: 1, clipPath: 'inset(0% 0% 0% 0% round 1rem)' }, 0);
        // Parallax images manage their own scale.
        if (image && !image.hasAttribute('data-parallax')) timeline.fromTo(image, { scale: 1.18 }, { scale: 1, clearProps: 'scale' }, 0);
    });

    // Scroll reveals, batched so grids cascade.
    ScrollTrigger.batch('[data-reveal]', {
        start: 'top 88%',
        once: true,
        onEnter: (batch) => {
            batch.forEach((element) => element.classList.add('is-inview'));
            gsap.fromTo(batch, { autoAlpha: 0, y: 32 }, { autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out', stagger: 0.09, overwrite: true, clearProps: 'transform' });
        },
    });

    // Gentle parallax on large imagery (desktop only).
    gsap.matchMedia().add('(min-width: 1024px)', () => {
        gsap.utils.toArray('[data-parallax]').forEach((image) => {
            gsap.fromTo(image, { yPercent: -5, scale: 1.12 }, {
                yPercent: 5,
                scale: 1.12,
                ease: 'none',
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
            duration: 1.6,
            ease: 'power2.out',
            scrollTrigger: { trigger: counter, start: 'top 90%', once: true },
            onUpdate: () => { counter.textContent = `${Math.round(value.current).toLocaleString('fr-FR')}${suffix}`; },
        });
    });

    // Reading / scroll progress bar in the header.
    const progress = document.querySelector('[data-scroll-progress]');
    if (progress) {
        gsap.to(progress, { scaleX: 1, ease: 'none', scrollTrigger: { start: 0, end: 'max', scrub: 0.2 } });
    }

    window.addEventListener('load', () => ScrollTrigger.refresh());

    return { lenis };
};
