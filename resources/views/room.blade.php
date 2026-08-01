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

    <script>
        // Provide server-side callsign & country to client JS (lightweight)
        window.CALLSIGN_OVERRIDE = @json(session('callsign', 'GHOST_OPERATOR'));
        window.COUNTRY_OVERRIDE = @json(session('country_code', 'ID'));
    </script>
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
                        <!-- Hapus session PHP di sini, biarkan dikontrol JS -->
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

    <!-- Scripts are loaded from the layout; room-specific client logic moved to public/js/room-client.js -->

</x-layouts.app>