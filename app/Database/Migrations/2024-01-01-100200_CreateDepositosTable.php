<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepositosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_deposito' => [
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
                'constraint' => '255',
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'endereco' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'responsavel' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
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

        $this->forge->addKey('id_deposito', true);
        $this->forge->addKey('id_cliente');
        $this->forge->createTable('depositos');
    }

    public function down()
    {
        $this->forge->dropTable('depositos');
    }
}

