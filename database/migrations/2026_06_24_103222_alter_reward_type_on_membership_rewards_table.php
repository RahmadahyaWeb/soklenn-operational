<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE membership_rewards
            MODIFY reward_type ENUM(
                'discount_percentage',
                'discount_fixed',
                'free_service',
                'free_delivery',
                'tier_upgrade'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE membership_rewards
            MODIFY reward_type ENUM(
                'discount_percentage',
                'tier_upgrade'
            ) NOT NULL
        ");
    }
};
