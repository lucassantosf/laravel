<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([ 
            'name' => 'admin',
            'password' => '$2y$10$z7TzphUwq5d3QJcMYpG9V.dj51lrHEIoGCihxspBjjAL7XOBMsxC2',
            'email' => 'master@cryptos.eti.br'
        ]); 
 
        DB::table('model_has_roles')->insert([ 
            'role_id' => 1,
            'model_type' => 'App\Models\User',
            'model_id' => 1,
        ]); 
    }
}
