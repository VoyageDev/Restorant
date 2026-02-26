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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_menu_id')
                ->constrained('kategori_menu')
                ->restrictOnDelete();
            $table->string('name')->unique();
            $table->string('price');
            $table->integer('stock');
            $table->integer('daily_stock');
            $table->enum('status', ['Tersedia', 'Habis'])->default('tersedia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
