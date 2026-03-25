<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('services')->exists() && DB::table('services')->count() > 0) {
            return;
        }

        $categories = DB::table('categories')->get(['id', 'name']);
        if ($categories->isEmpty()) {
            if ($this->command) {
                $this->command->warn('No categories found. Skipping dummy services seeding.');
            }
            return;
        }

        $categoryIdByName = $categories->pluck('id', 'name')->mapWithKeys(
            fn ($id, $name) => [strtolower((string) $name) => $id]
        );

        $now = now();

        // Category Name => List of services
        $servicesByCategory = [
            'Software Development' => [
                ['name' => 'Web App Development', 'description' => 'Build and maintain web applications.'],
                ['name' => 'Mobile App Development', 'description' => 'Design and develop mobile apps for iOS/Android.'],
                ['name' => 'UI/UX Design', 'description' => 'User interface and user experience design services.'],
            ],
            'IT Services' => [
                ['name' => 'Managed IT Support', 'description' => 'Remote and on-site IT support for businesses.'],
                ['name' => 'Network Setup', 'description' => 'Install and configure business networks and Wi-Fi.'],
                ['name' => 'Cloud Migration', 'description' => 'Move workloads to cloud infrastructure.'],
            ],
            'Restaurants' => [
                ['name' => 'Restaurant Catering', 'description' => 'Catering services for events and parties.'],
                ['name' => 'Private Dining', 'description' => 'Book private dining spaces for groups.'],
                ['name' => 'Takeaway & Delivery', 'description' => 'Food delivery and takeaway services.'],
            ],
            'Cafes' => [
                ['name' => 'Coffee & Pastries', 'description' => 'Coffee, tea, and baked goods.'],
                ['name' => 'Event Bookings', 'description' => 'Small events and meetings at the cafe.'],
                ['name' => 'Custom Cakes', 'description' => 'Cakes and desserts for celebrations.'],
            ],
            'Dental Care' => [
                ['name' => 'Teeth Cleaning', 'description' => 'Professional dental cleaning and checkups.'],
                ['name' => 'Braces & Aligners', 'description' => 'Orthodontic consultations and treatments.'],
                ['name' => 'Root Canal Services', 'description' => 'Endodontic treatment services.'],
            ],
            'Physiotherapy' => [
                ['name' => 'Rehabilitation Therapy', 'description' => 'Recover mobility and strength through therapy.'],
                ['name' => 'Pain Management', 'description' => 'Physiotherapy for chronic pain and injuries.'],
                ['name' => 'Sports Recovery', 'description' => 'Rehab services for athletes and active individuals.'],
            ],
            'Plumbing' => [
                ['name' => 'Leak Fixing', 'description' => 'Repair leaks and plumbing issues.'],
                ['name' => 'Pipe Installation', 'description' => 'Install and replace pipes for residential/commercial needs.'],
                ['name' => 'Water Heater Services', 'description' => 'Install and service water heaters.'],
            ],
            'Electrical' => [
                ['name' => 'Wiring Installation', 'description' => 'New wiring and electrical installation services.'],
                ['name' => 'Lighting Repair', 'description' => 'Fix and upgrade indoor/outdoor lighting.'],
                ['name' => 'Circuit Breaker Repair', 'description' => 'Repair and replace electrical breakers.'],
            ],
            'Tutoring' => [
                ['name' => 'Math Tutoring', 'description' => 'One-on-one tutoring for mathematics.'],
                ['name' => 'Science Tutoring', 'description' => 'Help with science subjects and experiments.'],
                ['name' => 'Exam Preparation', 'description' => 'Exam coaching and preparation sessions.'],
            ],
            'Language Schools' => [
                ['name' => 'English Classes', 'description' => 'Improve English speaking, listening, reading, and writing.'],
                ['name' => 'IELTS Coaching', 'description' => 'IELTS preparation for test takers.'],
                ['name' => 'Spoken Language Courses', 'description' => 'Conversational language learning programs.'],
            ],
        ];

        $rows = [];
        foreach ($servicesByCategory as $categoryName => $serviceList) {
            $key = strtolower((string) $categoryName);
            if (! isset($categoryIdByName[$key])) {
                continue;
            }

            $categoryId = (int) $categoryIdByName[$key];
            foreach ($serviceList as $service) {
                $rows[] = [
                    'category_id' => $categoryId,
                    'name' => (string) $service['name'],
                    'description' => isset($service['description']) ? (string) $service['description'] : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($rows === []) {
            if ($this->command) {
                $this->command->warn('No dummy services were seeded (missing categories?).');
            }
            return;
        }

        DB::table('services')->insert($rows);

        if ($this->command) {
            $this->command->info('Seeded dummy services.');
        }
    }
}

