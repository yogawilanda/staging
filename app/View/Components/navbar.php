<?php

namespace App\View\Components;

use App\Models\ActiveMatchmaking;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class navbar extends Component
{
    public int $activeUserCounter;
    public int $visitorCount;
    public int $callerCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {   
        // manfaatin caching supaya ga selalu manggil.
        $this->activeUserCounter = cache()->remember('active_users_count', now()->addSeconds(5), function () {
            return ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds(15))->count();
        });

        $this->visitorCount = cache()->remember('visitor_count', now()->addSeconds(5), function () {
            return ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds(15))
                ->where('status', 'visitor')
                ->count();
        });

        $this->callerCount = cache()->remember('caller_count', now()->addSeconds(5), function () {
            return ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds(15))
                ->whereIn('status', ['waiting','matched'])
                ->count();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
