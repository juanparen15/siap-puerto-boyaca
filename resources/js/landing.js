import { CountUp } from 'countup.js';
import L from 'leaflet';

// Animated counters — trigger when visible
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.countup');
    if (!elements.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.dataset.target || '0', 10);
                    new CountUp(el, target, {
                        duration: 2.5,
                        separator: '.',
                        decimal: ',',
                    }).start();
                    observer.unobserve(el);
                }
            });
        },
        { threshold: 0.1 }
    );

    elements.forEach(el => observer.observe(el));
});

// Hero background map (decorative — no interaction)
document.addEventListener('DOMContentLoaded', () => {
    const heroMapEl = document.getElementById('hero-map');
    if (!heroMapEl) return;

    const heroMap = L.map('hero-map', {
        zoomControl: false,
        dragging: false,
        scrollWheelZoom: false,
        touchZoom: false,
        doubleClickZoom: false,
        keyboard: false,
        attributionControl: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(heroMap);

    heroMap.setView([5.977, -74.579], 13);
});
