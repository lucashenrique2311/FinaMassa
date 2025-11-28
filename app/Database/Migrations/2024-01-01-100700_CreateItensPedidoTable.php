<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItensPedidoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_item' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pedido' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_produto' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Null se for produto não cadastrado',
            ],
            'nome_produto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'Nome do produto (caso não esteja cadastrado)',
            ],
            'quantidade' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
            ],
            'preco_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'desconto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_item', true);
        $this->forge->addKey('id_pedido');
        $this->forge->addKey('id_produto');
        $this->forge->createTable('itens_pedido');
    }

    public function down()
    {
        $this->forge->dropTable('itens_pedido');
    }
}

