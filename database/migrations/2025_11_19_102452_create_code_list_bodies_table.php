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
        Schema::create('code_list_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_list_head_id')->constrained('code_list_heads')->cascadeOnDelete();
            $table->foreignId('type_of_subscription_id')->constrained('type_of_subscriptions')->cascadeOnDelete();
            $table->string('code', 14)->unique();
            $table->decimal('code_price', 10, 2)->default(0.00);
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('students')->nullOnDelete();
            $table->integer('usage_count')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_list_bodies');
    }
};
