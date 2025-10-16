<?php

namespace Database\Seeders;

use Log;
use Hash;
use App\Models\User;
use App\Constants\Roles;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'admin@mail.com',
            'phone_number' => 992927777777,
            'password' => Hash::make('1234' . env('PASSWORD_SALT')),
        ];

        try {
            User::create($user);
            RoleUser::create(['role_id' => Roles::DRIVER, 'user_id' => $user['id'], 'user_type' => User::class]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
