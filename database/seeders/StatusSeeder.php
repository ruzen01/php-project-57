<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatusSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $now = Carbon::now();  // Текущее время

        $statuses = [
           ['name' => 'Новый', 'created_at' => $now],
           ['name' => 'В работе', 'created_at' => $now],
           ['name' => 'На тестировании', 'created_at' => $now],
           ['name' => 'Завершен', 'created_at' => $now]
        ];

        foreach ($statuses as $status) {
            DB::table('task_statuses')->updateOrInsert(
                ['name' => $status['name']], // Условие для обновления
                $status                       // Данные для вставки или обновления
            );
        }
    }
}