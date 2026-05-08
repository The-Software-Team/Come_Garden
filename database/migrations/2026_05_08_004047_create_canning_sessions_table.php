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
        Schema::create('canning_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizer_id');

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->string('location')
                ->nullable();

            $table->dateTime('scheduled_at');

            $table->integer('max_members')
                ->default(5);

            $table->integer('current_count')
                ->default(0);

            $table->enum('status', [
                'open',
                'closed',
                'completed',
                'cancelled'
            ])->default('open');

            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canning_sessions');
    }
};
