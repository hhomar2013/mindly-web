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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sliders', 'popup']);
            $table->enum('from', ['in', 'out']);
            $table->string('link');
            $table->string('image');
            $table->string('comment');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(false);
            $table->string('model_name');
            $table->morphs('ad_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
