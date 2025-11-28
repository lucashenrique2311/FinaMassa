<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdutosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_produto' => [
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
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'Código interno do produto',
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'categoria' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Ex: Massa, Molho, Queijo, Bebida, etc',
            ],
            'unidade_medida' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'UN',
                'comment'    => 'UN, KG, L, etc',
            ],
            'custo_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'preco_venda' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'null'       => true,
                'comment'    => 'Preço de venda (se aplicável)',
            ],
            'estoque_minimo' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'default'    => 0.000,
            ],
            'controla_estoque' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Controla estoque, 0=Não controla',
            ],
            'ativo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'imagem' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
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

        $this->forge->addKey('id_produto', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('codigo');
        $this->forge->addKey('categoria');
        $this->forge->createTable('produtos');
    }

    public function down()
    {
        $this->forge->dropTable('produtos');
    }
}

