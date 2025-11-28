<?php namespace App\Controllers;

use App\Models\PermissaoModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UsuarioPermissoes extends BaseController
{
    protected $permissaoModel;
    protected $usuarioModel;
    protected $session;
    protected $usuario;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->usuario = $this->session->get('dadoslogin');
        $this->permissaoModel = new PermissaoModel();
        $this->usuarioModel = new UsuarioModel();
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
        
        // Verificar se é admin (apenas admins podem gerenciar permissões)
        $isAdmin = $this->usuario['admin'] ?? $this->usuario['ADMIN'] ?? 0;
        if (!$isAdmin) {
            return redirect()->to('/Dashboard')->with('erro', 'Acesso negado. Apenas administradores podem gerenciar permissões.');
        }
    }

    /**
     * Exibe tela para atribuir permissões a um usuário
     */
    public function atribuir($idUsuario = null)
    {
        if (!$idUsuario) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não informado.');
        }
        
        $usuario_data = $this->usuarioModel->getUsuario($idUsuario);
        
        if (!$usuario_data) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não encontrado.');
        }

        // Busca todas as permissões agrupadas por módulo
        $permissoes = $this->permissaoModel->getPermissoes();
        $permissoesAgrupadas = [];
        foreach ($permissoes as $permissao) {
            $modulo = $permissao['modulo'];
            if (!isset($permissoesAgrupadas[$modulo])) {
                $permissoesAgrupadas[$modulo] = [];
            }
            $permissoesAgrupadas[$modulo][] = $permissao;
        }

        // Busca permissões já atribuídas ao usuário
        $permissoesUsuario = $this->permissaoModel->getPermissoesUsuario($idUsuario);
        $permissoesUsuarioIds = array_column($permissoesUsuario, 'id_permissao');
        
        $data = [
            'title' => 'Atribuir Permissões - ' . $usuario_data['nome'],
            'usuario' => $this->usuario,
            'usuario_data' => $usuario_data,
            'permissoes_agrupadas' => $permissoesAgrupadas,
            'permissoes_usuario_ids' => $permissoesUsuarioIds,
        ];

        echo view('Commons/header');
        echo view('UsuarioPermissoes/atribuir', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva permissões atribuídas ao usuário
     */
    public function salvar()
    {
        $idUsuario = $this->request->getPost('id_usuario');
        
        if (!$idUsuario) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não informado.');
        }
        
        $usuario_data = $this->usuarioModel->getUsuario($idUsuario);
        
        if (!$usuario_data) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não encontrado.');
        }

        // Remove todas as permissões atuais do usuário
        $db = \Config\Database::connect();
        $db->table('usuario_permissoes')->where('id_usuario', $idUsuario)->delete();

        // Adiciona as novas permissões selecionadas
        $permissoesSelecionadas = $this->request->getPost('permissoes');
        
        if (!empty($permissoesSelecionadas) && is_array($permissoesSelecionadas)) {
            $dataToInsert = [];
            foreach ($permissoesSelecionadas as $idPermissao) {
                $dataToInsert[] = [
                    'id_usuario' => $idUsuario,
                    'id_permissao' => $idPermissao,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            
            if (!empty($dataToInsert)) {
                $db->table('usuario_permissoes')->insertBatch($dataToInsert);
            }
        }

        return redirect()->to('/Usuarios')->with('sucesso', 'Permissões atribuídas com sucesso!');
    }
}

