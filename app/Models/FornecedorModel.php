<?php

namespace App\Models;

use CodeIgniter\Model;

class FornecedorModel extends Model
{
    protected $table = 'fornecedores';
    protected $primaryKey = 'id_fornecedor';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'cpf',
        'inscricao_estadual',
        'telefone',
        'celular',
        'email',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'observacoes',
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
        'razao_social' => 'required|max_length[255]',
        'cnpj' => 'permit_empty|max_length[18]',
        'cpf' => 'permit_empty|max_length[14]',
        'email' => 'permit_empty|valid_email|max_length[255]',
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
     * Busca fornecedores do cliente logado
     */
    public function getFornecedores($filtros = [])
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
            $builder->like('razao_social', $filtros['busca']);
            $builder->orLike('nome_fantasia', $filtros['busca']);
            $builder->orLike('cnpj', $filtros['busca']);
            $builder->orLike('cpf', $filtros['busca']);
            $builder->groupEnd();
        }

        if (isset($filtros['order_by'])) {
            $builder->orderBy($filtros['order_by'], $filtros['order_dir'] ?? 'ASC');
        } else {
            $builder->orderBy('razao_social', 'ASC');
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Busca fornecedor por ID
     */
    public function getFornecedor($id)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_fornecedor', $id)
                    ->first();
    }
}

