<header class="w-full max-w-6xl mx-auto flex items-center justify-between border-b border-zinc-800 pb-4 z-10 font-mono">
    <div class="flex items-center gap-3">
        <span class="w-3 h-3 {{ $statusDot ?? 'bg-red-600 animate-ping' }} rounded-full" id="statusPing"></span>

        <a href="{{ url('/') }}" class="font-pixel text-2xl tracking-widest text-white crt-glow" id="statusTitle">
            {{ $title ?? 'VOID//CALLS' }}
            <!-- {{ 'VOID//CALLS' }} -->
        </a>

        @isset($subtitle)
        <span class="text-xs text-zinc-600">|</span>
        <span class="text-xs text-zinc-400">{{ $subtitle }}</span>
        @endisset
    </div>

    <div>
        <div class="text-xs text-zinc-500 hidden sm:block">
            PING: <span class="text-emerald-400">18ms</span>
        </div>

        <div class="flex items-center gap-2 text-xs text-zinc-500">
            <span class="text-emerald-600">{{ 'Active User: '. $activeUserCounter }}</span>
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
        </div>
    </div>
</header>