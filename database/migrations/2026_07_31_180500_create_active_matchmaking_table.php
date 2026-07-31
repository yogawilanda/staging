<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('active_matchmaking', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_token', 64)->unique(); // Cookie/Session ID pengganti akun
            $table->string('callsign', 50);
            $table->string('country_code', 10)->default('ID');
            $table->enum('status', ['waiting', 'connecting', 'in_call'])->default('waiting');
            $table->uuid('paired_with')->nullable(); // UUID peer jika sedang in_call/connecting
            $table->string('ip_hash', 64)->nullable(); // IP Hash anonim untuk security/ban
            $table->timestamp('last_ping_at')->useCurrent();
            $table->timestamps();

            $table->index(['status', 'last_ping_at']);
        });

        // Paksa ubah Engine ke MEMORY (RAM)
        DB::statement('ALTER TABLE active_matchmaking ENGINE = MEMORY');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_matchmaking');
    }
};