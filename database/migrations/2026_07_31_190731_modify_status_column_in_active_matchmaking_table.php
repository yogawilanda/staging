<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('active_matchmaking', function (Blueprint $table) {
            // Ubah tipe kolom status jadi string agar muat nilai 'matched'
            $table->string('status', 20)->default('waiting')->change();
        });
    }

    public function down(): void
    {
        Schema::table('active_matchmaking', function (Blueprint $table) {
            //
        });
    }
};