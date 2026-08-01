// presence.js - shared ping utilities (no module system, exposes globals)
window.sendPing = async function({ sessionToken, callsign, countryCode = 'ID', visitor = false } = {}) {
    try {
        const res = await fetch('/api/v1/ping', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ session_token: sessionToken, callsign, country_code: countryCode, visitor })
        });
        return await res.json();
    } catch (err) {
        console.warn('[PRESENCE] sendPing failed', err);
        return null;
    }
};

window.startPresenceHeartbeat = function({ sessionToken, callsign, countryCode = 'ID', intervalMs = 30000, isCallActiveRef } = {}) {
    if (window.__presenceInterval) return;

    // initial ping flags visitor when not actively calling
    try {
        const initialVisitorFlag = !isCallActiveRef || !isCallActiveRef();
        console.log('[PRESENCE] initial ping', { sessionToken, callsign, initialVisitorFlag });
        window.sendPing({ sessionToken, callsign, countryCode, visitor: initialVisitorFlag });
    } catch (e) {
        console.warn('[PRESENCE] initial ping exception', e);
    }

    window.__presenceInterval = setInterval(() => {
        if (document.hidden) return;
        const sendVisitor = !(isCallActiveRef && isCallActiveRef());
        window.sendPing({ sessionToken, callsign, countryCode, visitor: sendVisitor }).catch(e => console.warn('[PRESENCE] ping failed', e));
    }, intervalMs);

    window.addEventListener('beforeunload', () => {
        try {
            const data = JSON.stringify({ session_token: sessionToken, visitor: false });
            const blob = new Blob([data], { type: 'application/json' });
            navigator.sendBeacon('/api/v1/leave', blob);
        } catch (e) {
            // ignore
        }
    });
};