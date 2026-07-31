<?php

namespace App\Console\Commands;

use App\Models\ActiveMatchmaking;
use Illuminate\Console\Command;

class PurgeInactiveMatchmaking extends Command
{
    protected $signature = 'matchmaking:purge';
    protected $description = 'Purge inactive matchmaking users where last_ping_at > 15 seconds';

    public function handle()
    {
        $cutoff = now()->subSeconds(15);

        // Lepas pair kawan jika user dipurge
        $inactiveUsers = ActiveMatchmaking::where('last_ping_at', '<', $cutoff)->get();

        foreach ($inactiveUsers as $user) {
            if ($user->paired_with) {
                ActiveMatchmaking::where('id', $user->paired_with)->update([
                    'status' => 'waiting',
                    'paired_with' => null
                ]);
            }
            $user->delete();
        }
    }
}