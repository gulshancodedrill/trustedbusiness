<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = DB::table('cities')
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->join('countries', 'countries.id', '=', 'states.country_id')
            ->select([
                'countries.name as country',
                'states.name as state',
                'cities.name as city',
            ])
            ->get();

        if ($locations->isEmpty()) {
            if ($this->command) {
                $this->command->warn('No locations found. Seed countries/states/cities first.');
            }
            return;
        }

        $categories = DB::table('categories')->get(['id', 'name']);
        if ($categories->isEmpty()) {
            if ($this->command) {
                $this->command->warn('No categories found. Seed industries/categories/services first.');
            }
            return;
        }

        // Group services by category_id so each business gets a valid service for its category.
        $servicesByCategory = DB::table('services')
            ->select(['id', 'category_id', 'name'])
            ->get()
            ->groupBy('category_id');

        if ($servicesByCategory->isEmpty()) {
            return;
        }

        $now = now();

        $businessEmails = [];
        for ($i = 1; $i <= 24; $i++) {
            $businessEmails[] = 'bizseed'.$i.'@example.com';
        }

        $idx = 0;
        foreach ($categories as $category) {
            /** @var \Illuminate\Support\Collection<array-key, object> $services */
            $services = $servicesByCategory->get($category->id, collect());
            if ($services->isEmpty()) {
                continue;
            }

            // Create up to 2 businesses per category (depending on available emails).
            for ($j = 0; $j < 2; $j++) {
                if ($idx >= count($businessEmails)) {
                    break 2;
                }

                $email = $businessEmails[$idx];
                $idx++;

                $service = $services->shuffle()->first();
                $location = $locations->shuffle()->first();

                $firstName = ['Ava', 'Noah', 'Mia', 'Liam', 'Sophia', 'Ethan', 'Isabella', 'Lucas'][$idx % 8];
                $lastName = ['Smith', 'Johnson', 'Brown', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White'][$idx % 8];

                $pincode = (string) (100000 + ($idx * 7) % 899999);
                $contact = (string) (9000000000 + ($idx * 13) % 99999999);

                // Avoid duplicates across multiple runs (no unique constraint on business_email).
                if (DB::table('business')->where('business_email', $email)->exists()) {
                    continue;
                }

                Business::create([
                    'owner_first_name' => $firstName,
                    'owner_last_name' => $lastName,
                    'contact_number' => $contact,
                    'business_name' => $service->name.' - '.$location->city,
                    'business_email' => $email,
                    'business_contact_number' => $contact,
                    'website' => 'https://example.com/'.$idx,
                    'business_description' => 'Dummy business record for demo/testing purposes.',
                    'country' => $location->country,
                    'state' => $location->state,
                    'city' => $location->city,
                    'pincode' => $pincode,
                    'address_line_1' => '123 Demo Street, '.$location->city,
                    'industry_id' => (int) DB::table('categories')->where('id', $category->id)->value('industry_id'),
                    'category_id' => (int) $category->id,
                    'service_id' => (int) $service->id,
                    'tags' => ['trusted', 'verified'],
                    'hear_from' => 'Google',
                    'sunday_timing' => '09:00 - 17:00',
                    'monday_timing' => '09:00 - 17:00',
                    'tuesday_timing' => '09:00 - 17:00',
                    'wednesday_timing' => '09:00 - 17:00',
                    'thursday_timing' => '09:00 - 17:00',
                    'friday_timing' => '09:00 - 17:00',
                    'saturday_timing' => '09:00 - 17:00',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        if ($this->command) {
            $this->command->info('Seeded dummy businesses.');
        }
    }
}

