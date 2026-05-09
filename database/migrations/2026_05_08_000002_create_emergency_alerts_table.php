<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_alerts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')->constrained('members');
            $table->string('title');
            $table->text('message');
            $table->string('severity')->default('warning'); // info, warning, critical
            $table->boolean('is_active')->default(true);
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_alerts');
    }
};
