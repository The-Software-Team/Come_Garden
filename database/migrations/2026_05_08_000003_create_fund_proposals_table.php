<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_proposals', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description');
            $table->decimal('estimated_cost', 10, 2);
            $table->foreignId('proposed_by')->constrained('members');
            $table->string('status')->default('open'); // open, approved, rejected
            $table->timestamp('voting_ends_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_proposals');
    }
};
