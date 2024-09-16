<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $statuses = [
           ['name' => 'Новый'],
           ['name' => 'В работе'],
           ['name' => 'На тестировании'],
           ['name' => 'Завершен']
        ];

        foreach ($statuses as $status) {
            DB::table('task_statuses')->updateOrInsert(
                ['name' => $status['name']], // Условие для обновления
                $status                       // Данные для вставки или обновления
            );
        }
    }
}
