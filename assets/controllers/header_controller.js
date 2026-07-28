import { Controller } from '@hotwired/stimulus';

/*
 * En-tête marketing : état « scrolled » (fond flou + ombre au-delà de 12px)
 * et menu mobile (burger). Reproduit le comportement de la baseline.
 */
export default class extends Controller {
    static targets = ['menu', 'burger'];

    connect() {
        this.onScroll = this.onScroll.bind(this);
        this.onScroll();
        window.addEventListener('scroll', this.onScroll, { passive: true });
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    onScroll() {
        this.element.classList.toggle('scrolled', window.scrollY > 12);
    }

    toggle() {
        const open = this.menuTarget.classList.toggle('open');
        if (this.hasBurgerTarget) {
            this.burgerTarget.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    }

    close() {
        this.menuTarget.classList.remove('open');
        if (this.hasBurgerTarget) {
            this.burgerTarget.setAttribute('aria-expanded', 'false');
        }
    }
}
