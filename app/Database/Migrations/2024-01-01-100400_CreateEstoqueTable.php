<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEstoqueTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_estoque' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'ID do cliente (pizzaria) - para SaaS',
            ],
            'id_produto' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_deposito' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantidade' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'default'    => 0.000,
            ],
            'custo_medio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'comment'    => 'Custo médio ponderado',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_estoque', true);
        $this->forge->addKey(['id_cliente', 'id_produto', 'id_deposito'], false, true);
        $this->forge->addKey('id_produto');
        $this->forge->addKey('id_deposito');
        $this->forge->createTable('estoque');
    }

    public function down()
    {
        $this->forge->dropTable('estoque');
    }
}

