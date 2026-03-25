<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Demo data for United Kingdom so country/state/city dropdowns can be tested
 * before full API-backed seeders are wired.
 */
class UkDemoLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $countryId = DB::table('countries')->where('iso2', 'GB')->value('id');
        if (! $countryId) {
            $countryId = DB::table('countries')->insertGetId([
                'external_id' => (int) sprintf('%u', crc32('demo:country:GB')),
                'name' => 'United Kingdom',
                'iso2' => 'GB',
                'iso3' => 'GBR',
                'phone_code' => '44',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $regions = [
            ['name' => 'England', 'code' => 'ENG'],
            ['name' => 'Scotland', 'code' => 'SCT'],
            ['name' => 'Wales', 'code' => 'WLS'],
        ];

        foreach ($regions as $region) {
            $exists = DB::table('states')
                ->where('country_id', $countryId)
                ->where('iso2', $region['code'])
                ->exists();

            if (! $exists) {
                DB::table('states')->insert([
                    'external_id' => (int) sprintf('%u', crc32('demo:state:GB:'.$region['code'])),
                    'country_id' => $countryId,
                    'name' => $region['name'],
                    'iso2' => $region['code'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $englandId = (int) DB::table('states')
            ->where('country_id', $countryId)
            ->where('iso2', 'ENG')
            ->value('id');

        $scotlandId = (int) DB::table('states')
            ->where('country_id', $countryId)
            ->where('iso2', 'SCT')
            ->value('id');

        $demoCities = [
            $englandId => ['London', 'Manchester', 'Birmingham', 'Leeds', 'Bristol', 'Liverpool'],
            $scotlandId => ['Edinburgh', 'Glasgow', 'Aberdeen'],
        ];

        foreach ($demoCities as $stateId => $names) {
            if ($stateId === 0) {
                continue;
            }
            foreach ($names as $name) {
                $exists = DB::table('cities')
                    ->where('state_id', $stateId)
                    ->where('name', $name)
                    ->exists();

                if ($exists) {
                    continue;
                }

                DB::table('cities')->insert([
                    'external_id' => (int) sprintf('%u', crc32('demo:city:GB:'.$stateId.':'.$name)),
                    'state_id' => $stateId,
                    'name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
