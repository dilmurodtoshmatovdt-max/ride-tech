<?php

namespace Database\Seeders;

use App\Constants\TripStatuses;
use App\Models\TripStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TripStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $items = [
            ['id' => TripStatuses::PENDING, 'name' => 'Поиск водителя'],
            ['id' => TripStatuses::ASSIGNED, 'name' => 'Водитель назначен'],
            ['id' => TripStatuses::ARRIVED, 'name' => 'Водитель прибыл'],
            ['id' => TripStatuses::STARTED, 'name' => 'Поездка началась'],
            ['id' => TripStatuses::COMPLETED, 'name' => 'Поездка завершена'],
            ['id' => TripStatuses::CANCELED, 'name' => 'Отменено'],
            ['id' => TripStatuses::REJECTED, 'name' => 'Отклонено']
        ];

        foreach ($items as $item) {
            try {
                TripStatus::updateOrCreate(['id' => $item['id']], $item);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
