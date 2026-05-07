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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')->unique();
        
            $table->enum('status', ['available', 'in_use', 'maintenance']);

            $table->string('usage_status')->default('low');
    
            $table->integer('total_usage_hours')->default(0);
            $table->integer('maintenance_threshold_hours')->default(5);
        
            $table->boolean('is_active')->default(true);
    
            $table->timestamps();
        
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
