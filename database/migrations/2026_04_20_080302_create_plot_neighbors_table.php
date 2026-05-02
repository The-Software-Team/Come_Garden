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
        Schema::create('plot_neighbors', function (Blueprint $table) {
            $table->foreignId('plot_id')->constrained('plots')->onDelete('cascade');
            $table->foreignId('neighbor_id')->constrained('plots')->onDelete('cascade');
    
            $table->primary(['plot_id', 'neighbor_id']);
    
            $table->index('plot_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_neighbors');
    }
};
