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
        Schema::create('tool_penalties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id');
            $table->foreignId('tool_booking_id');

            $table->string('type');
            // service, fine

            $table->integer('severity')->nullable(); 
            // 1, 2, 3 OR monetary conversion

            $table->string('status')->default('active');
            // active, resolved

            $table->timestamps();

            $table->index(['member_id']);
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_penalties');
    }
};
