<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProdutosAddEhIngrediente extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produtos', [
            'eh_ingrediente' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=É ingrediente (controla estoque), 0=Produto final (não controla estoque)',
                'after'      => 'controla_estoque',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produtos', 'eh_ingrediente');
    }
}
