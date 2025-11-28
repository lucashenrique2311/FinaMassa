<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID do cliente (pizzaria) - para SaaS',
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'senha' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'telefone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'ativo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Ativo, 0=Inativo',
            ],
            'admin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=Admin, 0=Usuário comum',
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

        $this->forge->addKey('id_usuario', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('email');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}

