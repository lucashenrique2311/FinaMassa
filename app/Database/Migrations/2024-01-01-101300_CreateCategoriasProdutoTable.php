<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasProdutoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_categoria' => [
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
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Nome da categoria',
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cor' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Cor para identificação visual',
            ],
            'ativo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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

        $this->forge->addKey('id_categoria', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('nome');
        $this->forge->addKey(['id_cliente', 'nome'], false, true);
        $this->forge->createTable('categorias_produto');
    }

    public function down()
    {
        $this->forge->dropTable('categorias_produto');
    }
}

