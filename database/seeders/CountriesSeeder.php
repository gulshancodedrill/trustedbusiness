<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CountriesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = $this->fetchCountries();
        if ($countries === []) {
            if ($this->command) {
                $this->command->warn('No countries fetched from CSC API. Keeping existing countries data as-is.');
            }
            return;
        }

        $rows = collect($countries)
            ->map(function (array $country): array {
                $iso2 = strtoupper((string) ($country['iso2'] ?? ''));
                $fallbackExternalId = sprintf('%u', crc32('country:'.$iso2));

                return [
                    'external_id' => (int) ($country['id'] ?? $fallbackExternalId),
                    'name' => $country['name'] ?? $iso2,
                    'iso2' => $iso2 ?: null,
                    'iso3' => strtoupper((string) ($country['iso3'] ?? '')) ?: null,
                    'phone_code' => (string) ($country['phonecode'] ?? '') ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->filter(fn (array $country) => ! empty($country['iso2']))
            ->values()
            ->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('countries')->upsert(
                $chunk,
                ['external_id'],
                ['name', 'iso2', 'iso3', 'phone_code', 'updated_at']
            );
        }
    }

    private function fetchCountries(): array
    {
        $apiKey = (string) env('CSC_API_KEY');
        if ($apiKey === '') {
            throw new RuntimeException('CSC_API_KEY is not set in environment.');
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
}
