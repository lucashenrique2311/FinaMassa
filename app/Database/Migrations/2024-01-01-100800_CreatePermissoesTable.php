<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissoesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_permissao' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'modulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Ex: produtos, estoque, pedidos, etc',
            ],
            'acao' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Ex: visualizar, criar, editar, excluir',
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

        $this->forge->addKey('id_permissao', true);
        $this->forge->addKey(['modulo', 'acao']);
        $this->forge->createTable('permissoes');
    }

    public function down()
    {
        $this->forge->dropTable('permissoes');
    }
}

