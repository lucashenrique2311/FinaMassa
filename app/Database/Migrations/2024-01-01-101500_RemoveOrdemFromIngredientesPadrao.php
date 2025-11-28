<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveOrdemFromIngredientesPadrao extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('ingredientes_padrao', 'ordem');
    }

    public function down()
    {
        $fields = [
            'ordem' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'ativo',
                'comment'    => 'Ordem de exibição',
            ],
        ];
        $this->forge->addColumn('ingredientes_padrao', $fields);
    }
}

