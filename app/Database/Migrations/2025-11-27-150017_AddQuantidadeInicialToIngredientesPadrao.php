<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuantidadeInicialToIngredientesPadrao extends Migration
{
    public function up()
    {
        $fields = [
            'quantidade_inicial' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'default'    => 0.000,
                'null'       => true,
                'after'      => 'custo_padrao',
                'comment'    => 'Quantidade inicial em estoque (apenas para referência)',
            ],
        ];
        
        $this->forge->addColumn('ingredientes_padrao', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ingredientes_padrao', 'quantidade_inicial');
    }
}
