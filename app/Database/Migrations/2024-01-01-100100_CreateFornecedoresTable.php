<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFornecedoresTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_fornecedor' => [
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
            'razao_social' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nome_fantasia' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => '18',
                'null'       => true,
            ],
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'null'       => true,
            ],
            'inscricao_estadual' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'telefone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'celular' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cep' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'endereco' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'complemento' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'bairro' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('id_fornecedor', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('cnpj');
        $this->forge->createTable('fornecedores');
    }

    public function down()
    {
        $this->forge->dropTable('fornecedores');
    }
}

