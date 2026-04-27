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
        Schema::create('seed_batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id');

            $table->string('seed_type');
            $table->integer('quantity');

            $table->integer('viability'); // 0–100 rule in service
            $table->string('origin');
            $table->integer('age');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed_batches');
    }
};
