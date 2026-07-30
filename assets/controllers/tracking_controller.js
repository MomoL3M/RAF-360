import { Controller } from '@hotwired/stimulus';

/*
 * Point d'entrée UNIQUE de la mesure d'audience (§20).
 *
 * Règles tenues ici, et nulle part ailleurs :
 * - AUCUNE donnée personnelle n'est envoyée : ni e-mail, ni nom, ni identifiant, et le
 *   chemin de page est transmis SANS query string (un paramètre d'URL peut contenir une
 *   adresse e-mail — §20).
 * - Rien ne part avant consentement : sans cookie `raf360_consent=accepted`, les
 *   événements restent en file locale et sont perdus à la fermeture de l'onglet.
 * - Sans point de collecte configuré (`endpoint` vide), aucune requête réseau n'est
 *   émise : l'absence de prestataire ne casse rien et ne trace personne.
 *
 * Le nom des événements suit la convention de docs/plan-mesure.md.
 */
const COOKIE = 'raf360_consent';
const FILE_MAX = 20;

/** Origines des moteurs de réponse IA à distinguer dans la mesure (§9.4). */
const MOTEURS_IA = ['chatgpt.com', 'chat.openai.com', 'perplexity.ai', 'claude.ai', 'gemini.google.com', 'copilot.microsoft.com'];
const MOTEURS_CLASSIQUES = ['google.', 'bing.com', 'duckduckgo.com', 'qwant.com', 'ecosia.org'];

export default class extends Controller {
    static values = { endpoint: String, conversion: String };

    connect() {
        this.file = [];
        this._onConsent = () => this._vider();
        window.addEventListener('raf360:consent', this._onConsent);

        this.envoyer('page_vue', { canal: this._canal() });

        // Conversion confirmée par le serveur (message flash) : c'est le seul moyen fiable
        // de ne compter que les envois RÉELLEMENT acceptés, pas les clics sur « Envoyer ».
        if (this.conversionValue) {
            this.envoyer(this.conversionValue, {});
        }
    }

    disconnect() {
        window.removeEventListener('raf360:consent', this._onConsent);
    }

    /**
     * Déclencheur déclaratif :
     * data-action="click->tracking#evenement" data-tracking-nom-param="tarifs_cta_clic"
     */
    evenement(event) {
        const nom = event.params?.nom;
        if (nom) {
            this.envoyer(nom, {});
        }
    }

    /** Envoi (ou mise en file) d'un événement nommé. */
    envoyer(nom, proprietes) {
        const charge = {
            evenement: nom,
            // Chemin sans query string : c'est là que se cacheraient des données personnelles.
            page: window.location.pathname,
            ...proprietes,
        };

        this.file.push(charge);
        if (this.file.length > FILE_MAX) {
            this.file.shift();
        }
        this._vider();
    }

    /** Vide la file si — et seulement si — le consentement et le point de collecte existent. */
    _vider() {
        const endpoint = this.endpointValue;
        if (!endpoint || !this._consentementDonne() || this.file.length === 0) {
            return;
        }

        const lot = this.file.splice(0, this.file.length);
        try {
            const corps = new Blob([JSON.stringify({ evenements: lot })], { type: 'application/json' });
            if (!navigator.sendBeacon(endpoint, corps)) {
                // Beacon refusé (quota, hors ligne) : on ne réessaie pas, une mesure
                // perdue ne doit jamais dégrader la navigation.
                this.file = [];
            }
        } catch (erreur) {
            console.error('Mesure : envoi impossible', erreur);
            this.file = [];
        }
    }

    _consentementDonne() {
        return document.cookie
            .split('; ')
            .some((c) => c === `${COOKIE}=accepted`);
    }

    /** Classe la provenance sans conserver l'URL de référence complète. */
    _canal() {
        const referrer = document.referrer;
        if (!referrer) {
            return 'direct';
        }

        let hote;
        try {
            hote = new URL(referrer).hostname;
        } catch (erreur) {
            return 'inconnu';
        }

        if (hote === window.location.hostname) {
            return 'interne';
        }
        if (MOTEURS_IA.some((m) => hote.includes(m))) {
            return 'moteur_ia';
        }
        if (MOTEURS_CLASSIQUES.some((m) => hote.includes(m))) {
            return 'moteur_recherche';
        }

        return 'referral';
    }
}
