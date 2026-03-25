<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StatesSeeder extends Seeder
{
    private const API_BASE = 'https://api.countrystatecity.in/v1';

    private const RAPIDAPI_BASE = 'https://country-state-city-search-rest-api.p.rapidapi.com';

    private const RAPIDAPI_HOST = 'country-state-city-search-rest-api.p.rapidapi.com';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cscKey = (string) env('CSC_API_KEY', '');
        $rapidKey = (string) (env('RAPIDAPI_KEY') ?: env('RapidAPI_Key', ''));
        if ($cscKey === '' && $rapidKey === '') {
            throw new RuntimeException('Set CSC_API_KEY and/or RAPIDAPI_KEY (or RapidAPI_Key) in .env.');
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
