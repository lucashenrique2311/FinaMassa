<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMeioAMeioToItensPedido extends Migration
{
    public function up()
    {
        $fields = [
            'id_produto_meio_a_meio' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_produto',
                'comment'    => 'ID do segundo produto para pizza meio a meio',
            ],
            'nome_produto_meio_a_meio' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'nome_produto',
                'comment'    => 'Nome do segundo produto (se não estiver cadastrado)',
            ],
        ];
        
        $this->forge->addColumn('itens_pedido', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('itens_pedido', ['id_produto_meio_a_meio', 'nome_produto_meio_a_meio']);
    }
}

