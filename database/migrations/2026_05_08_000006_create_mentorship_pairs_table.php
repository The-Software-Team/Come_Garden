<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentorship_pairs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mentor_id')->constrained('members');
            $table->foreignId('mentee_id')->constrained('members');
            $table->json('shared_interests')->nullable();
            $table->string('status')->default('active');

            $table->timestamps();

            $table->unique(['mentor_id', 'mentee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentorship_pairs');
    }
};
