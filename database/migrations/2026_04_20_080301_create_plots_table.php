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
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
        
            $table->enum('size', ['large', 'small']);
        
            $table->float('x');
            $table->float('y');
            $table->float('width');
            $table->float('height');
            $table->float('area');
        
            $table->string('status')->default('available');
            $table->string('soil_quality')->nullable();
        
            $table->string('infection_status')->nullable(); // healthy, infected, recovering
            $table->string('infection_type')->nullable();
            $table->timestamp('infection_date')->nullable();
        
            $table->timestamps();
        
            $table->index(['size', 'status']);
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
