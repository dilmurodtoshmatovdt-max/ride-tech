<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RolesSeeder::class]);
        $this->call([TestUserSeeder::class]);
        $this->call([PermissionSeeder::class]);
        $this->call([TripStatusSeeder::class]);
        $this->call(PermissionRoleSeeder::class);
    }
}
