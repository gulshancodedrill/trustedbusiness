<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\IndustriesSeeder;
use Database\Seeders\CategoriesSeeder;
use Database\Seeders\ServicesSeeder;

class DummyIndustryCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            IndustriesSeeder::class,
            CategoriesSeeder::class,
            ServicesSeeder::class,
        ]);
    }
}
