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
        Schema::create('swap_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();

            $table->foreignId('requester_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('target_id')->constrained('members')->cascadeOnDelete();

            $table->string('status')->default('pending');
            // pending, approved, rejected

            $table->timestamps();

            $table->index(['requester_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_requets');
    }
};
