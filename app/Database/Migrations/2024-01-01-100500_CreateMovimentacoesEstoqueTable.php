<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMovimentacoesEstoqueTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_movimentacao' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'ENTRADA, SAIDA, AJUSTE, TRANSFERENCIA',
            ],
            'quantidade' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
            ],
            'custo_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'id_fornecedor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_pedido_venda' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Se a saída foi por venda',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Usuário que fez a movimentação',
            ],
            'data_movimentacao' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_movimentacao', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('id_produto');
        $this->forge->addKey('id_deposito');
        $this->forge->addKey('tipo');
        $this->forge->addKey('data_movimentacao');
        $this->forge->createTable('movimentacoes_estoque');
    }

    public function down()
    {
        $this->forge->dropTable('movimentacoes_estoque');
    }
}

