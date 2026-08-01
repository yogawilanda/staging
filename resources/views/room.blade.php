<x-layouts.app>
    <x-slot:title>VOID // CALLS</x-slot:title>

    @push('styles')
    <style>
        .speaking {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
            border-color: #10b981 !important;
        }
    </style>
    @endpush

    <span id="statusTitle" class=""></span>
    <!-- Main Call Grid Layout -->
    <main class="w-full max-w-6xl mx-auto my-auto py-2 z-10 grid md:grid-cols-12 gap-6 items-start">

        <!-- Left Side: 1-on-1 Call Peers (8 Cols) -->
        <div class="md:col-span-8 flex flex-col gap-4">

            <div class="flex justify-between items-center text-xs text-zinc-500 font-mono border-b border-zinc-900 pb-2">
                <span id="sessionText">SEARCHING FOR PEER...</span>
                <!-- <span class="text-xs text-zinc-600">|</span> -->
                <!-- <span class="text-emerald-500">E2E ENCRYPTED</span> -->
                <span class="text-xs text-zinc-600">|</span>
                <span class="text-xs text-zinc-400">1-ON-1 DIRECT FREQUENCY</span>
            </div>

            <!-- 1-on-1 Grid Slots -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Peer 1: You -->
                <div class="border border-zinc-800 bg-zinc-950 p-6 flex flex-col items-center justify-center gap-3 relative speaking transition-all">
                    <div class="w-20 h-20 rounded-full bg-zinc-900 border border-emerald-500/50 flex items-center justify-center relative">
                        <span class="font-pixel text-3xl text-white">YOU</span>
                        <span class="absolute -bottom-1 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-black"></span>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-bold text-white tracking-wider font-mono">
                            {{ session('callsign', 'GHOST_OPERATOR') }}
                        </div>
                        <div class="text-xs text-emerald-400 font-mono mt-1">
                            NATION: <span class="text-white font-bold">{{ session('country_code', 'ID') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Peer 2: Stranger (Dynamic Card) -->
                <div id="strangerCard" class="border border-dashed border-zinc-800 bg-zinc-950/50 p-6 flex flex-col items-center justify-center gap-3 relative transition-all">
                    <div id="peer-avatar" class="w-20 h-20 rounded-full bg-zinc-900 border border-zinc-700 flex items-center justify-center relative font-pixel text-3xl text-zinc-500">
                        ?
                    </div>
                    <div class="text-center">
                        <div id="peer-callsign" class="text-sm font-bold text-zinc-500 tracking-wider font-mono animate-pulse">
                            SEARCHING...
                        </div>
                        <div id="peer-country" class="text-xs text-zinc-600 font-mono mt-1">
                            NATION: UNKNOWN
                        </div>
                    </div>
                </div>

            </div>

            <!-- Call Control Bar -->
            <div class="border border-zinc-800 bg-zinc-950 p-4 mt-2 flex items-center justify-center gap-4 flex-wrap">
                <button
                    id="muteBtn"
                    onclick="toggleMute()"
                    class="px-6 py-3 border border-zinc-700 hover:border-white bg-black text-white text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2">
                    <span id="muteIcon">🎙️</span>
                    <span id="muteText">MUTE MIC</span>
                </button>

                <button
                    id="actionBtn"
                    onclick="handleCallAction()"
                    class="px-6 py-3 border border-red-900/80 bg-red-950/40 text-red-400 hover:bg-red-900 hover:text-white text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2 font-mono">
                    <span>🛑</span>
                    <span id="actionText">END CALL</span>
                </button>
            </div>

        </div>

        <!-- Right Side: Terminal Log & Live Text Chat (4 Cols) -->
        <div class="md:col-span-4 border border-zinc-800 bg-zinc-950 p-4 flex flex-col gap-4 h-105 relative">

            <div class="flex justify-between items-center text-xs text-zinc-500 border-b border-zinc-900 pb-2">
                <span class="text-white font-mono font-bold">[ FREQUENCY LOG ]</span>
                <span class="text-xs text-zinc-600">DIRECT</span>
            </div>

            <div id="chatLog" class="flex-1 overflow-y-auto space-y-3 pr-2 text-xs font-mono text-zinc-400">
                <div class="text-zinc-600">&gt; System initialized. Codec: Opus 48kHz.</div>
                <div class="text-zinc-500" id="log-status">&gt; Scanning active frequencies...</div>
            </div>

            <form onsubmit="sendMessage(event)" class="mt-auto border-t border-zinc-900 pt-3 flex gap-2">
                <input
                    id="chatInput"
                    type="text"
                    placeholder="Send signal text..."
                    class="w-full bg-black border border-zinc-800 focus:border-zinc-500 text-white text-xs px-3 py-2 outline-none font-mono placeholder:text-zinc-700" />
                <button type="submit" class="border border-zinc-700 bg-white text-black font-bold px-3 text-xs hover:bg-zinc-300 transition-colors font-mono">
                    SEND
                </button>
            </form>

        </div>

    </main>

    <!-- Footer Dynamic Meta Info -->
    <div class="w-full max-w-6xl mx-auto flex justify-between items-center pt-3 text-[11px] text-zinc-600 z-10 font-mono">
        <div id="sessionIdDisplay">SESSION ID: INIT...</div>
        <div id="footerStatus">STATUS: MATCHMAKING</div>
    </div>

    <!-- Hidden Audio Player -->
    <audio id="remote-audio" autoplay playsinline></audio>

    <!-- Import JS Modules -->
    <script src="{{ asset('js/void-ui.js') }}"></script>
    <script src="{{ asset('js/void-webrtc.js') }}"></script>

    <script>
        // Core State Variables
        let isMuted = false;
        let isCallActive = true;
        let localStream = null;
        let peerConnection = null;
        let currentPeerSession = null;
        let pollInterval = null;
        let isMatchedUI = false;
        let isMatched = false;
        let pendingCandidates = [];

        // Session Token Management
        let sessionToken = sessionStorage.getItem('void_session_token');
        if (!sessionToken) {
            sessionToken = 'sec_' + Math.random().toString(36).substring(2, 11) + Date.now().toString(36);
            sessionStorage.setItem('void_session_token', sessionToken);
        }

        const callsign = "{{ session('callsign', 'GHOST_OPERATOR') }}";
        const countryCode = "{{ session('country_code', 'ID') }}";

        const rtcConfig = {
            iceServers: [{
                    urls: 'stun:stun.l.google.com:19302'
                },
                {
                    urls: 'stun:stun1.l.google.com:19302'
                },
                {
                    urls: 'stun:stun2.l.google.com:19302'
                },
                {
                    urls: 'stun:stun3.l.google.com:19302'
                }
            ]
        };

        const pc = new RTCPeerConnection(rtcConfig);

        document.addEventListener('DOMContentLoaded', async () => {
            document.getElementById('sessionIdDisplay').innerText = `SESSION ID: #${sessionToken.substring(0, 10).toUpperCase()}`;
            await cleanupOldSession();
            initMicrophone();
        });

        async function initMicrophone() {
            try {
                if (localStream) return;
                localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: false
                });
                startHeartbeatAndMatchmaking();
            } catch (err) {
                alert('[ERROR] Gagal mengakses mikrofon. Harap berikan izin akses mic!');
                console.error(err);
            }
        }

        function startHeartbeatAndMatchmaking() {
            pollInterval = setInterval(async () => {
                if (!isCallActive) return;

                try {
                    await fetch('/api/v1/ping', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session_token: sessionToken,
                            callsign: callsign,
                            country_code: countryCode
                        })
                    });
                } catch (err) {
                    console.error('[PING_ERROR]', err);
                }

                checkPendingSignals();

                if (!isMatched) {
                    try {
                        const res = await fetch('/api/v1/matchmake', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                session_token: sessionToken,
                                callsign: callsign,
                                country_code: countryCode
                            })
                        });
                        const data = await res.json();

                        if (data.status === 'matched' && data.peer) {
                            isMatched = true;
                            currentPeerSession = data.peer.id;
                            updateMatchedUI(data.peer);

                            if (data.role === 'initiator') {
                                initiateCall(data.peer.id);
                            }
                        }
                    } catch (err) {
                        console.error('[MATCHMAKING_ERROR]', err);
                    }
                }
            }, 2000);
        }

        window.addEventListener('beforeunload', () => {
            const data = JSON.stringify({
                session_token: sessionToken
            });
            const blob = new Blob([data], {
                type: 'application/json'
            });
            navigator.sendBeacon('/api/v1/leave', blob);
        });
    </script>
</x-layouts.app>