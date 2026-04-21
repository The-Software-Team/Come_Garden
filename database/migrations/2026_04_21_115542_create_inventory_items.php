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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->string('name');              // fertilizer, compost, etc.
            $table->string('category');          // soil, nutrient, tool-aid
            
            $table->integer('total_quantity')->default(0);
            $table->integer('available_quantity')->default(0);
    
            $table->integer('reorder_threshold')->default(10);
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
