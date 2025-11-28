<?php

namespace App\Models;

use CodeIgniter\Model;

class DepositoModel extends Model
{
    protected $table = 'depositos';
    protected $primaryKey = 'id_deposito';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'nome',
        'descricao',
        'endereco',
        'responsavel',
        'telefone',
        'ativo'
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
        'nome' => 'required|max_length[255]',
        'ativo' => 'permit_empty|integer',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

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
     * Busca depósitos do cliente logado
     */
    public function getDepositos($filtros = [])
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $builder = $this->builder();
        $builder->where('id_cliente', $this->idCliente);
        $builder->where('deleted_at', null);

        if (isset($filtros['ativo'])) {
            $builder->where('ativo', $filtros['ativo']);
        }

        if (isset($filtros['busca']) && !empty($filtros['busca'])) {
            $builder->groupStart();
            $builder->like('nome', $filtros['busca']);
            $builder->orLike('descricao', $filtros['busca']);
            $builder->groupEnd();
        }

        if (isset($filtros['order_by'])) {
            $builder->orderBy($filtros['order_by'], $filtros['order_dir'] ?? 'ASC');
        } else {
            $builder->orderBy('nome', 'ASC');
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Busca depósito por ID
     */
    public function getDeposito($id)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_deposito', $id)
                    ->first();
    }
}

