<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('industries')->exists()) {
            return;
        }

        $now = now();

        $rows = [
            [
                'name' => 'Technology',
                'description' => 'Software and IT related services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Food & Restaurants',
                'description' => 'Food businesses and dining services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical and wellness services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Home Services',
                'description' => 'Repairs, installations, and home improvement services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Education',
                'description' => 'Learning and training providers.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('industries')->insert($rows);

        if ($this->command) {
            $this->command->info('Seeded dummy industries.');
        }
    }
}

