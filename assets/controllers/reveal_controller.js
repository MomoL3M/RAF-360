import { Controller } from '@hotwired/stimulus';

/*
 * Apparition au scroll (équivalent du composant Reveal de la baseline).
 * L'animation reste 100% CSS (.reveal / .reveal.in) ; ce contrôleur ne fait
 * qu'ajouter la classe `in` quand l'élément entre dans le viewport, une seule fois.
 * Respecte prefers-reduced-motion.
 */
export default class extends Controller {
    connect() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.element.classList.add('in');
            return;
        }

        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.element.classList.add('in');
                        this.observer.disconnect();
                    }
                });
            },
            { threshold: 0.14, rootMargin: '0px 0px -8% 0px' },
        );

        this.observer.observe(this.element);
    }

    disconnect() {
        this.observer?.disconnect();
    }
}
