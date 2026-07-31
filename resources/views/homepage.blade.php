<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VOID CALLS // Instant Anonymous Audio Room</title>

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
    </style>
</head>

<body class="bg-black text-zinc-300 min-h-screen flex flex-col justify-between p-4 md:p-8 relative overflow-x-hidden selection:bg-white selection:text-black">

    <!-- CRT Overlay -->
    <div class="pointer-events-none fixed inset-0 z-50 scanline opacity-40"></div>

    <!-- Header -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between border-b border-zinc-800 pb-4 z-10">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 bg-red-600 animate-ping rounded-full"></span>
            <a href="{{ url('/') }}" class="font-pixel text-2xl tracking-widest text-white crt-glow">VOID//CALLS</a>
        </div>

        <!-- middle menu about us | reports | contact us | security measure -->
        <nav class="hidden md:flex items-center gap-4 text-xs font-mono text-zinc-500">
            <a href="#" class="hover:text-white transition-colors">[ABOUT_US]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-red-400 transition-colors">[REPORTS]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-white transition-colors">[CONTACT_US]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-emerald-400 transition-colors">[SECURITY]</a>
        </nav>

        <div class="flex items-center gap-2 text-xs font-mono text-zinc-500">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>SERVER_ONLINE: FREQ-09</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-5xl mx-auto my-auto py-10 z-10 grid md:grid-cols-12 gap-8 items-center">

        <!-- Left Side: Concept -->
        <div class="md:col-span-6 flex flex-col gap-6">
            <div class="inline-flex items-center gap-2 border border-zinc-800 bg-zinc-950 px-3 py-1 w-fit text-[11px] text-zinc-500 uppercase tracking-widest">
                <span>NO ACCOUNT NEEDED</span>
                <span>//</span>
                <span class="text-emerald-500">INSTANT ACCESS</span>
            </div>

            <h1 class="font-pixel text-5xl md:text-7xl font-bold tracking-wider leading-none text-white crt-glow">
                JUMP IN. TALK TO STRANGERS.
            </h1>

            <p class="text-sm md:text-base text-zinc-400 leading-relaxed">
                Langsung masuk ke server suara tanpa perlu *sign up* atau *login*. Cukup masukkan panggilan anonimmu, pilih channel, dan langsung terhubung secara *real-time*.
            </p>

            <!-- Audio Specs -->
            <div class="border-l-2 border-zinc-700 pl-4 py-1 text-xs text-zinc-500 space-y-1 font-mono">
                <p>&gt; Encrypted Peer-to-Peer Voice Mesh</p>
                <p>&gt; Zero Registration Logs Saved</p>
                <p>&gt; Instant Disconnect = Instant Purge</p>
            </div>
        </div>

        <!-- Right Side: Direct Join Call Form -->
        <div class="md:col-span-6 border border-zinc-800 bg-zinc-950 p-6 md:p-8 flex flex-col gap-6 relative shadow-2xl">
            <div class="flex justify-between items-center text-xs text-zinc-500 border-b border-zinc-800 pb-3">
                <span class="text-white font-mono font-bold tracking-widest">[ JOIN VOICE SERVER ]</span>
                <span class="text-emerald-500 font-mono">READY</span>
            </div>

            <!-- Disesuaikan ke route room -->
            <form action="{{ route('room.join') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <!-- Call Sign / Alias Input -->
                <div>
                    <label class="block text-xs uppercase tracking-widest text-zinc-400 mb-2 font-mono">
                        Alias / Call Sign:
                    </label>
                    <input
                        type="text"
                        name="callsign"
                        placeholder="e.g. Stranger_99"
                        required
                        class="w-full bg-black border border-zinc-800 focus:border-white text-white text-sm px-4 py-3 outline-none transition-colors font-mono placeholder:text-zinc-700" />
                </div>

                <!-- Room Selection -->
                <div>
                    <label class="block text-xs uppercase tracking-widest text-zinc-400 mb-2 font-mono">
                        Select Frequency / Server:
                    </label>
                    <select
                        name="room_id"
                        class="w-full bg-black border border-zinc-800 focus:border-white text-white text-sm px-4 py-3 outline-none transition-colors font-mono cursor-pointer">
                        <option value="random">⚡ Random Void Room (Fast Match)</option>
                        <option value="room-1">Channel #01 - Public Frequencies</option>
                        <option value="room-2">Channel #02 - Late Night Whispers</option>
                        <option value="room-3">Channel #03 - Lo-Fi & Noise</option>
                    </select>
                </div>

                <!-- Action Button -->
                <button
                    type="submit"
                    class="mt-2 w-full bg-white text-black font-bold uppercase tracking-widest py-4 text-sm hover:bg-zinc-300 transition-colors flex items-center justify-center gap-2 group">
                    <span>CONNECT TO VOICE SERVER</span>
                    <span class="group-hover:translate-x-1 transition-transform">►</span>
                </button>
            </form>

            <div class="text-[10px] text-zinc-600 text-center uppercase tracking-widest pt-2 border-t border-zinc-900">
                By clicking connect, you agree to enter unmoderated void space.
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 border-t border-zinc-900 pt-4 text-[11px] text-zinc-600 z-10">
        <div>
            NO LOGS // NO ACCOUNTS // AIRTALK-STYLE ANONYMOUS CALLS
        </div>
        <div class="flex gap-4">
            <a href="{{ url('/') }}" class="hover:text-zinc-400">[DISCONNECT ALL]</a>
            <a href="#" class="hover:text-zinc-400">[RULES]</a>
        </div>
    </footer>

</body>

</html>