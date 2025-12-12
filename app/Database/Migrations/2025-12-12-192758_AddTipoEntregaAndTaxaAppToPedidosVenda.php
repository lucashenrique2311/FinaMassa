<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoEntregaAndTaxaAppToPedidosVenda extends Migration
{
    public function up()
    {
        $fields = [
            'tipo_entrega' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'tipo_pedido',
                'comment'    => 'DELIVERY, RETIRADA - usado quando tipo_pedido = APP',
            ],
            'taxa_app' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'taxa_entrega',
                'comment'    => 'Taxa do app (UaiRango) - percentual padrão 10%',
            ],
        ];
        
        $this->forge->addColumn('pedidos_venda', $fields);
        
        // Atualiza comentário do tipo_pedido
        $this->db->query("ALTER TABLE pedidos_venda MODIFY COLUMN tipo_pedido VARCHAR(20) DEFAULT 'BALCAO' COMMENT 'BALCAO, APP (UaiRango)'");
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos_venda', ['tipo_entrega', 'taxa_app']);
        
        // Restaura comentário original
        $this->db->query("ALTER TABLE pedidos_venda MODIFY COLUMN tipo_pedido VARCHAR(20) DEFAULT 'BALCAO' COMMENT 'BALCAO, DELIVERY, RETIRADA'");
    }
}
