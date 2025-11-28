<?php

namespace App\Models;

use CodeIgniter\Model;

class IngredientePadraoModel extends Model
{
    protected $table = 'ingredientes_padrao';
    protected $primaryKey = 'id_ingrediente_padrao';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'nome',
        'categoria',
        'unidade_medida',
        'custo_padrao',
        'quantidade_inicial',
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
     * Busca ingredientes padrão do cliente
     */
    public function getIngredientesPadrao($filtros = [])
    {
        if (!$this->idCliente) {
            return [];
        }
        
        $builder = $this->builder();
        $builder->where('id_cliente', $this->idCliente);
        
        if (isset($filtros['ativo'])) {
            $builder->where('ativo', $filtros['ativo']);
        }
        
        if (isset($filtros['categoria']) && !empty($filtros['categoria'])) {
            $builder->where('categoria', $filtros['categoria']);
        }
        
        $builder->orderBy('categoria', 'ASC');
        $builder->orderBy('nome', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Busca categorias únicas
     * Busca as categorias de produtos cadastradas no sistema
     */
    public function getCategorias()
    {
        if (!$this->idCliente) {
            return [];
        }
        
        // Busca categorias de produtos cadastradas
        $categoriaModel = new \App\Models\CategoriaProdutoModel();
        $categorias = $categoriaModel->getCategorias(['ativo' => 1]);
        
        // Retorna apenas os nomes para compatibilidade
        return array_column($categorias, 'nome');
    }

    /**
     * Busca ingrediente padrão por ID
     */
    public function getIngredientePadrao($id)
    {
        if (!$this->idCliente) {
            return null;
        }
        
        return $this->where('id_cliente', $this->idCliente)
                    ->where('id_ingrediente_padrao', $id)
                    ->first();
    }
}

