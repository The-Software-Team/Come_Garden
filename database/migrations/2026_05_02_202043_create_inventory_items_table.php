<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('reorder_threshold')->default(10);

            $table->string('unit')->nullable();   // e.g. "kg", "packets"
            $table->string('type')->default('consumable'); // can be generalized later for  seed/tool/fertilizer
            $table->string('status')->default('active');

            $table->timestamps();

            $table->index('name');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};