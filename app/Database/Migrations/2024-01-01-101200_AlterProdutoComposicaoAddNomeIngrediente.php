<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProdutoComposicaoAddNomeIngrediente extends Migration
{
    public function up()
    {
        $fields = [
            'nome_ingrediente' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'id_ingrediente',
                'comment'    => 'Nome do ingrediente (para ingredientes padrão)',
            ],
        ];
        
        $this->forge->addColumn('produto_composicao', $fields);
        
        // Torna id_ingrediente nullable
        $this->db->query("ALTER TABLE produto_composicao MODIFY id_ingrediente INT(11) UNSIGNED NULL");
    }

    public function down()
    {
        $this->forge->dropColumn('produto_composicao', 'nome_ingrediente');
        $this->db->query("ALTER TABLE produto_composicao MODIFY id_ingrediente INT(11) UNSIGNED NOT NULL");
    }
}

