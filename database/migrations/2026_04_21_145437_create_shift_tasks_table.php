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
        Schema::create('shift_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('category');
            // heavy, light

            $table->integer('estimated_hours')->default(2);

            $table->timestamps();

            $table->index(['shift_id', 'category']);
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_tasks');
    }
};
