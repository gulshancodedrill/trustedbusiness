<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['slug' => 'admin', 'name' => 'Administrator'],
            ['slug' => 'customer', 'name' => 'Customer'],
        ];

        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
