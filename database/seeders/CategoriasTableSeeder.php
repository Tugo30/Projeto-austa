<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run()
    {
        DB::table('categorias')->insert([
            ['nome' => 'Suporte Técnico'],
            ['nome' => 'Financeiro'],
            ['nome' => 'RH'],
            ['nome' => 'TI'],
        ]);
    }
}
