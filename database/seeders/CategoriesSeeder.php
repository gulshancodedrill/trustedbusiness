<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('categories')->exists() && DB::table('categories')->count() > 0) {
            return;
        }

        $industries = DB::table('industries')->get(['id', 'name']);
        if ($industries->isEmpty()) {
            // IndustriesSeeder should run before this.
            if ($this->command) {
                $this->command->warn('No industries found. Skipping dummy categories seeding.');
            }
            return;
        }

        $industryIdByName = $industries->pluck('id', 'name')->mapWithKeys(
            fn ($id, $name) => [strtolower((string) $name) => $id]
        );

        $now = now();

        $categories = [
            // Technology
            ['industry' => 'Technology', 'name' => 'Software Development', 'description' => 'Custom software and app development.'],
            ['industry' => 'Technology', 'name' => 'IT Services', 'description' => 'Managed IT support, networks, and related services.'],

            // Food & Restaurants
            ['industry' => 'Food & Restaurants', 'name' => 'Restaurants', 'description' => 'Restaurants, dining, and related food service operations.'],
            ['industry' => 'Food & Restaurants', 'name' => 'Cafes', 'description' => 'Cafes and coffee shops.'],

            // Healthcare
            ['industry' => 'Healthcare', 'name' => 'Dental Care', 'description' => 'Dental clinics and services.'],
            ['industry' => 'Healthcare', 'name' => 'Physiotherapy', 'description' => 'Physiotherapy and rehabilitation services.'],

            // Home Services
            ['industry' => 'Home Services', 'name' => 'Plumbing', 'description' => 'Plumbing repair and installation services.'],
            ['industry' => 'Home Services', 'name' => 'Electrical', 'description' => 'Electrical repair, wiring, and installation services.'],

            // Education
            ['industry' => 'Education', 'name' => 'Tutoring', 'description' => 'Academic tutoring and coaching services.'],
            ['industry' => 'Education', 'name' => 'Language Schools', 'description' => 'Language training programs and classes.'],
        ];

        $rows = [];
        foreach ($categories as $cat) {
            $industryKey = strtolower((string) $cat['industry']);
            if (! isset($industryIdByName[$industryKey])) {
                continue;
            }

            $rows[] = [
                'industry_id' => $industryIdByName[$industryKey],
                'name' => (string) $cat['name'],
                'description' => $cat['description'] ? (string) $cat['description'] : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows === []) {
            if ($this->command) {
                $this->command->warn('No dummy categories could be created (missing industries?).');
            }
            return;
        }

        DB::table('categories')->insert($rows);

        if ($this->command) {
            $this->command->info('Seeded dummy categories.');
        }
    }
}

