<?php

use App\Http\Controllers\Api\MatchmakingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/ping', [MatchmakingController::class, 'ping']);
    Route::post('/matchmake', [MatchmakingController::class, 'matchmake']);
    Route::post('/leave', [MatchmakingController::class, 'leave']);
    
    // endpoint signaling after matchmaking is met.
    Route::post('/signal/send', [MatchmakingController::class, 'sendSignal']);
    Route::post('/signal/get', [MatchmakingController::class, 'getSignals']);
    Route::post('/signal/cleanup', [MatchmakingController::class, 'cleanup']);
});