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
        Schema::create('plot_infections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plot_id')->constrained()->restrictOnDelete();

            $table->string('type');
            $table->date('infection_date');

            $table->timestamps();
    
            $table->index('plot_id');
            $table->index('infection_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_infections');
    }
};
