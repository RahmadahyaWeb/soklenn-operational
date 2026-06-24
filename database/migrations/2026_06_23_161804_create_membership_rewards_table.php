<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_rewards', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedInteger('required_stamp');

            $table->enum('reward_type', [
                'discount_percentage',
                'free_service',
                'free_delivery',
                'tier_upgrade',
            ]);

            $table->string('reward_value')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_rewards');
    }
};
