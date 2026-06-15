<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'master@gmail.com'],
            [
                'name' => 'admin',
                'password' => '$2y$10$z7TzphUwq5d3QJcMYpG9V.dj51lrHEIoGCihxspBjjAL7XOBMsxC2',
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@gmail.com'],
            [
                'name' => 'client',
                'password' => Hash::make('123456'),
            ]
        );
    }
}
