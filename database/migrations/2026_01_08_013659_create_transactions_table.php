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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id') // kasir
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('meja_id') // customer
                ->nullable()
                ->constrained('meja')
                ->nullOnDelete();
            $table->string('no_trx');
            $table->string('waiter_name');
            $table->enum('order_type', ['dine_in', 'take_away']);
            $table->enum('status', ['ordered', 'paid'])->default('ordered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
