import { Controller } from '@hotwired/stimulus';

/*
 * Bandeau de consentement (§17.1).
 * - S'affiche tant qu'aucun choix n'a été enregistré.
 * - « Refuser » et « Accepter » sont deux boutons équivalents (même taille, même visibilité).
 * - Le choix est stocké dans un cookie propriétaire (exempté de consentement) 1 an,
 *   relisible par les futurs scripts de mesure AVANT de se charger.
 * - Rouvrable à tout moment via n'importe quel élément [data-consent-open] (lien du pied de page).
 */
const COOKIE = 'raf360_consent';
const MAX_AGE = 60 * 60 * 24 * 365;

export default class extends Controller {
    connect() {
        this._reopeners = Array.from(document.querySelectorAll('[data-consent-open]'));
        this._open = (e) => { if (e) { e.preventDefault(); } this.open(); };
        this._reopeners.forEach((el) => el.addEventListener('click', this._open));

        if (!this._hasChoice()) {
            this.open();
        }
    }

    disconnect() {
        this._reopeners.forEach((el) => el.removeEventListener('click', this._open));
    }

    accept() { this._save('accepted'); }

    refuse() { this._save('refused'); }

    open() {
        this.element.hidden = false;
    }

    close() {
        this.element.hidden = true;
    }

    _hasChoice() {
        return document.cookie.split('; ').some((c) => c.indexOf(COOKIE + '=') === 0);
    }

    _save(choice) {
        document.cookie = `${COOKIE}=${choice}; path=/; max-age=${MAX_AGE}; SameSite=Lax`;
        this.close();
        window.dispatchEvent(new CustomEvent('raf360:consent', { detail: { choice } }));
    }
}
