<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StatesSeeder extends Seeder
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

        $rows = [];
        foreach ($countries as $country) {
            $response = Http::timeout(60)
                ->retry(3, 500, null, false)
                ->withHeaders(['X-CSCAPI-KEY' => $apiKey])
                ->get(self::API_BASE.'/countries/'.strtoupper((string) $country->iso2).'/states');

            if (! $response->successful()) {
                if ($response->status() === 429 && $this->command) {
                    $this->command->warn('CSC API daily limit reached while fetching states. Seeded partial data.');
                    break;
                }
                continue;
            }

            foreach ($response->json() as $state) {
                $stateCode = strtoupper((string) ($state['iso2'] ?? $state['state_code'] ?? ''));
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
    }

}
