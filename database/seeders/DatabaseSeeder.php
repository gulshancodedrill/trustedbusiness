<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            CountriesSeeder::class,
            StatesSeeder::class,
            CitiesSeeder::class,
            \Database\Seeders\IndustriesSeeder::class,
            \Database\Seeders\CategoriesSeeder::class,
            \Database\Seeders\ServicesSeeder::class,
            \Database\Seeders\BusinessSeeder::class,
        ]);

        $adminRoleId = DB::table('user_roles')
            ->where('slug', 'administrator')
            ->value('id');

        $email = 'test@example.com';

        if (! DB::table('users')->where('email', $email)->exists()) {
            // User seeded only once to keep `db:seed` idempotent.
            User::factory()->create([
                'name' => 'Test User',
                'email' => $email,
                'password' => Hash::make('Test@123'),
                'role_id' => $adminRoleId,
            ]);
        }
    }
}
