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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id');
            $table->foreignId('member_id');

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('actual_return_time')->nullable();

            $table->string('status')->default('active');
            // active, completed, overdue, cancelled

            $table->boolean('cleaned')->default(true);

            $table->timestamps();

            $table->index(['tool_id', 'status']);
            $table->index(['member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
