<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel transaksi panggilan (InnoDB)
        Schema::create('calls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('caller_callsign', 50);
            $table->string('receiver_callsign', 50);
            $table->string('caller_country', 10)->default('ID');
            $table->string('receiver_country', 10)->default('ID');
            $table->integer('duration_seconds')->default(0);
            $table->string('end_reason', 50)->nullable(); // e.g., user_disconnected, peer_disconnected, reported
            $table->timestamps();
        });

        // Tabel laporan pelanggaran (InnoDB)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('call_id')->nullable()->constrained('calls')->nullOnDelete();
            $table->string('reporter_session', 64);
            $table->string('reported_callsign', 50);
            $table->string('reason', 255);
            $table->string('reporter_ip_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('calls');
    }
};