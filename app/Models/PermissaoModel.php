<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissaoModel extends Model
{
    protected $table = 'permissoes';
    protected $primaryKey = 'id_permissao';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nome',
        'descricao',
        'modulo',
        'acao'
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
     * Busca todas as permissões
     */
    public function getPermissoes($filtros = [])
    {
        $builder = $this->builder();

        if (isset($filtros['modulo'])) {
            $builder->where('modulo', $filtros['modulo']);
        }

        if (isset($filtros['acao'])) {
            $builder->where('acao', $filtros['acao']);
        }

        $builder->orderBy('modulo', 'ASC');
        $builder->orderBy('acao', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Busca permissões de um usuário
     */
    public function getPermissoesUsuario($idUsuario)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuario_permissoes');
        $builder->select('permissoes.*');
        $builder->join('permissoes', 'permissoes.id_permissao = usuario_permissoes.id_permissao');
        $builder->where('usuario_permissoes.id_usuario', $idUsuario);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verifica se usuário tem permissão
     */
    public function usuarioTemPermissao($idUsuario, $modulo, $acao)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuario_permissoes');
        $builder->join('permissoes', 'permissoes.id_permissao = usuario_permissoes.id_permissao');
        $builder->where('usuario_permissoes.id_usuario', $idUsuario);
        $builder->where('permissoes.modulo', $modulo);
        $builder->where('permissoes.acao', $acao);
        
        return $builder->countAllResults() > 0;
    }
}

