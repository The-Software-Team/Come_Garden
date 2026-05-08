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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_id');

            $table->foreignId('seller_id');

            $table->foreignId('buyer_id');

            $table->decimal('quantity', 8, 2);

            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('note')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
