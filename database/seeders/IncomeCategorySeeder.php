<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Order Income',
            'Manual Income',
            'Bonus',
            'Additional Service',
            'Membership',
            'Other Income',
        ];

        foreach ($categories as $category) {

            IncomeCategory::firstOrCreate([
                'name' => $category,
            ]);

        }
    }
}
