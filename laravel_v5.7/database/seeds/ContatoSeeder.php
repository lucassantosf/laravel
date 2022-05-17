<?php

use Illuminate\Database\Seeder;

class ContatoSeeder extends Seeder
{
    public function run()
    {
        DB::table('contatos')->insert([
			'nome'=>'contato 1',
			'email'=>'contato@gmail.com',
			'idade'=>99,
			'telefone'=>'(15)9 8877-3456',
		]);
    }
}
