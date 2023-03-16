<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'admin',
            'password'=>'$2y$10$z7TzphUwq5d3QJcMYpG9V.dj51lrHEIoGCihxspBjjAL7XOBMsxC2',
            'email'=>'master@gmail.com',
        ]);
    }
}
