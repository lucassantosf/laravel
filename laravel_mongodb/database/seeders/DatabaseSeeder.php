<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            // RolesPermissionSeeder::class,
            // PostSeeder::class,
        ]);

        \Artisan::call('passport:install');
    }
}
