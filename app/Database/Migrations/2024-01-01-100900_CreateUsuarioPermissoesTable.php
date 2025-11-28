<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarioPermissoesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario_permissao' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_permissao' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_usuario_permissao', true);
        $this->forge->addKey(['id_usuario', 'id_permissao'], false, true);
        $this->forge->createTable('usuario_permissoes');
    }

    public function down()
    {
        $this->forge->dropTable('usuario_permissoes');
    }
}

