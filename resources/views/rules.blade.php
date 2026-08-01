<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VOID CALLS // Rules & Guidelines</title>

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

<body class="bg-black text-zinc-300 min-h-screen flex flex-col justify-between p-4 md:p-6 relative overflow-x-hidden selection:bg-white selection:text-black">

    <!-- CRT Overlay -->
    <div class="pointer-events-none fixed inset-0 z-50 scanline opacity-40"></div>

    <!-- Header -->
    <header class="w-full max-w-4xl mx-auto flex items-center justify-between border-b border-zinc-800 pb-3 z-10">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 bg-red-600 animate-ping rounded-full"></span>
            <a href="{{ url('/') }}" class="font-pixel text-2xl tracking-widest text-white crt-glow">VOID//CALLS</a>
        </div>

        <!-- Middle Menu -->
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
    <main class="w-full max-w-3xl mx-auto my-auto py-4 z-10 flex flex-col gap-4">

        <!-- Title Section -->
        <div class="flex flex-col gap-1 border-b border-zinc-800 pb-4">
            <div class="inline-flex items-center gap-2 border border-zinc-800 bg-zinc-950 px-3 py-0.5 w-fit text-[10px] text-zinc-500 uppercase tracking-widest">
                <span>SYSTEM PROTOCOL</span>
                <span>//</span>
                <span class="text-emerald-500">GUIDELINES</span>
            </div>
            <h1 class="font-pixel text-3xl md:text-5xl font-bold tracking-wider leading-none text-white crt-glow">
                VOID_RULES.LOG
            </h1>
            <p class="text-xs text-zinc-400 font-mono">
                &gt; Read carefully before transmitting across frequencies.
            </p>
        </div>

        <!-- Rules Grid / List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-zinc-400 font-mono">
            <div class="border border-zinc-800 bg-zinc-950 p-3.5 flex flex-col gap-1.5">
                <div class="text-white font-bold tracking-widest text-xs flex items-center justify-between">
                    <span>01 // ABSOLUTE ANONYMITY</span>
                    <span class="text-[10px] text-emerald-500">MANDATORY</span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Never reveal real identity, legal name, exact location, or private credentials. What happens in the void stays in the void.
                </p>
            </div>

            <div class="border border-zinc-800 bg-zinc-950 p-3.5 flex flex-col gap-1.5">
                <div class="text-white font-bold tracking-widest text-xs flex items-center justify-between">
                    <span>02 // ZERO PERSISTENCE</span>
                    <span class="text-[10px] text-emerald-500">ENFORCED</span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Audio streams are peer-to-peer and unrecorded. Do not attempt to harvest, scrape, or record transmissions without consent.
                </p>
            </div>

            <div class="border border-zinc-800 bg-zinc-950 p-3.5 flex flex-col gap-1.5">
                <div class="text-white font-bold tracking-widest text-xs flex items-center justify-between">
                    <span>03 // FREQUENCY ETIQUETTE</span>
                    <span class="text-[10px] text-yellow-500">RESPECT</span>
                </div> 
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Avoid microphone screaming or spamming audio channels. Violation may result in instant device/IP termination.
                </p>
            </div>

            <div class="border border-zinc-800 bg-zinc-950 p-3.5 flex flex-col gap-1.5">
                <div class="text-white font-bold tracking-widest text-xs flex items-center justify-between">
                    <span>04 // DISCONNECTION PURGE</span>
                    <span class="text-[10px] text-emerald-500">AUTOMATIC</span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Dropping the call immediately severs data channels and purges session nodes. If a channel turns toxic, disconnect.
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div>
            <a href="{{ url('/') }}" class="w-full bg-white text-black font-bold uppercase tracking-widest py-3 text-xs hover:bg-zinc-300 transition-colors flex items-center justify-center gap-2 group block text-center">
                <span class="group-hover:-translate-x-1 transition-transform">◄</span>
                <span>RETURN TO HOMEPAGE</span>
            </a>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2 border-t border-zinc-900 pt-3 text-[11px] text-zinc-600 z-10">
        <div>
            NO LOGS // NO ACCOUNTS // ANONYMOUS CALLS
        </div>
        <div class="flex gap-4">
            <a href="{{ url('/') }}" class="hover:text-zinc-400">[DISCONNECT ALL]</a>
            <a href="{{ url('/') }}" class="hover:text-zinc-400">[RULES]</a>
        </div>
    </footer>

</body>

</html>