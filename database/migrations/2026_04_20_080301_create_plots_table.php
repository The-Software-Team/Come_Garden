<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();

            // Size category
            $table->enum('size', ['small', 'large']);

            // Center coordinates (origin = allotment center)
            $table->decimal('x', 10, 4);
            $table->decimal('y', 10, 4);

            // Dimensions
            $table->decimal('width', 8, 4);
            $table->decimal('height', 8, 4);
            $table->decimal('area', 10, 4);

            // Bounding box — derived from x/y/w/h, stored for fast spatial queries
            $table->decimal('x_min', 10, 4);
            $table->decimal('x_max', 10, 4);
            $table->decimal('y_min', 10, 4);
            $table->decimal('y_max', 10, 4);

            // Sun exposure profile (east/west/center based on x relative to allotment width)
            $table->enum('sun_profile', ['east', 'west', 'center'])->nullable();

            // Lifecycle
            $table->enum('status', ['available', 'rented', 'maintenance', 'inactive'])->default('available');

            // Soil & infection (kept from your original model)
            $table->string('soil_quality')->nullable();
            $table->boolean('infection_status')->default(false);
            $table->string('infection_type')->nullable();
            $table->date('infection_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};