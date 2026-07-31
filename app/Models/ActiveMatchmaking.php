<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveMatchmaking extends Model
{
    use HasFactory;

    protected $table = 'active_matchmaking';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'session_token',
        'callsign',
        'country_code',
        'status',
        'paired_with',
        'ip_hash',
        'last_ping_at',
    ];

    protected $casts = [
        'last_ping_at' => 'datetime',
    ];
}