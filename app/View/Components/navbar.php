<?php

namespace App\View\Components;

use App\Models\ActiveMatchmaking;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class navbar extends Component
{   
    public int $activeUserCounter;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->activeUserCounter = ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds(10))
            ->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
