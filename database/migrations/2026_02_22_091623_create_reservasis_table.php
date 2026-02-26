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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('meja')->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->string('no_telepon')->nullable();
            $table->dateTime('waktu_reservasi');
            $table->enum('status', ['Pending', 'Selesai', 'Dibatalkan'])->nullable()->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
