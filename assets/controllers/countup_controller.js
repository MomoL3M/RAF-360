import { Controller } from '@hotwired/stimulus';

/*
 * Incrémente un nombre de 0 jusqu'à sa valeur cible quand l'élément entre
 * dans le viewport (une seule fois). Le rendu serveur contient déjà la valeur
 * finale : sans JS ou en reduced-motion, le chiffre correct reste affiché.
 *
 * Attributs : data-countup-to-value (nombre), data-countup-suffix-value,
 * data-countup-prefix-value, data-countup-duration-value (ms).
 */
export default class extends Controller {
    static values = {
        to: Number,
        prefix: String,
        suffix: String,
        duration: { type: Number, default: 1300 },
    };

    connect() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return; // on laisse la valeur finale rendue par le serveur
        }

        this.render(0);
        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.animate();
                        this.observer.disconnect();
                    }
                });
            },
            { threshold: 0.4 },
        );
        this.observer.observe(this.element);
    }

    animate() {
        const start = performance.now();
        const step = (now) => {
            const progress = Math.min(1, (now - start) / this.durationValue);
            const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
            this.render(this.toValue * eased);
            if (progress < 1) {
                this.raf = requestAnimationFrame(step);
            } else {
                this.render(this.toValue);
            }
        };
        this.raf = requestAnimationFrame(step);
    }

    render(value) {
        const rounded = Math.round(value).toString();
        const spaced = rounded.replace(/\B(?=(\d{3})+(?!\d))/g, ' '); // milliers
        this.element.textContent = (this.prefixValue || '') + spaced + (this.suffixValue || '');
    }

    disconnect() {
        this.observer?.disconnect();
        if (this.raf) {
            cancelAnimationFrame(this.raf);
        }
    }
}
