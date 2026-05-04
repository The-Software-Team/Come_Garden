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
        Schema::create('assignments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('shift_id');
        $table->foreignId('member_id');
        $table->foreignId('shift_task_id')->nullable();

        $table->string('role')->nullable();
        
        $table->string('status')->default('assigned');

        $table->integer('hours')->default(0);

        $table->timestamps();

        $table->index(['member_id', 'shift_id']);
    });  
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
