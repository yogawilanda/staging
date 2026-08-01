<?php

use App\Models\ActiveMatchmaking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Buat command artisan khusus untuk cleanup
Artisan::command('matchmaking:purge', function () {
    $staleUsers = ActiveMatchmaking::where('last_ping_at', '<', now()->subSeconds(20))->get();

    foreach ($staleUsers as $user) {
        // Reset partner-nya jika ada yang digantung
        if (!empty($user->paired_with)) {
            ActiveMatchmaking::where('session_token', $user->paired_with)
                ->orWhere('id', $user->paired_with)
                ->update([
                    'status' => 'waiting',
                    'paired_with' => null,
                ]);
        }

        // Hapus user yang sudah mati suri
        $user->delete();
    }

    $this->info('Stale matchmaking users purged successfully.');
})->purpose('Clean up ghost or inactive matchmaking users');

// Jadwalkan agar berjalan otomatis setiap 15 detik
Schedule::command('matchmaking:purge')->everyFifteenSeconds();