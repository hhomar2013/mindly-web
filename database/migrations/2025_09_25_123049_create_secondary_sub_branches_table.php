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
        Schema::create('secondary_sub_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secondary_branch_id')
                ->constrained('secondary_branches')
                ->cascadeOnDelete();
            $table->string('sub_branch_id')->unique();
            $table->json('name');
            $table->timestamps();
            $table->index('sub_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_sub_branches');
    }
};
