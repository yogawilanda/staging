<x-layouts.app>
    <x-slot:title>VOID CALLS // Instant Anonymous Audio Room</x-slot:title>

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
                Jump straight into the voice channel. No sign-up/login needed. Just drop a display name, pick a room, and connect instantly in real time.
            </p>

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

            <form action="{{ route('room.join') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <div>
                    <label class="block text-xs uppercase tracking-widest text-zinc-400 mb-2 font-mono">
                        Call Sign:
                    </label>
                    <input
                        type="text"
                        name="callsign"
                        placeholder="e.g. Stranger_99"
                        required
                        class="w-full bg-black border border-zinc-800 focus:border-white text-white text-sm px-4 py-3 outline-none transition-colors font-mono placeholder:text-zinc-700" />
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-zinc-400 mb-2 font-mono">
                        Select Server:
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
</x-layouts.app>