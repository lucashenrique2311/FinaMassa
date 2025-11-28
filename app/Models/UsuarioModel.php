<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'nome',
        'email',
        'senha',
        'telefone',
        'ativo',
        'admin'
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
        'nome' => 'required|max_length[255]',
        'email' => 'required|valid_email|max_length[255]|is_unique[usuarios.email,id_usuario,{id_usuario}]',
        'senha' => 'permit_empty|min_length[6]',
        'ativo' => 'permit_empty|integer',
        'admin' => 'permit_empty|integer',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $usuario = $this->session->get('dadoslogin');
        if ($usuario) {
            $this->idCliente = $usuario['ID_CLIENTE'] ?? $usuario['id_cliente'] ?? null;
        }
    }

    /**
     * Busca informações de login (compatível com estrutura antiga)
     */
    function getInformacoesLogin($email, $senha)
    {
        $builder = $this->builder();
        $builder->select('id_usuario, nome, email, id_cliente, admin, ativo');
        $builder->where('email', strtoupper($email));
        $builder->where('senha', $senha);
        $builder->where('ativo', 1);
        $builder->where('deleted_at', null);
        
        $result = $builder->get()->getResultArray();
        
        // Adapta para estrutura esperada pelo controller antigo
        if (!empty($result)) {
            $result[0]['EMAIL'] = $result[0]['email'];
            $result[0]['RAZAO_SOCIAL'] = $result[0]['nome'];
            $result[0]['ID_CLIENTE'] = $result[0]['id_cliente'] ?? 1;
            $result[0]['ADMIN'] = $result[0]['admin'];
            $result[0]['EXPIRADO'] = 'N'; // Sempre não expirado na nova estrutura
            $result[0]['ID_USUARIO'] = $result[0]['id_usuario'];
        }
        
        return $result;
    }

    /**
     * Busca informações do usuário
     */
    function getInformacoesUsuario($id)
    {
        return $this->where('id_usuario', $id)
                    ->where('deleted_at', null)
                    ->first();
    }

    /**
     * Busca token para recuperação de senha
     */
    function getToken($email)
    {
        $builder = $this->builder();
        $builder->select('email, senha as TOKEN'); // Usa senha como token temporário
        $builder->where('email', $email);
        $builder->where('deleted_at', null);
        return $builder->get()->getRow();
    }

    /**
     * Consulta token (compatível com estrutura antiga)
     */
    function consultaToken($token)
    {
        // Na nova estrutura, vamos usar um campo de token temporário
        // Por enquanto, retorna true se encontrar usuário com esse hash
        $builder = $this->builder();
        $builder->where('senha', $token);
        $builder->where('deleted_at', null);
        return $builder->get()->getRow();
    }

    /**
     * Atualiza senha
     */
    function updatePassword($senha, $token)
    {
        $builder = $this->builder();
        $builder->where('senha', $token); // Token é a senha antiga
        $builder->set('senha', sha1($senha));
        $builder->update();
        return $this->db->affectedRows();
    }

    /**
     * Busca usuários do cliente logado
     */
    public function getUsuarios($filtros = [])
    {
        $builder = $this->builder();
        
        if ($this->idCliente) {
            $builder->where('id_cliente', $this->idCliente);
        }
        
        $builder->where('deleted_at', null);

        if (isset($filtros['ativo'])) {
            $builder->where('ativo', $filtros['ativo']);
        }

        if (isset($filtros['admin'])) {
            $builder->where('admin', $filtros['admin']);
        }

        if (isset($filtros['busca']) && !empty($filtros['busca'])) {
            $builder->groupStart();
            $builder->like('nome', $filtros['busca']);
            $builder->orLike('email', $filtros['busca']);
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
     * Busca usuário por ID
     */
    public function getUsuario($id)
    {
        return $this->where('id_usuario', $id)
                    ->where('deleted_at', null)
                    ->first();
    }
}
