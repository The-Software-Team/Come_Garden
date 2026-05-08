<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('qr_token')
                ->nullable()
                ->unique();

            $table->timestamp('picked_up_at')
                ->nullable();

            $table->timestamp('returned_scanned_at')
                ->nullable();

            $table->timestamp('cleaned_at')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'qr_token',
                'picked_up_at',
                'returned_scanned_at',
                'cleaned_at'
            ]);

        });
    }
};