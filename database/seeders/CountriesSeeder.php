<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountriesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    private const DUMMY_COUNTRIES = [
        [
            'iso2' => 'US',
            'name' => 'United States',
            'iso3' => 'USA',
            'phone_code' => '1',
        ],
        [
            'iso2' => 'CA',
            'name' => 'Canada',
            'iso3' => 'CAN',
            'phone_code' => '1',
        ],
        [
            'iso2' => 'IN',
            'name' => 'India',
            'iso3' => 'IND',
            'phone_code' => '91',
        ],
        [
            'iso2' => 'AE',
            'name' => 'United Arab Emirates',
            'iso3' => 'ARE',
            'phone_code' => '971',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Always seed deterministic dummy data.
        $this->seedDummyCountries();
    }

    private function fetchCountries(): array
    {
        $apiKey = (string) env('CSC_API_KEY');
        if ($apiKey === '') {
            return [];
        }

        $response = Http::timeout(60)
            ->retry(3, 500, null, false)
            ->withHeaders(['X-CSCAPI-KEY' => $apiKey])
            ->get(self::API_BASE.'/countries');

        if (! $response->successful()) {
            if ($response->status() === 429 && $this->command) {
                $this->command->warn('CSC API daily limit reached while fetching countries.');
            }
            return [];
        }

        return $response->json();
    }

    private function seedDummyCountries(): void
    {
        $countries = self::DUMMY_COUNTRIES;

        $rows = array_map(function (array $country): array {
            $iso2 = strtoupper((string) $country['iso2']);

            return [
                'external_id' => (int) sprintf('%u', crc32('country:'.$iso2)),
                'name' => (string) $country['name'],
                'iso2' => $iso2 ?: null,
                'iso3' => $country['iso3'] ? strtoupper((string) $country['iso3']) : null,
                'phone_code' => $country['phone_code'] ? (string) $country['phone_code'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $countries);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('countries')->upsert(
                $chunk,
                ['external_id'],
                ['name', 'iso2', 'iso3', 'phone_code', 'updated_at']
            );
        }

        if ($this->command) {
            $this->command->info('Seeded dummy countries.');
        }
    }
}
