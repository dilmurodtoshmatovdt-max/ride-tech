<?php

namespace Database\Seeders;

use Log;
use Hash;
use App\Models\User;
use App\Constants\Roles;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driver = [
            'id' => 1,
            'name' => 'Test Driver',
            'email' => 'driver@mail.com',
            'phone_number' => 992927777777,
            'password' => Hash::make('1234' . env('PASSWORD_SALT')),
        ];
        $passenger = [
            'id' => 2,
            'name' => 'Test Passenger',
            'email' => 'passenger@mail.com',
            'phone_number' => 992928888888,
            'password' => Hash::make('1234' . env('PASSWORD_SALT')),
        ];

        try {
            User::create($driver);
            User::create($passenger);
            RoleUser::create(['role_id' => Roles::DRIVER, 'user_id' => $driver['id'], 'user_type' => User::class]);
            RoleUser::create(['role_id' => Roles::PASSANGER, 'user_id' => $passenger['id'], 'user_type' => User::class]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
