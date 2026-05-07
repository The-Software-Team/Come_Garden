<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_neighbors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plot_id')
                ->constrained('plots')
                ->cascadeOnDelete();

            $table->foreignId('neighbor_plot_id')
                ->constrained('plots')
                ->cascadeOnDelete();

            // Direction makes queries like "who is above me?" efficient
            $table->enum('direction', ['north', 'south', 'east', 'west']);

            $table->timestamps();

            // Each directional pair is unique; both directions are stored explicitly
            // so queries from either side are a simple WHERE plot_id = ?
            $table->unique(['plot_id', 'neighbor_plot_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plot_neighbors');
    }
};