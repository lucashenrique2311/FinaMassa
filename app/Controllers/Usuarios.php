<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Usuarios extends BaseController
{
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
        $this->usuarioModel = new UsuarioModel();
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os usuários
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
            'admin' => $this->request->getGet('admin'),
            'order_by' => $this->request->getGet('order_by') ?? 'nome',
            'order_dir' => $this->request->getGet('order_dir') ?? 'ASC',
        ];

        $usuarios = $this->usuarioModel->getUsuarios($filtros);
        
        $data = [
            'title' => 'Usuários',
            'usuario' => $this->usuario,
            'usuarios' => $usuarios,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Usuarios/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $data = [
            'title' => 'Novo Usuário',
            'usuario' => $this->usuario,
            'usuario_data' => null,
        ];

        echo view('Commons/header');
        echo view('Usuarios/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $usuario_data = $this->usuarioModel->getUsuario($id);
        
        if (!$usuario_data) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não encontrado.');
        }

        $data = [
            'title' => 'Editar Usuário',
            'usuario' => $this->usuario,
            'usuario_data' => $usuario_data,
        ];

        echo view('Commons/header');
        echo view('Usuarios/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo usuário
     */
    public function salvar()
    {
        $dados = [
            'id_cliente' => $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null,
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'telefone' => $this->request->getPost('telefone'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
            'admin' => $this->request->getPost('admin') ? 1 : 0,
        ];

        // Se tem senha, adiciona
        if ($this->request->getPost('senha')) {
            $dados['senha'] = sha1(preg_replace('/[^[:alnum:]_]/', '', $this->request->getPost('senha')));
        }

        if ($this->usuarioModel->insert($dados)) {
            return redirect()->to('/Usuarios')->with('sucesso', 'Usuário criado com sucesso!');
        } else {
            $erros = $this->usuarioModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza usuário existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        
        $usuario_data = $this->usuarioModel->getUsuario($id);
        
        if (!$usuario_data) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não encontrado.');
        }

        $dados = [
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'telefone' => $this->request->getPost('telefone'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
            'admin' => $this->request->getPost('admin') ? 1 : 0,
        ];

        // Se tem senha nova, atualiza
        if ($this->request->getPost('senha')) {
            $dados['senha'] = sha1(preg_replace('/[^[:alnum:]_]/', '', $this->request->getPost('senha')));
        }

        if ($this->usuarioModel->update($id, $dados)) {
            return redirect()->to('/Usuarios')->with('sucesso', 'Usuário atualizado com sucesso!');
        } else {
            $erros = $this->usuarioModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui usuário (soft delete)
     */
    public function excluir($id)
    {
        $usuario_data = $this->usuarioModel->getUsuario($id);
        
        if (!$usuario_data) {
            return redirect()->to('/Usuarios')->with('erro', 'Usuário não encontrado.');
        }

        // Não permite excluir a si mesmo
        $usuario_logado_id = $this->usuario['ID_USUARIO'] ?? $this->usuario['id_usuario'] ?? null;
        if ($id == $usuario_logado_id) {
            return redirect()->to('/Usuarios')->with('erro', 'Você não pode excluir seu próprio usuário.');
        }

        if ($this->usuarioModel->delete($id)) {
            return redirect()->to('/Usuarios')->with('sucesso', 'Usuário excluído com sucesso!');
        } else {
            return redirect()->to('/Usuarios')->with('erro', 'Erro ao excluir usuário.');
        }
    }

    /**
     * API para DataTable (AJAX)
     */
    public function api()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
            'admin' => $this->request->getGet('admin'),
            'order_by' => $this->request->getGet('order_by') ?? 'nome',
            'order_dir' => $this->request->getGet('order_dir') ?? 'ASC',
        ];

        $usuarios = $this->usuarioModel->getUsuarios($filtros);
        
        return $this->response->setJSON([
            'data' => $usuarios,
            'total' => count($usuarios),
        ]);
    }
}

