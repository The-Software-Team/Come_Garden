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
        Schema::create('rental_applications', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('member_id');
            $table->foreignId('plot_id');
        
            $table->float('share');
            $table->boolean('auto_renew')->default(false);
        
            $table->string('status')->default('pending');
            $table->integer('score')->default(0);
        
            $table->timestamps();
        
            $table->index('member_id');
            $table->index('plot_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_applications');
    }
};
