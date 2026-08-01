<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('homepage');
});

Route::view('/login', 'login')->name('login');

Route::post('/join-room', function (Request $request) {
    // Simpan input ke session laravel
    session([
        'callsign' => $request->input('callsign', 'GHOST_OPERATOR'),
        'country_code' => $request->input('country_code', 'ID'),
    ]);

    return view('room', [
        'callsign' => session('callsign'),
        'room_id'  => $request->input('room_id')
    ]);
})->name('room.join');

Route::view('/rules', 'rules')->name('rules');