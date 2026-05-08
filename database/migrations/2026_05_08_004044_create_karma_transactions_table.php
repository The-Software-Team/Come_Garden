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
        Schema::create('karma_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            $table->integer('points');

            $table->string('reason');

            $table->unsignedBigInteger('reference_id')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->index('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karma_transactions');
    }
};
