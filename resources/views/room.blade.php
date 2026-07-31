<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VOID CALLS // 1-on-1 Transmission</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=VT323&family=Share+Tech+Mono&display=swap');

        body {
            font-family: 'Share Tech Mono', monospace;
        }

        .font-pixel {
            font-family: 'VT323', monospace;
        }

        .scanline {
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0),
                    rgba(255, 255, 255, 0) 50%,
                    rgba(0, 0, 0, 0.3) 50%,
                    rgba(0, 0, 0, 0.3));
            background-size: 100% 4px;
        }

        .crt-glow {
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
        }

        .speaking {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
            border-color: #10b981 !important;
        }
    </style>
</head>

<body class="bg-black text-zinc-300 min-h-screen flex flex-col justify-between p-4 md:p-6 relative overflow-x-hidden selection:bg-white selection:text-black">

    <!-- CRT Overlay -->
    <div class="pointer-events-none fixed inset-0 z-50 scanline opacity-40"></div>

    <!-- Top Status Bar -->
    <header class="w-full max-w-6xl mx-auto flex items-center justify-between border-b border-zinc-800 pb-3 z-10">
        <div class="flex items-center gap-3">
            <span id="statusPing" class="w-3 h-3 bg-yellow-500 animate-ping rounded-full"></span>
            <span id="statusTitle" class="font-pixel text-xl tracking-widest text-white crt-glow">VOID//SEARCHING</span>
            <span class="text-xs text-zinc-600">|</span>
            <span class="text-xs text-zinc-400 font-mono">1-ON-1 DIRECT FREQUENCY</span>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-xs text-zinc-500 font-mono hidden sm:block">
                PING: <span class="text-emerald-400">18ms</span>
            </div>
        </div>
    </header>

    <!-- Main Call Grid Layout -->
    <main class="w-full max-w-6xl mx-auto my-auto py-6 z-10 grid md:grid-cols-12 gap-6 items-start">

        <!-- Left Side: 1-on-1 Call Peers (8 Cols) -->
        <div class="md:col-span-8 flex flex-col gap-4">

            <div class="flex justify-between items-center text-xs text-zinc-500 font-mono border-b border-zinc-900 pb-2">
                <span id="sessionText">SEARCHING FOR PEER...</span>
                <span class="text-emerald-500">E2E ENCRYPTED</span>
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
        <div class="md:col-span-4 border border-zinc-800 bg-zinc-950 p-4 flex flex-col gap-4 h-[420px] relative">

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

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto flex justify-between items-center border-t border-zinc-900 pt-3 text-[11px] text-zinc-600 z-10 font-mono">
        <div id="sessionIdDisplay">SESSION ID: INIT...</div>
        <div id="footerStatus">STATUS: MATCHMAKING</div>
    </footer>

    <!-- Hidden Audio Player -->
    <audio id="remote-audio" autoplay playsinline></audio>

    <!-- Import JS Modules -->
    <script src="{{ asset('js/void-ui.js') }}"></script>
    <script src="{{ asset('js/void-webrtc.js') }}"></script>

    <script>
        // 1. Core State Variables
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

        // Dynamic Variables from Blade Session
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

        // 2. Lifecycle
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('sessionIdDisplay').innerText = `SESSION ID: #${sessionToken.substring(0, 10).toUpperCase()}`;
            initMicrophone();
        });

        async function initMicrophone() {
            try {
                if (localStream) return;

                localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: false
                });
                console.log('[VOID//WEBRTC] Microphone initialized.');
                startHeartbeatAndMatchmaking();
            } catch (err) {
                alert('[ERROR] Gagal mengakses mikrofon. Harap berikan izin akses mic!');
                console.error(err);
            }
        }

        function startHeartbeatAndMatchmaking() {
            pollInterval = setInterval(async () => {
                if (!isCallActive) return;

                // Ping Server
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

                // Check WebRTC Signals
                checkPendingSignals();

                // Matchmaking Loop
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


</body>

</html>