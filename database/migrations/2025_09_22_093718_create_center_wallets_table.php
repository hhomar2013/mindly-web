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
        Schema::create('center_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('center_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_wallets');
    }
};
