<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdutoComposicaoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_composicao' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_produto' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'ID do produto que está sendo composto',
            ],
            'id_ingrediente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID do produto que é ingrediente (null se for ingrediente padrão)',
            ],
            'nome_ingrediente' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Nome do ingrediente (para ingredientes padrão)',
            ],
            'quantidade' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'default'    => 0.000,
                'comment'    => 'Quantidade do ingrediente usado',
            ],
            'custo_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'comment'    => 'Custo unitário do ingrediente no momento da composição',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'comment'    => 'Quantidade * Custo Unitário',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_composicao', true);
        $this->forge->addKey('id_produto');
        $this->forge->addKey('id_ingrediente');
        $this->forge->addKey(['id_produto', 'id_ingrediente'], false, true);
        $this->forge->createTable('produto_composicao');
    }

    public function down()
    {
        $this->forge->dropTable('produto_composicao');
    }
}

