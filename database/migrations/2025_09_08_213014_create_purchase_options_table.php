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
       Schema::create('purchase_options', function (Blueprint $table) {
            $table->id();
            $table->morphs('purchasable');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('price_note')->nullable();
            $table->enum('state', ['available','not_available'])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_options');
    }
};
