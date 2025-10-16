<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Constants\Roles;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $items = [
            ['id' => Roles::ADMIN, 'name' => 'admin', 'display_name' => 'Администратор'],
            ['id' => Roles::DRIVER, 'name' => 'driver', 'display_name' => 'Водитель'],
            ['id' => Roles::PASSANGER, 'name' => 'passanger', 'display_name' => 'Пассажир'],
        ];

        foreach ($items as $item) {
            try {
                Role::updateOrCreate(['id' => $item['id']], $item);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
