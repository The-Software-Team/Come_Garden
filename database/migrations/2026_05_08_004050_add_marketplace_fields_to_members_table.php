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
        Schema::table('members', function (Blueprint $table) {

            $table->integer('karma_points')
                ->default(0);

            $table->integer('seedbank_credits')
                ->default(0)
                ->after('karma_points');

            $table->text('allergen_preferences')
                ->nullable()
                ->after('seedbank_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
