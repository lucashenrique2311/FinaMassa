<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemPedidoModel extends Model
{
    protected $table = 'itens_pedido';
    protected $primaryKey = 'id_item';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_pedido',
        'id_produto',
        'nome_produto',
        'id_produto_meio_a_meio',
        'nome_produto_meio_a_meio',
        'quantidade',
        'preco_unitario',
        'desconto',
        'subtotal',
        'observacoes',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;

    /**
     * Busca itens de um pedido
     */
    public function getItensPedido($idPedido)
    {
        $builder = $this->builder();
        $builder->select('itens_pedido.*, 
                         produtos.nome as produto_cadastrado, 
                         produtos.codigo as produto_codigo,
                         produtos_meio_a_meio.nome as produto_meio_a_meio_cadastrado,
                         produtos_meio_a_meio.codigo as produto_meio_a_meio_codigo');
        $builder->join('produtos', 'produtos.id_produto = itens_pedido.id_produto', 'left');
        $builder->join('produtos as produtos_meio_a_meio', 'produtos_meio_a_meio.id_produto = itens_pedido.id_produto_meio_a_meio', 'left');
        $builder->where('itens_pedido.id_pedido', $idPedido);
        $builder->orderBy('itens_pedido.id_item', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Calcula subtotal do item
     */
    public function calcularSubtotal($quantidade, $precoUnitario, $desconto = 0)
    {
        return ($quantidade * $precoUnitario) - $desconto;
    }

    /**
     * Remove todos os itens de um pedido
     */
    public function removerItensPedido($idPedido)
    {
        return $this->where('id_pedido', $idPedido)->delete();
    }
}

