<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'codigo',
        'nome',
        'descricao',
        'categoria',
        'unidade_medida',
        'custo_unitario',
        'preco_venda',
        'estoque_minimo',
        'controla_estoque',
        'eh_ingrediente',
        'ativo',
        'imagem'
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
        'categoria' => 'permit_empty|max_length[100]',
        'unidade_medida' => 'permit_empty|max_length[20]',
        'custo_unitario' => 'permit_empty|decimal',
        'preco_venda' => 'permit_empty|decimal',
        'estoque_minimo' => 'permit_empty|decimal',
        'controla_estoque' => 'permit_empty|integer',
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
     * Busca produtos do cliente logado
     */
    public function getProdutos($filtros = [])
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

        if (isset($filtros['eh_ingrediente']) && $filtros['eh_ingrediente'] !== null) {
            if ($filtros['eh_ingrediente'] == 0) {
                // Para produtos finais, inclui 0 ou NULL
                $builder->groupStart();
                $builder->where('eh_ingrediente', 0);
                $builder->orWhere('eh_ingrediente', null);
                $builder->groupEnd();
            } else {
                $builder->where('eh_ingrediente', $filtros['eh_ingrediente']);
            }
        }

        if (isset($filtros['categoria']) && !empty($filtros['categoria'])) {
            $builder->where('categoria', $filtros['categoria']);
        }

        if (isset($filtros['busca']) && !empty($filtros['busca'])) {
            $builder->groupStart();
            $builder->like('nome', $filtros['busca']);
            $builder->orLike('codigo', $filtros['busca']);
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
     * Busca produto por ID
     */
    public function getProduto($id)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_produto', $id)
                    ->first();
    }

    /**
     * Busca categorias únicas do cliente
     * Agora busca da tabela categorias_produto
     */
    public function getCategorias()
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $categoriaModel = new \App\Models\CategoriaProdutoModel();
        $categorias = $categoriaModel->getCategorias(['ativo' => 1]);
        
        // Retorna apenas os nomes para compatibilidade
        return array_column($categorias, 'nome');
    }
    
    /**
     * Busca categorias completas (com todos os dados)
     */
    public function getCategoriasCompletas()
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $categoriaModel = new \App\Models\CategoriaProdutoModel();
        return $categoriaModel->getCategorias(['ativo' => 1]);
    }
}

