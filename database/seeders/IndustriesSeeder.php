<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $industries = [
            [
                'name' => 'Electronics Repair',
                'description' => 'Repairs for phones, laptops, and other electronics.',
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical and health services for individuals and families.',
            ],
            [
                'name' => 'Landscaping',
                'description' => 'Garden and outdoor maintenance services.',
            ],
        ];

        foreach ($industries as $industry) {
            $exists = DB::table('industries')
                ->where('name', $industry['name'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('industries')->insert([
                'name' => $industry['name'],
                'description' => $industry['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
