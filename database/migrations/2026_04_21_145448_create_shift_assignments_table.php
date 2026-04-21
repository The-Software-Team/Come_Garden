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
        Schema::create('shift_assignments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
        $table->foreignId('member_id')->constrained()->cascadeOnDelete();
        $table->foreignId('shift_task_id')->nullable()->constrained()->nullOnDelete();

        $table->string('role')->nullable();
        // heavy, light

        $table->string('status')->default('assigned');
        // assigned, completed, missed

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
        Schema::dropIfExists('shift_assignments');
    }
};
