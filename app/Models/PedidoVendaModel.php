<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoVendaModel extends Model
{
    protected $table = 'pedidos_venda';
    protected $primaryKey = 'id_pedido';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'numero_pedido',
        'data_pedido',
        'cliente_nome',
        'cliente_telefone',
        'cliente_endereco',
        'tipo_pedido',
        'status',
        'subtotal',
        'desconto',
        'taxa_entrega',
        'total',
        'forma_pagamento',
        'observacoes',
        'id_usuario'
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
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_cliente' => 'required|integer',
        'data_pedido' => 'required|valid_date',
        'tipo_pedido' => 'required|in_list[BALCAO,DELIVERY,RETIRADA]',
        'status' => 'permit_empty|in_list[ABERTO,PREPARANDO,PRONTO,ENTREGUE,CANCELADO]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['gerarNumeroPedido'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

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
     * Gera número do pedido automaticamente
     */
    protected function gerarNumeroPedido(array $data)
    {
        if (empty($data['data']['numero_pedido'])) {
            $ano = date('Y');
            $ultimoPedido = $this->where('id_cliente', $this->idCliente)
                                 ->like('numero_pedido', $ano, 'after')
                                 ->orderBy('id_pedido', 'DESC')
                                 ->first();
            
            if ($ultimoPedido) {
                $ultimoNumero = (int) substr($ultimoPedido['numero_pedido'], -6);
                $novoNumero = $ultimoNumero + 1;
            } else {
                $novoNumero = 1;
            }
            
            $data['data']['numero_pedido'] = $ano . str_pad($novoNumero, 6, '0', STR_PAD_LEFT);
        }
        
        return $data;
    }

    /**
     * Busca pedidos do cliente
     */
    public function getPedidos($filtros = [])
    {
        $builder = $this->builder();
        $builder->where('id_cliente', $this->idCliente);
        $builder->where('deleted_at', null);

        if (isset($filtros['status'])) {
            $builder->where('status', $filtros['status']);
        }

        if (isset($filtros['tipo_pedido'])) {
            $builder->where('tipo_pedido', $filtros['tipo_pedido']);
        }

        if (isset($filtros['data_inicio'])) {
            $builder->where('data_pedido >=', $filtros['data_inicio']);
        }

        if (isset($filtros['data_fim'])) {
            $builder->where('data_pedido <=', $filtros['data_fim'] . ' 23:59:59');
        }

        if (isset($filtros['busca']) && !empty($filtros['busca'])) {
            $builder->groupStart();
            $builder->like('numero_pedido', $filtros['busca']);
            $builder->orLike('cliente_nome', $filtros['busca']);
            $builder->orLike('cliente_telefone', $filtros['busca']);
            $builder->groupEnd();
        }

        $builder->orderBy('data_pedido', 'DESC');
        $builder->orderBy('id_pedido', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Busca pedido por ID
     */
    public function getPedido($id)
    {
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_pedido', $id)
                    ->first();
    }

    /**
     * Calcula totais do pedido
     */
    public function calcularTotais($subtotal, $desconto = 0, $taxaEntrega = 0)
    {
        $total = $subtotal - $desconto + $taxaEntrega;
        return [
            'subtotal' => $subtotal,
            'desconto' => $desconto,
            'taxa_entrega' => $taxaEntrega,
            'total' => max(0, $total)
        ];
    }
}

