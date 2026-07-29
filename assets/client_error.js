/*
 * Remonte les erreurs JavaScript de production au serveur (§4.5) : jamais perdues
 * silencieusement. Non bloquant, plafonné par page pour ne pas spammer, même origine (CSP).
 */
const ENDPOINT = '/log/client-error';
const MAX_PER_PAGE = 5;
let sent = 0;

function report(payload) {
    if (sent >= MAX_PER_PAGE) {
        return;
    }
    sent += 1;
    try {
        fetch(ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            keepalive: true,
        }).catch(() => {});
    } catch (e) {
        /* la télémétrie ne doit jamais casser la page */
    }
}

window.addEventListener('error', (event) => {
    report({
        message: event.message || 'error',
        source: `${event.filename || ''}:${event.lineno || 0}`,
        stack: event.error && event.error.stack ? String(event.error.stack) : '',
        url: window.location.href,
    });
});

window.addEventListener('unhandledrejection', (event) => {
    const reason = event.reason;
    report({
        message: reason && reason.message ? String(reason.message) : 'unhandledrejection',
        source: '',
        stack: reason && reason.stack ? String(reason.stack) : '',
        url: window.location.href,
    });
});
