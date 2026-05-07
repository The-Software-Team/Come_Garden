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
        Schema::create('tool_waitlists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id');
            $table->foreignId('member_id');

            $table->integer('priority_score')->default(0);
            $table->integer('duration_hours');
    
            $table->enum('status', [
                'waiting',
                'processed',
                'expired',
                'cancelled'
            ])->default('waiting');

            $table->timestamps();
        
            $table->index(['tool_id', 'priority_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_waitlists');
    }
};
