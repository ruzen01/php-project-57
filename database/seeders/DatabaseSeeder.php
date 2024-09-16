<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'], // Условие поиска
            [                                // Данные для создания
               'name' => 'Test User',
               'email' => 'test@example.com',
               'password' => bcrypt('password'), // Убедитесь, что используете шифрование пароля
               'email_verified_at' => now(),
               'remember_token' => \Str::random(10),
            ]
        );
    }
}
