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

            $table->foreignId('wallet_id')->constrained()->restrictOnDelete();

            $table->decimal('amount', 12, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('reason');
            $table->string('reference_id')->nullable();

            $table->timestamps();

            $table->index('wallet_id');
            $table->index('type');
            $table->index('created_at');

            $table->index(['wallet_id', 'created_at']);
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
