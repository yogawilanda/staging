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

        <div class="flex items-center gap-3 text-xs text-zinc-500">
            <div class="flex items-center gap-2">
                <span class="text-emerald-600">Active: <span id="nav-active-count" class="font-bold">{{ $activeUserCounter }}</span></span>
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-[11px] text-zinc-500">
                <span class="text-zinc-400">Visitors: <span id="nav-visitor-count" class="text-white font-bold">{{ $visitorCount }}</span></span>
                <span class="text-zinc-400">Callers: <span id="nav-caller-count" class="text-white font-bold">{{ $callerCount }}</span></span>
            </div>
        </div>

<script>
// Refresh active user counts so the current visitor/caller is included after client pings
(async function refreshActiveCounts(){
    async function fetchCounts(){
        try{
            const res = await fetch('/api/v1/active_counts');
            if (!res.ok) return;
            const json = await res.json();
            if (json && json.data) {
                document.getElementById('nav-active-count').textContent = json.data.total;
                const v = document.getElementById('nav-visitor-count'); if (v) v.textContent = json.data.visitors;
                const c = document.getElementById('nav-caller-count'); if (c) c.textContent = json.data.callers;
            }
        } catch (e) {
            // ignore
        }
    }

    await fetchCounts(); // immediate
    setInterval(fetchCounts, 5000);
})();
</script>
    </div>
</header>