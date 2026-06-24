<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MembershipReward;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     RolePermissionSeeder::class,
        //     IncomeCategorySeeder::class,
        // ]);

        MembershipReward::insert([
            [
                'name' => '10%',
                'required_stamp' => 3,
                'reward_type' => 'discount_percentage',
                'reward_value' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => '10K',
                'required_stamp' => 5,
                'reward_type' => 'discount_fixed',
                'reward_value' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => '15%',
                'required_stamp' => 7,
                'reward_type' => 'discount_percentage',
                'reward_value' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => '20K',
                'required_stamp' => 10,
                'reward_type' => 'discount_fixed',
                'reward_value' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => '20%',
                'required_stamp' => 12,
                'reward_type' => 'discount_percentage',
                'reward_value' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'FAM',
                'required_stamp' => 15,
                'reward_type' => 'tier_upgrade',
                'reward_value' => 'family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
