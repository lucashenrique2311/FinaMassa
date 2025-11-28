<?php namespace App\Controllers;

use App\Models\PermissaoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Permissoes extends BaseController
{
    protected $permissaoModel;
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
     * Lista todas as permissões
     */
    public function index()
    {
        $filtros = [
            'modulo' => $this->request->getGet('modulo'),
            'acao' => $this->request->getGet('acao'),
        ];

        $permissoes = $this->permissaoModel->getPermissoes($filtros);
        
        // Agrupa por módulo para exibição
        $permissoesAgrupadas = [];
        foreach ($permissoes as $permissao) {
            $modulo = $permissao['modulo'];
            if (!isset($permissoesAgrupadas[$modulo])) {
                $permissoesAgrupadas[$modulo] = [];
            }
            $permissoesAgrupadas[$modulo][] = $permissao;
        }
        
        $data = [
            'title' => 'Permissões',
            'usuario' => $this->usuario,
            'permissoes' => $permissoes,
            'permissoes_agrupadas' => $permissoesAgrupadas,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Permissoes/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $data = [
            'title' => 'Nova Permissão',
            'usuario' => $this->usuario,
            'permissao_data' => null,
        ];

        echo view('Commons/header');
        echo view('Permissoes/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $permissao_data = $this->permissaoModel->find($id);
        
        if (!$permissao_data) {
            return redirect()->to('/Permissoes')->with('erro', 'Permissão não encontrada.');
        }

        $data = [
            'title' => 'Editar Permissão',
            'usuario' => $this->usuario,
            'permissao_data' => $permissao_data,
        ];

        echo view('Commons/header');
        echo view('Permissoes/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva nova permissão
     */
    public function salvar()
    {
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'modulo' => $this->request->getPost('modulo'),
            'acao' => $this->request->getPost('acao'),
        ];

        if ($this->permissaoModel->insert($dados)) {
            return redirect()->to('/Permissoes')->with('sucesso', 'Permissão criada com sucesso!');
        } else {
            $erros = $this->permissaoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza permissão existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        
        $permissao_data = $this->permissaoModel->find($id);
        
        if (!$permissao_data) {
            return redirect()->to('/Permissoes')->with('erro', 'Permissão não encontrada.');
        }

        $dados = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'modulo' => $this->request->getPost('modulo'),
            'acao' => $this->request->getPost('acao'),
        ];

        if ($this->permissaoModel->update($id, $dados)) {
            return redirect()->to('/Permissoes')->with('sucesso', 'Permissão atualizada com sucesso!');
        } else {
            $erros = $this->permissaoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui permissão
     */
    public function excluir($id)
    {
        $permissao_data = $this->permissaoModel->find($id);
        
        if (!$permissao_data) {
            return redirect()->to('/Permissoes')->with('erro', 'Permissão não encontrada.');
        }

        // Verificar se há usuários com essa permissão
        $db = \Config\Database::connect();
        $builder = $db->table('usuario_permissoes');
        $builder->where('id_permissao', $id);
        $count = $builder->countAllResults();
        
        if ($count > 0) {
            return redirect()->to('/Permissoes')->with('erro', 'Não é possível excluir esta permissão. Existem ' . $count . ' usuário(s) com esta permissão atribuída.');
        }

        if ($this->permissaoModel->delete($id)) {
            return redirect()->to('/Permissoes')->with('sucesso', 'Permissão excluída com sucesso!');
        } else {
            return redirect()->to('/Permissoes')->with('erro', 'Erro ao excluir permissão.');
        }
    }
}

