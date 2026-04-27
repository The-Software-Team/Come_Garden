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
        Schema::create('rental_participants', function (Blueprint $table) {
            $table->id();
    
            $table->foreignId('rental_id');
            $table->foreignId('member_id');

            $table->float('share');
            $table->decimal('cost', 12, 2);
            $table->boolean('late')->default(false);
            $table->boolean('auto_renew')->default(false);

            $table->timestamps();

            $table->index('rental_id');
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_participants');
    }
};
