<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $services = [
            // Electronics Repair -> Phone Repair
            ['industry' => 'Electronics Repair', 'category' => 'Phone Repair', 'name' => 'Screen Replacement', 'description' => 'Genuine and compatible screen replacements.'],
            ['industry' => 'Electronics Repair', 'category' => 'Phone Repair', 'name' => 'Battery Replacement', 'description' => 'Battery tests and replacements for better performance.'],

            // Electronics Repair -> Laptop Repair
            ['industry' => 'Electronics Repair', 'category' => 'Laptop Repair', 'name' => 'Keyboard Replacement', 'description' => 'Fix broken keys and replace damaged keyboards.'],
            ['industry' => 'Electronics Repair', 'category' => 'Laptop Repair', 'name' => 'SSD Upgrade', 'description' => 'Speed upgrades with SSD installation.'],

            // Healthcare -> Dental Care
            ['industry' => 'Healthcare', 'category' => 'Dental Care', 'name' => 'Teeth Cleaning', 'description' => 'Professional cleaning and preventive care.'],
            ['industry' => 'Healthcare', 'category' => 'Dental Care', 'name' => 'Dental Checkup', 'description' => 'Routine evaluation and treatment planning.'],

            // Landscaping -> Lawn Maintenance
            ['industry' => 'Landscaping', 'category' => 'Lawn Maintenance', 'name' => 'Lawn Mowing', 'description' => 'Regular mowing and edging for neat lawns.'],
            ['industry' => 'Landscaping', 'category' => 'Lawn Maintenance', 'name' => 'Seasonal Cleanup', 'description' => 'Cleanup and preparation for each season.'],
        ];

        foreach ($services as $service) {
            $industryId = DB::table('industries')->where('name', $service['industry'])->value('id');
            if (! $industryId) {
                continue;
            }

            $categoryId = DB::table('categories')
                ->where('industry_id', $industryId)
                ->where('name', $service['category'])
                ->value('id');

            if (! $categoryId) {
                continue;
            }

            $exists = DB::table('services')
                ->where('category_id', $categoryId)
                ->where('name', $service['name'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('services')->insert([
                'category_id' => $categoryId,
                'name' => $service['name'],
                'description' => $service['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
