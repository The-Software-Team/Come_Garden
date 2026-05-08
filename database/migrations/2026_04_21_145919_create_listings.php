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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            $table->string('produce_name');

            $table->enum('type', [
                'standard',
                'gift',
                'flash'
            ])->default('standard');

            $table->decimal('quantity_kg', 8, 2);

            $table->decimal('price', 10, 2)
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->string('pickup_location')
                ->nullable();

            $table->integer('pickup_window_hours')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->enum('status', [
                'available',
                'reserved',
                'completed',
                'expired',
                'cancelled'
            ])->default('available');

            $table->text('allergen_flags')
                ->nullable();

            $table->decimal('quality_score', 3, 1)
                ->nullable();

            $table->timestamps();

            $table->index(['status', 'type']);
            $table->index('expires_at');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
