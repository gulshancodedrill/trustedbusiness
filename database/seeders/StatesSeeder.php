<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StatesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    private const DUMMY_STATES = [
        'US' => [
            ['iso2' => 'CA', 'name' => 'California'],
            ['iso2' => 'NY', 'name' => 'New York'],
        ],
        'CA' => [
            ['iso2' => 'ON', 'name' => 'Ontario'],
            ['iso2' => 'BC', 'name' => 'British Columbia'],
        ],
        'IN' => [
            ['iso2' => 'MH', 'name' => 'Maharashtra'],
            ['iso2' => 'DL', 'name' => 'Delhi'],
        ],
        'AE' => [
            ['iso2' => 'DU', 'name' => 'Dubai'],
            ['iso2' => 'SH', 'name' => 'Sharjah'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Always seed deterministic dummy data (no external API).
        $this->seedDummyStates();
        return;

        $countries = DB::table('countries')
            ->select('id', 'iso2')
            ->whereNotNull('iso2')
            ->get();

        $countryFilter = collect(explode(',', (string) env('CSC_COUNTRY_CODES', '')))
            ->map(fn (string $code) => strtoupper(trim($code)))
            ->filter()
            ->values();
        if ($countryFilter->isNotEmpty()) {
            $countries = $countries->filter(
                fn ($country) => $countryFilter->contains(strtoupper((string) $country->iso2))
            )->values();
        }

        // Skip API calls for countries that already have states (saves CSC/RapidAPI quota).
        $countryIdsWithStates = array_flip(
            DB::table('states')->distinct()->pluck('country_id')->all()
        );

        $rows = [];
        $cscQuotaExhausted = false;
        foreach ($countries as $country) {
            if (isset($countryIdsWithStates[$country->id])) {
                continue;
            }

            $iso2 = strtoupper((string) $country->iso2);
            $statesPayload = null;

            if ($cscKey !== '' && ! $cscQuotaExhausted) {
                $response = Http::timeout(60)
                    ->retry(3, 500, null, false)
                    ->withHeaders(['X-CSCAPI-KEY' => $cscKey])
                    ->get(self::API_BASE.'/countries/'.$iso2.'/states');

                if ($response->successful()) {
                    $statesPayload = $response->json();
                } elseif ($response->status() === 429) {
                    $cscQuotaExhausted = true;
                    if ($this->command) {
                        $this->command->warn('CSC API daily limit reached while fetching states. Falling back to RapidAPI when configured.');
                    }
                }
            }

            if ($statesPayload === null && $rapidKey !== '') {
                $statesPayload = $this->fetchStatesFromRapidApi($rapidKey, strtolower($iso2));
            }

            if (! is_array($statesPayload)) {
                continue;
            }

            foreach ($statesPayload as $state) {
                $stateCode = strtoupper((string) ($state['iso2'] ?? $state['state_code'] ?? $state['isoCode'] ?? ''));
                $fallbackExternalId = sprintf('%u', crc32('state:'.$country->iso2.':'.$stateCode.':'.($state['name'] ?? '')));
                $rows[] = [
                    'external_id' => (int) ($state['id'] ?? $fallbackExternalId),
                    'country_id' => $country->id,
                    'name' => $state['name'] ?? 'Unknown State',
                    'iso2' => $stateCode ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('states')->upsert(
                $chunk,
                ['external_id'],
                ['country_id', 'name', 'iso2', 'updated_at']
            );
        }

        if ($rows === [] && DB::table('states')->count() === 0) {
            // If the CSC API failed and produced no rows, make sure we still have dummy data.
            $this->seedDummyStates();
        }
    }

    private function seedDummyStates(): void
    {
        $countries = DB::table('countries')
            ->select('id', 'iso2')
            ->whereNotNull('iso2')
            ->get();

        if ($countries->isEmpty()) {
            // CountriesSeeder should run before StatesSeeder, but keep this safe.
            return;
        }

        $countriesByIso2 = $countries->keyBy(fn ($c) => strtoupper((string) $c->iso2));

        $rows = [];
        foreach (self::DUMMY_STATES as $countryIso2 => $states) {
            $countryIso2 = strtoupper($countryIso2);

            if (! $countriesByIso2->has($countryIso2)) {
                continue;
            }

            $countryId = $countriesByIso2[$countryIso2]->id;
            foreach ($states as $state) {
                $stateIso2 = strtoupper((string) $state['iso2']);
                $stateName = (string) $state['name'];
                $rows[] = [
                    'external_id' => (int) sprintf('%u', crc32('state:'.$countryIso2.':'.$stateIso2)),
                    'country_id' => $countryId,
                    'name' => $stateName,
                    'iso2' => $stateIso2 ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($rows === []) {
            if ($this->command) {
                $this->command->warn('No dummy states matched available countries. No state records seeded.');
            }
            return;
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('states')->upsert(
                $chunk,
                ['external_id'],
                ['country_id', 'name', 'iso2', 'updated_at']
            );
        }

        if ($this->command) {
            $this->command->info('Seeded dummy states.');
        }
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    private function fetchStatesFromRapidApi(string $rapidApiKey, string $countryCodeLower): ?array
    {
        $response = Http::timeout(60)
            ->retry(2, 500, null, false)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'x-rapidapi-host' => self::RAPIDAPI_HOST,
                'x-rapidapi-key' => $rapidApiKey,
            ])
            ->get(self::RAPIDAPI_BASE.'/states-by-countrycode', [
                'countrycode' => $countryCodeLower,
            ]);

        if (! $response->successful()) {
            return null;
        }

        $json = $response->json();
        if (! is_array($json)) {
            return null;
        }

        return array_map(function (array $row): array {
            return [
                'name' => $row['name'] ?? '',
                'iso2' => $row['isoCode'] ?? $row['iso2'] ?? '',
            ];
        }, $json);
    }
}
