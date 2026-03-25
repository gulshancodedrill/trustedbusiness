<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CitiesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    private const DUMMY_CITIES = [
        // Country ISO2 => State ISO2 => City Names
        'US' => [
            'CA' => ['Los Angeles', 'San Francisco'],
            'NY' => ['New York City', 'Buffalo'],
        ],
        'CA' => [
            'ON' => ['Toronto', 'Ottawa'],
            'BC' => ['Vancouver', 'Victoria'],
        ],
        'IN' => [
            'MH' => ['Mumbai', 'Pune'],
            'DL' => ['New Delhi', 'Chandni Chowk'],
        ],
        'AE' => [
            'DU' => ['Dubai', 'Deira'],
            'SH' => ['Sharjah', 'Al Nahda'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Always seed deterministic dummy data (no external API).
        $this->seedDummyCities();
        return;

        $states = DB::table('states')
            ->join('countries', 'countries.id', '=', 'states.country_id')
            ->select('states.id as state_id', 'states.iso2 as state_iso2', 'states.name as state_name', 'countries.iso2 as country_iso2')
            ->whereNotNull('countries.iso2')
            ->get();

        $countryFilter = collect(explode(',', (string) env('CSC_COUNTRY_CODES', '')))
            ->map(fn (string $code) => strtoupper(trim($code)))
            ->filter()
            ->values();
        if ($countryFilter->isNotEmpty()) {
            $states = $states->filter(
                fn ($state) => $countryFilter->contains(strtoupper((string) $state->country_iso2))
            )->values();
        }

        $rows = [];
        foreach ($states as $state) {
            $stateCode = strtoupper((string) $state->state_iso2);
            $countryCode = strtoupper((string) $state->country_iso2);
            if ($stateCode === '' || $countryCode === '') {
                continue;
            }

            $response = Http::timeout(60)
                ->retry(3, 500, null, false)
                ->withHeaders(['X-CSCAPI-KEY' => $apiKey])
                ->get(self::API_BASE."/countries/{$countryCode}/states/{$stateCode}/cities");

            if (! $response->successful()) {
                if ($response->status() === 429 && $this->command) {
                    $this->command->warn('CSC API daily limit reached while fetching cities. Seeded partial data.');
                    break;
                }
                continue;
            }

            foreach ($response->json() as $city) {
                $cityName = $city['name'] ?? 'Unknown City';
                $fallbackExternalId = sprintf('%u', crc32("city:{$countryCode}:{$stateCode}:{$cityName}"));
                $rows[] = [
                    'external_id' => (int) ($city['id'] ?? $fallbackExternalId),
                    'state_id' => $state->state_id,
                    'name' => $cityName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Keep memory in check for large imports.
            if (count($rows) >= 5000) {
                $this->flushRows($rows);
                $rows = [];
            }
        }

        $this->flushRows($rows);

        if ($rows === [] && DB::table('cities')->count() === 0) {
            // If the CSC API failed and produced no rows, make sure we still have dummy data.
            $this->seedDummyCities();
        }
    }

    private function seedDummyCities(): void
    {
        // Load states with their country ISO2 so we can map dummy cities to correct state_id.
        $states = DB::table('states')
            ->join('countries', 'countries.id', '=', 'states.country_id')
            ->select('states.id as state_id', 'states.iso2 as state_iso2', 'countries.iso2 as country_iso2')
            ->whereNotNull('states.iso2')
            ->whereNotNull('countries.iso2')
            ->get();

        if ($states->isEmpty()) {
            // CountriesSeeder/StatesSeeder should run before this, but keep this safe.
            return;
        }

        $stateIdByKey = [];
        foreach ($states as $state) {
            $key = strtoupper((string) $state->country_iso2.'-'.$state->state_iso2);
            $stateIdByKey[$key] = (int) $state->state_id;
        }

        $rows = [];
        foreach (self::DUMMY_CITIES as $countryIso2 => $byState) {
            $countryIso2 = strtoupper($countryIso2);

            foreach ($byState as $stateIso2 => $cities) {
                $stateIso2 = strtoupper((string) $stateIso2);
                $key = $countryIso2.'-'.$stateIso2;
                if (! isset($stateIdByKey[$key])) {
                    continue;
                }

                $stateId = $stateIdByKey[$key];
                foreach ($cities as $cityName) {
                    $cityName = (string) $cityName;
                    $rows[] = [
                        'external_id' => (int) sprintf('%u', crc32("city:{$countryIso2}:{$stateIso2}:{$cityName}")),
                        'state_id' => $stateId,
                        'name' => $cityName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if ($rows === []) {
            if ($this->command) {
                $this->command->warn('No dummy cities could be seeded (missing states).');
            }
            return;
        }

        foreach (array_chunk($rows, 2000) as $chunk) {
            DB::table('cities')->upsert(
                $chunk,
                ['external_id'],
                ['state_id', 'name', 'updated_at']
            );
        }

        if ($this->command) {
            $this->command->info('Seeded dummy cities.');
        }
    }

    private function flushRows(array $rows): void
    {
        if ($rows === []) {
            return;
        }

        foreach (array_chunk($rows, 2000) as $chunk) {
            DB::table('cities')->upsert(
                $chunk,
                ['external_id'],
                ['state_id', 'name', 'updated_at']
            );
        }
    }
}
