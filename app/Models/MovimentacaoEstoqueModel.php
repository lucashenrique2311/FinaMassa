<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentacaoEstoqueModel extends Model
{
    protected $table = 'movimentacoes_estoque';
    protected $primaryKey = 'id_movimentacao';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'id_produto',
        'id_deposito',
        'tipo',
        'quantidade',
        'custo_unitario',
        'id_fornecedor',
        'id_pedido_venda',
        'observacoes',
        'id_usuario',
        'data_movimentacao',
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

    protected $idCliente = null;
    protected $idUsuario = null;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $usuario = $this->session->get('dadoslogin');
        if ($usuario) {
            $this->idCliente = $usuario['ID_CLIENTE'] ?? null;
            $this->idUsuario = $usuario['ID_USUARIO'] ?? $usuario['ID_CLIENTE'] ?? null;
        }
    }

    /**
     * Busca movimentações do cliente
     */
    public function getMovimentacoes($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('movimentacoes_estoque.*, produtos.nome as produto_nome, produtos.codigo as produto_codigo, depositos.nome as deposito_nome, fornecedores.razao_social as fornecedor_nome');
        $builder->join('produtos', 'produtos.id_produto = movimentacoes_estoque.id_produto', 'left');
        $builder->join('depositos', 'depositos.id_deposito = movimentacoes_estoque.id_deposito', 'left');
        $builder->join('fornecedores', 'fornecedores.id_fornecedor = movimentacoes_estoque.id_fornecedor', 'left');
        $builder->where('movimentacoes_estoque.id_cliente', $this->idCliente);

        if (isset($filtros['tipo'])) {
            $builder->where('movimentacoes_estoque.tipo', $filtros['tipo']);
        }

        if (isset($filtros['id_produto'])) {
            $builder->where('movimentacoes_estoque.id_produto', $filtros['id_produto']);
        }

        if (isset($filtros['id_deposito'])) {
            $builder->where('movimentacoes_estoque.id_deposito', $filtros['id_deposito']);
        }

        if (isset($filtros['data_inicio'])) {
            $builder->where('movimentacoes_estoque.data_movimentacao >=', $filtros['data_inicio']);
        }

        if (isset($filtros['data_fim'])) {
            $builder->where('movimentacoes_estoque.data_movimentacao <=', $filtros['data_fim'] . ' 23:59:59');
        }

        $builder->orderBy('movimentacoes_estoque.data_movimentacao', 'DESC');
        $builder->orderBy('movimentacoes_estoque.id_movimentacao', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Registra uma movimentação de estoque
     */
    public function registrarMovimentacao($dados)
    {
        // Prepara dados obrigatórios
        $data = [
            'id_cliente' => $this->idCliente,
            'id_produto' => intval($dados['id_produto']),
            'id_deposito' => intval($dados['id_deposito']),
            'tipo' => $dados['tipo'],
            'quantidade' => floatval($dados['quantidade']),
            'custo_unitario' => floatval($dados['custo_unitario']),
            'id_usuario' => intval($this->idUsuario),
            'data_movimentacao' => isset($dados['data_movimentacao']) ? $dados['data_movimentacao'] : date('Y-m-d H:i:s'),
        ];
        
        // Campos opcionais
        if (isset($dados['id_fornecedor']) && !empty($dados['id_fornecedor'])) {
            $data['id_fornecedor'] = intval($dados['id_fornecedor']);
        }
        if (isset($dados['id_pedido_venda']) && !empty($dados['id_pedido_venda'])) {
            $data['id_pedido_venda'] = intval($dados['id_pedido_venda']);
        }
        if (isset($dados['observacoes']) && !empty($dados['observacoes'])) {
            $data['observacoes'] = $dados['observacoes'];
        }

        // Usa builder diretamente para ter mais controle
        $builder = $this->builder();
        $result = $builder->insert($data);
        if ($result) {
            $this->insertID = $this->db->insertID();
        }
        return $result;
    }
}

