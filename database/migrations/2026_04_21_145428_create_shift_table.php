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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->date('start_date');
            $table->date('end_date');

            $table->string('status')->default('scheduled');
            // scheduled, rescheduled, completed, incomplete

            $table->boolean('weather_adjusted')->default(false);
            $table->string('weather_note')->nullable();

            $table->timestamps();

            $table->index(['status']);
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift');
    }
};
