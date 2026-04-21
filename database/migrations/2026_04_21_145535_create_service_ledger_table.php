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
        Schema::create('service_ledger', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')->constrained()->cascadeOnDelete();

            $table->integer('total_hours')->default(0);
            $table->integer('heavy_hours')->default(0);

            $table->integer('required_hours')->default(10);

            $table->timestamps();

            $table->index(['member_id']);
        }); 
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_ledger');
    }
};
