<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            [
                'industry' => 'Electronics Repair',
                'name' => 'Phone Repair',
                'description' => 'Screen repair, battery replacement, and diagnostics.',
            ],
            [
                'industry' => 'Electronics Repair',
                'name' => 'Laptop Repair',
                'description' => 'Hardware fixes, keyboard replacement, and performance tune-ups.',
            ],
            [
                'industry' => 'Healthcare',
                'name' => 'Dental Care',
                'description' => 'Cleaning, checkups, and dental treatments.',
            ],
            [
                'industry' => 'Landscaping',
                'name' => 'Lawn Maintenance',
                'description' => 'Mowing, trimming, and seasonal yard care.',
            ],
        ];

        foreach ($categories as $category) {
            $industryId = DB::table('industries')
                ->where('name', $category['industry'])
                ->value('id');

            if (! $industryId) {
                continue;
            }

            $exists = DB::table('categories')
                ->where('industry_id', $industryId)
                ->where('name', $category['name'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('categories')->insert([
                'industry_id' => $industryId,
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
