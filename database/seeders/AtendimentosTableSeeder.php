<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AtendimentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('atendimentos')->insert([
            [
                'titulo' => 'Problema no login',
                'descricao' => 'Usuário não consegue acessar o sistema.',
                'prioridade' => 'Alta',
                'status' => 'Aberto',
                'categoria_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Dúvida sobre salário',
                'descricao' => 'Funcionário com dúvidas sobre pagamento.',
                'prioridade' => 'Média',
                'status' => 'Em andamento',
                'categoria_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Erro no sistema',
                'descricao' => 'Erro 500 ao cadastrar novo usuário.',
                'prioridade' => 'Alta',
                'status' => 'Finalizado',
                'categoria_id' => 4,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
