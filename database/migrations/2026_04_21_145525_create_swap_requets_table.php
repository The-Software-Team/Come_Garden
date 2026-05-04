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
        Schema::create('swap_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shift_id');
            $table->foreignId('assignment_id');
            $table->foreignId('requester_id');
            $table->foreignId('target_id');

            $table->string('status')->default('pending');

            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_requets');
    }
};
