<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_reward_claims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_membership_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('membership_reward_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('claimed_at');

            $table->timestamps();

            $table->unique([
                'customer_membership_id',
                'membership_reward_id',
            ], 'membership_reward_unique_claim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_reward_claims');
    }
};
