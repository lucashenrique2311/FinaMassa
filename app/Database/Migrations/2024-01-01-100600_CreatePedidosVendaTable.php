<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosVendaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pedido' => [
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
            'numero_pedido' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Número do pedido (gerado automaticamente)',
            ],
            'data_pedido' => [
                'type' => 'DATETIME',
            ],
            'cliente_nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Nome do cliente que fez o pedido',
            ],
            'cliente_telefone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'cliente_endereco' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tipo_pedido' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'BALCAO',
                'comment'    => 'BALCAO, DELIVERY, RETIRADA',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ABERTO',
                'comment'    => 'ABERTO, PREPARANDO, PRONTO, ENTREGUE, CANCELADO',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'desconto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'taxa_entrega' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'forma_pagamento' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'DINHEIRO, CARTAO_CREDITO, CARTAO_DEBITO, PIX, etc',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Usuário que criou o pedido',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_pedido', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('numero_pedido');
        $this->forge->addKey('data_pedido');
        $this->forge->addKey('status');
        $this->forge->createTable('pedidos_venda');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_venda');
    }
}

