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
        Schema::create('plot_crops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plot_id');
            $table->foreignId('user_id');

            $table->string('type'); // tomato, carrot
            $table->string('stage')->default('seed'); // seed, growing, harvest

            $table->timestamp('planted_at');

            $table->timestamps();

            $table->index(['plot_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_crops');
    }
};
