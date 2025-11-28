<?php

namespace App\Models;

use CodeIgniter\Model;

class EstoqueModel extends Model
{
    protected $table = 'estoque';
    protected $primaryKey = 'id_estoque';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'id_produto',
        'id_deposito',
        'quantidade',
        'custo_medio',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = null;
    protected $updatedField = 'updated_at';
    protected $deletedField = null;

    protected $idCliente = null;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $usuario = $this->session->get('dadoslogin');
        if ($usuario) {
            $this->idCliente = $usuario['ID_CLIENTE'] ?? null;
        }
    }

    /**
     * Busca estoque do cliente
     */
    public function getEstoque($filtros = [])
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $builder = $this->builder();
        $builder->select('estoque.*, produtos.nome as produto_nome, produtos.codigo as produto_codigo, produtos.unidade_medida, depositos.nome as deposito_nome');
        $builder->join('produtos', 'produtos.id_produto = estoque.id_produto', 'left');
        $builder->join('depositos', 'depositos.id_deposito = estoque.id_deposito', 'left');
        $builder->where('estoque.id_cliente', $this->idCliente);
        // Apenas produtos que são ingredientes controlam estoque
        $builder->where('produtos.eh_ingrediente', 1);

        if (isset($filtros['id_deposito'])) {
            $builder->where('estoque.id_deposito', $filtros['id_deposito']);
        }

        if (isset($filtros['id_produto'])) {
            $builder->where('estoque.id_produto', $filtros['id_produto']);
        }

        if (isset($filtros['estoque_baixo']) && $filtros['estoque_baixo']) {
            $builder->join('produtos as p', 'p.id_produto = estoque.id_produto', 'left');
            $builder->where('estoque.quantidade <= p.estoque_minimo');
        }

        $builder->orderBy('produtos.nome', 'ASC');
        $builder->orderBy('depositos.nome', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Busca estoque de um produto em um depósito
     */
    public function getEstoqueProdutoDeposito($idProduto, $idDeposito)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_produto', $idProduto)
                    ->where('id_deposito', $idDeposito)
                    ->first();
    }

    /**
     * Atualiza ou cria estoque
     */
    public function atualizarEstoque($idProduto, $idDeposito, $quantidade, $custoMedio = null)
    {
        $estoque = $this->getEstoqueProdutoDeposito($idProduto, $idDeposito);
        
        if ($estoque) {
            $data = [
                'quantidade' => $quantidade
            ];
            if ($custoMedio !== null) {
                $data['custo_medio'] = $custoMedio;
            }
            return $this->update($estoque['id_estoque'], $data);
        } else {
            // Garante que os valores são numéricos
            $quantidade = floatval($quantidade);
            $custoMedio = $custoMedio !== null ? floatval($custoMedio) : 0.00;
            
            // Usa builder diretamente para ter mais controle
            $builder = $this->builder();
            $data = [
                'id_cliente' => intval($this->idCliente),
                'id_produto' => intval($idProduto),
                'id_deposito' => intval($idDeposito),
                'quantidade' => $quantidade,
                'custo_medio' => $custoMedio,
            ];
            
            // Insere usando builder para evitar problemas com timestamps
            $result = $builder->insert($data);
            if ($result) {
                $this->insertID = $this->db->insertID();
            }
            return $result;
        }
    }
}

