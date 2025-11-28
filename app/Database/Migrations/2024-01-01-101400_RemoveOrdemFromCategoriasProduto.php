<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveOrdemFromCategoriasProduto extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('categorias_produto', 'ordem');
    }

    public function down()
    {
        $fields = [
            'ordem' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'cor',
                'comment'    => 'Ordem de exibição',
            ],
        ];
        $this->forge->addColumn('categorias_produto', $fields);
    }
}

