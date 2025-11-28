<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaProdutoModel extends Model
{
    protected $table = 'categorias_produto';
    protected $primaryKey = 'id_categoria';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'nome',
        'descricao',
        'cor',
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
     * Busca categorias do cliente
     */
    public function getCategorias($filtros = [])
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $builder = $this->builder();
        $builder->where('id_cliente', $this->idCliente);
        
        if (isset($filtros['ativo'])) {
            $builder->where('ativo', $filtros['ativo']);
        }
        
        if (isset($filtros['busca']) && !empty($filtros['busca'])) {
            $builder->groupStart();
            $builder->like('nome', $filtros['busca']);
            $builder->orLike('descricao', $filtros['busca']);
            $builder->groupEnd();
        }
        
        $builder->orderBy('nome', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Busca categoria por ID
     */
    public function getCategoria($id)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_categoria', $id)
                    ->first();
    }

    /**
     * Busca categoria por nome
     */
    public function getCategoriaPorNome($nome)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('nome', $nome)
                    ->first();
    }
}

