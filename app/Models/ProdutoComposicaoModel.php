<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoComposicaoModel extends Model
{
    protected $table = 'produto_composicao';
    protected $primaryKey = 'id_composicao';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_produto',
        'id_ingrediente',
        'nome_ingrediente',
        'quantidade',
        'custo_unitario',
        'subtotal',
        'observacoes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = null;

    /**
     * Busca composição de um produto
     */
    public function getComposicao($idProduto)
    {
        $builder = $this->builder();
        $builder->select('produto_composicao.*, produtos.nome as ingrediente_nome, produtos.codigo as ingrediente_codigo, produtos.unidade_medida as ingrediente_unidade, COALESCE(produtos.nome, produto_composicao.nome_ingrediente) as nome_ordenacao');
        $builder->join('produtos', 'produtos.id_produto = produto_composicao.id_ingrediente', 'left');
        $builder->where('produto_composicao.id_produto', $idProduto);
        $builder->orderBy('nome_ordenacao', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Calcula custo total da composição
     */
    public function calcularCustoTotal($idProduto)
    {
        $composicao = $this->getComposicao($idProduto);
        $total = 0;
        
        foreach ($composicao as $item) {
            $total += $item['subtotal'] ?? 0;
        }
        
        return $total;
    }

    /**
     * Remove toda a composição de um produto
     */
    public function removerComposicao($idProduto)
    {
        return $this->where('id_produto', $idProduto)->delete();
    }

    /**
     * Salva composição completa (remove antiga e salva nova)
     */
    public function salvarComposicao($idProduto, $itens)
    {
        // Remove composição antiga
        $this->removerComposicao($idProduto);
        
        // Salva novos itens
        foreach ($itens as $item) {
            // Garante que quantidade e custos sejam números, não strings formatadas
            $quantidade = converterQuantidadeParaFloat($item['quantidade'] ?? 0);
            $custoUnitario = converterQuantidadeParaFloat($item['custo_unitario'] ?? 0);
            $subtotal = converterQuantidadeParaFloat($item['subtotal'] ?? 0);
            
            $this->insert([
                'id_produto' => $idProduto,
                'id_ingrediente' => $item['id_ingrediente'] ?? null,
                'nome_ingrediente' => $item['nome_ingrediente'] ?? null,
                'quantidade' => $quantidade,
                'custo_unitario' => $custoUnitario,
                'subtotal' => $subtotal,
                'observacoes' => $item['observacoes'] ?? null,
            ]);
        }
        
        return true;
    }
}

