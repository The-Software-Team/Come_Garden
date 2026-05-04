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
            $table->foreignId('booking_id');

            $table->string('type');

            $table->integer('severity')->nullable(); 

            $table->string('status')->default('active');

            $table->integer('amount')->default(0);

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
