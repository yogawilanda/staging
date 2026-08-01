<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'VOID CALLS // Instant Anonymous Audio Room' }}</title>

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

    @push('styles')
    <style>
        .speaking {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
            border-color: #10b981 !important;
        }
    </style>
    @endpush

    <!-- Dynamic Navbar for Room State -->
    <x-navbar />

    <!-- Page Content Injection -->
    {{ $slot }}

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 border-t border-zinc-900 pt-4 text-[11px] text-zinc-600 z-10">
        <div>
            NO ACCOUNTS // ANONYMOUS CALLS
        </div>

        <nav class="hidden md:flex items-center gap-4 text-xs text-zinc-500">
            <a href="#" class="hover:text-white transition-colors">[ABOUT_US]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-red-400 transition-colors">[REPORTS]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-white transition-colors">[CONTACT_US]</a>
            <span class="text-zinc-800">//</span>
            <a href="#" class="hover:text-emerald-400 transition-colors">[SECURITY]</a>
        </nav>
        <div class="flex gap-4">
            <a href="{{ url('/') }}" class="hover:text-zinc-400">[DISCONNECT ALL]</a>
            <a href="{{ route('rules') }}" class="hover:text-zinc-400">[RULES]</a>
        </div>
    </footer>

</body>
<script src="{{ asset('js/void-ui.js') }}"></script>

</html>