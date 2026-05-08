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
        Schema::create('quality_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_id');

            $table->foreignId('user_id');

            $table->tinyInteger('score');

            $table->text('review')
                ->nullable();

            $table->timestamps();

            $table->unique(['listing_id', 'user_id']);
        }); 
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_ratings');
    }
};
