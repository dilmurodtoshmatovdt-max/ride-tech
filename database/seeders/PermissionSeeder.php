<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Log;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => Permissions::TripList, 'display_name' => 'Поездки: Просмотр', 'description' => ''],
            ['name' => Permissions::TripAdd, 'display_name' => 'Поездки: Добавление', 'description' => ''],
            ['name' => Permissions::TripEdit, 'display_name' => 'Поездки: Изменение', 'description' => ''],
            ['name' => Permissions::TripDelete, 'display_name' => 'Поездки: Удаление', 'description' => ''],
            ['name' => Permissions::TripCancel, 'display_name' => 'Поездки: отменить', 'description' => ''],
            ['name' => Permissions::TripReject, 'display_name' => 'Поездки: отклонить', 'description' => ''],
            ['name' => Permissions::TripAssign, 'display_name' => 'Поездки: назначение водителя', 'description' => ''],
            ['name' => Permissions::TripArrive, 'display_name' => 'Поездки: прибытие', 'description' => ''],
            ['name' => Permissions::TripStart, 'display_name' => 'Поездки: начать', 'description' => ''],
            ['name' => Permissions::TripFinish, 'display_name' => 'Поездки: завершить', 'description' => ''],

            ['name' => Permissions::CarList, 'display_name' => 'Машины: Просмотр', 'description' => ''],
            ['name' => Permissions::CarAdd, 'display_name' => 'Машины: Добавление', 'description' => ''],
            ['name' => Permissions::CarEdit, 'display_name' => 'Машины: Изменение', 'description' => ''],
            ['name' => Permissions::CarDelete, 'display_name' => 'Машины: Удаление', 'description' => ''],

            ['name' => Permissions::ReviewAdd, 'display_name' => 'Отзывы: Добавление', 'description' => ''],
            ['name' => Permissions::ReviewList, 'display_name' => 'Отзывы: Список', 'description' => ''],


        ];
        foreach ($items as $item) {
            try {
                Permission::updateOrCreate(['name' => $item['name']], $item);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
