<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CitiesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKey = (string) env('CSC_API_KEY');
        if ($apiKey === '') {
            throw new RuntimeException('CSC_API_KEY is not set in environment.');
        }

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
