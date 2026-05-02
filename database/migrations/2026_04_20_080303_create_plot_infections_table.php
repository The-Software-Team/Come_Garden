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

            $table->foreignId('plot_id')->constrained('plots')->onDelete('cascade');

            $table->string('type'); // blight, pest, fungus
            $table->string('severity')->default('low'); // low, medium, high

            $table->timestamp('infection_date');

            // lifecycle tracking
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['plot_id', 'infection_date']);
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
