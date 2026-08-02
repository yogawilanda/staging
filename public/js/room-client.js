// room-client.js - moved from inline script in room view
// Core State Variables (globals for other scripts that expect them)
var isMuted = false;
var isCallActive = true;
var localStream = null;
var peerConnection = null;
var currentPeerSession = null;
var pollInterval = null;
var isMatchedUI = false;
var isMatched = false;
var pendingCandidates = [];

// Session Token Management
var sessionToken = sessionStorage.getItem('void_session_token');
if (!sessionToken) {
    sessionToken = 'sec_' + Math.random().toString(36).substring(2, 11) + Date.now().toString(36);
    sessionStorage.setItem('void_session_token', sessionToken);
}

var callsign = document.querySelector('#callsign')?.value || window.CALLSIGN_OVERRIDE || 'GHOST_OPERATOR';
var countryCode = document.querySelector('#country_code')?.value || window.COUNTRY_OVERRIDE || 'ID';

var rtcConfig = {
    iceTransportPolicy: 'relay', // normalnya jaringan di Indonesia wajib relay.
    iceServers: [
        {
            urls: "stun:stun.relay.metered.ca:80",
        },
        {
            urls: "turn:global.relay.metered.ca:80",
            username: "ce0785b171ac0155101f0f47",
            credential: "I0z5FylhnjB8tV61",
        },
        {
            urls: "turn:global.relay.metered.ca:80?transport=tcp",
            username: "ce0785b171ac0155101f0f47",
            credential: "I0z5FylhnjB8tV61",
        },
        {
            urls: "turn:global.relay.metered.ca:443",
            username: "ce0785b171ac0155101f0f47",
            credential: "I0z5FylhnjB8tV61",
        },
        {
            urls: "turns:global.relay.metered.ca:443?transport=tcp",
            username: "ce0785b171ac0155101f0f47",
            credential: "I0z5FylhnjB8tV61",
        },
    ],
};

// Start general presence heartbeat for visitors and non-microphone users
window.startPresenceHeartbeat && window.startPresenceHeartbeat({ sessionToken: sessionToken, callsign: callsign, countryCode: countryCode, isCallActiveRef: _isCallActiveRef });

// init microphone for callers
async function initMicrophone() {
    try {
        if (localStream) return;
        console.log('[CLIENT] Requesting getUserMedia');
        localStream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
        console.log('[CLIENT] getUserMedia success, tracks=', localStream.getTracks());
        // expose on window for other modules
        window.localStream = localStream;

        // ensure our UI can reflect mic status
        isMuted = false;

        // When user grants mic, ensure matchmaking heartbeat starts
        startHeartbeatAndMatchmaking();
    } catch (err) {
        console.error('[CLIENT] getUserMedia error', err);
        alert('[ERROR] Gagal mengakses mikrofon. Harap berikan izin akses mic!');
    }
}

// Centralized matchmaking and ping loop
function startHeartbeatAndMatchmaking() {
    if (pollInterval) {
        console.log('[HB] pollInterval already running');
        return;
    }

    console.log('[HB] startHeartbeatAndMatchmaking called');
    pollInterval = setInterval(async () => {
        if (!isCallActive) return;

        try {
            // Use shared sendPing utility
            if (typeof window.sendPing === 'function') {
                await window.sendPing({ sessionToken: sessionToken, callsign: callsign, countryCode: countryCode, visitor: false });
            } else {
                await fetch('/api/v1/ping', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ session_token: sessionToken, callsign: callsign, country_code: countryCode })
                });
            }
            console.log('[HB] ping ok');
        } catch (err) {
            console.error('[PING_ERROR]', err);
        }

        if (typeof checkPendingSignals === 'function') {
            checkPendingSignals();
        }

        // matchmaking
        try {
            const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.content;

            const res = await fetch('/api/v1/matchmake', {
                method: 'POST',
                headers,
                body: JSON.stringify({ session_token: sessionToken, callsign: callsign, country_code: countryCode })
            });
            const data = await res.json();
            console.log('[HB] matchmake response', data);

            if (data.status === 'matched' && data.peer) {
                if (!isMatched) {
                    isMatched = true;
                    currentPeerSession = data.peer.id;
                    typeof updateMatchedUI === 'function' && updateMatchedUI(data.peer);

                    if (data.role === 'initiator') {
                        typeof initiateCall === 'function' && initiateCall(data.peer.id);
                    }
                }
            } else if (data.status === 'disconnected' || data.status === 'searching') {
                if (isMatched) {
                    isMatched = false;
                    currentPeerSession = null;
                    typeof resetPeerUI === 'function' && resetPeerUI();
                    document.getElementById('statusTitle') && (document.getElementById('statusTitle').innerText = "VOID//DISCONNECTED");
                    const statusPing = document.getElementById('statusPing'); if (statusPing) statusPing.className = "w-3 h-3 bg-red-600 animate-ping rounded-full";
                    const sessionText = document.getElementById('sessionText'); if (sessionText) sessionText.innerText = "TRANSMISSION ENDED";
                    const actionText = document.getElementById('actionText'); if (actionText) actionText.innerText = "FIND NEW CALL";
                }
            }
        } catch (err) {
            console.error('[MATCHMAKING_ERROR]', err);
        }
    }, 2000);
}

// Ensure presence heartbeat starts when DOM ready and mic init is attempted
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('sessionIdDisplay') && (document.getElementById('sessionIdDisplay').innerText = `SESSION ID: #${sessionToken.substring(0, 10).toUpperCase()}`);
    // start presence if not already
    window.startPresenceHeartbeat && window.startPresenceHeartbeat({ sessionToken: sessionToken, callsign: callsign, countryCode: countryCode, isCallActiveRef: _isCallActiveRef });

    // try init mic but don't block page
    initMicrophone();
});

// expose helper to stop heartbeat (used by END CALL action)
function stopHeartbeat() {
    if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
}

// also expose to window for backwards-compat
window.initMicrophone = initMicrophone;
window.startHeartbeatAndMatchmaking = startHeartbeatAndMatchmaking;
window.stopHeartbeat = stopHeartbeat;
