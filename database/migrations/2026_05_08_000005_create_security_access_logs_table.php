<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_access_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')->constrained();
            $table->string('gate_code_used');
            $table->string('action'); // entry, exit
            $table->string('gate_location')->default('main_gate');
            $table->timestamp('accessed_at');

            $table->timestamps();

            $table->index(['member_id', 'accessed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_access_logs');
    }
};
