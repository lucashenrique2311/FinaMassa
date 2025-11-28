<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIngredientesPadraoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ingrediente_padrao' => [
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
                'comment'    => 'Nome do ingrediente padrão (ex: Massa, Queijo, Molho)',
            ],
            'categoria' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Categoria do ingrediente (ex: Massa, Queijo, Molho, Proteína, Vegetal)',
            ],
            'unidade_medida' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'UN',
                'comment'    => 'UN, KG, L, etc',
            ],
            'custo_padrao' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'comment'    => 'Custo padrão do ingrediente',
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

        $this->forge->addKey('id_ingrediente_padrao', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('categoria');
        $this->forge->createTable('ingredientes_padrao');
    }

    public function down()
    {
        $this->forge->dropTable('ingredientes_padrao');
    }
}

