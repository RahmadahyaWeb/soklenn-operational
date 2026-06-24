<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->foreignId('membership_reward_claim_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('membership_reward_claims')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropConstrainedForeignId(
                'membership_reward_claim_id'
            );

        });
    }
};
