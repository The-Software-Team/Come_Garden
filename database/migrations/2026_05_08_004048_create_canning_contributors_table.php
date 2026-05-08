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
        Schema::create('canning_contributors', function (Blueprint $table) {
        $table->id();

        $table->foreignId('session_id')
            ->constrained('canning_sessions')
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained('members')
            ->cascadeOnDelete();

        $table->string('produce_name');

        $table->decimal('quantity_kg', 8, 2);

        $table->timestamps();

        $table->unique(['session_id', 'user_id']);
    });  
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canning_contributors');
    }
};
