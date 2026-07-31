<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signals', function (Blueprint $table) {
            $table->id();
            $table->string('sender_session', 64);
            $table->string('receiver_session', 64);
            $table->string('type', 20); // offer, answer, ice-candidate
            $table->string('payload', 4000); // Ubah dari text() ke string() berbatas
            $table->timestamps();

            $table->index(['receiver_session']);
        });

        DB::statement('ALTER TABLE signals ENGINE = MEMORY');
    }

    public function down(): void
    {
        Schema::dropIfExists('signals');
    }
};