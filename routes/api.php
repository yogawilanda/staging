<?php

use App\Http\Controllers\Api\MatchmakingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/ping', [MatchmakingController::class, 'ping']);
    Route::post('/matchmake', [MatchmakingController::class, 'matchmake']);
    Route::post('/leave', [MatchmakingController::class, 'leave']);
    
    // Pastikan endpoint signaling ini sesuai dengan fetch() JS
    Route::post('/signal/send', [MatchmakingController::class, 'sendSignal']);
    Route::post('/signal/get', [MatchmakingController::class, 'getSignals']);
});