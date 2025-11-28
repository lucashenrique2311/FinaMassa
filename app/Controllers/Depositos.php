<?php namespace App\Controllers;

use App\Models\DepositoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Depositos extends BaseController
{
    protected $depositoModel;
    protected $session;
    protected $usuario;
    protected $idCliente;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->usuario = $this->session->get('dadoslogin');
        $this->depositoModel = new DepositoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os depósitos
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
            'order_by' => $this->request->getGet('order_by') ?? 'nome',
            'order_dir' => $this->request->getGet('order_dir') ?? 'ASC',
        ];

        $depositos = $this->depositoModel->getDepositos($filtros);
        
        $data = [
            'title' => 'Depósitos',
            'usuario' => $this->usuario,
            'depositos' => $depositos,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Depositos/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $data = [
            'title' => 'Novo Depósito',
            'usuario' => $this->usuario,
            'deposito_data' => null,
        ];

        echo view('Commons/header');
        echo view('Depositos/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $deposito_data = $this->depositoModel->getDeposito($id);
        
        if (!$deposito_data) {
            return redirect()->to('/Depositos')->with('erro', 'Depósito não encontrado.');
        }

        $data = [
            'title' => 'Editar Depósito',
            'usuario' => $this->usuario,
            'deposito_data' => $deposito_data,
        ];

        echo view('Commons/header');
        echo view('Depositos/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo depósito
     */
    public function salvar()
    {
        $dados = [
            'id_cliente' => $this->idCliente,
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'endereco' => $this->request->getPost('endereco'),
            'responsavel' => $this->request->getPost('responsavel'),
            'telefone' => $this->request->getPost('telefone'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        if ($this->depositoModel->insert($dados)) {
            return redirect()->to('/Depositos')->with('sucesso', 'Depósito criado com sucesso!');
        } else {
            $erros = $this->depositoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza depósito existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id_deposito');
        }
        
        $deposito_data = $this->depositoModel->getDeposito($id);
        
        if (!$deposito_data) {
            return redirect()->to('/Depositos')->with('erro', 'Depósito não encontrado.');
        }

        $dados = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'endereco' => $this->request->getPost('endereco'),
            'responsavel' => $this->request->getPost('responsavel'),
            'telefone' => $this->request->getPost('telefone'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        if ($this->depositoModel->update($id, $dados)) {
            return redirect()->to('/Depositos')->with('sucesso', 'Depósito atualizado com sucesso!');
        } else {
            $erros = $this->depositoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui depósito (soft delete)
     */
    public function excluir($id)
    {
        $deposito_data = $this->depositoModel->getDeposito($id);

        if (!$deposito_data) {
            return redirect()->to('/Depositos')->with('erro', 'Depósito não encontrado.');
        }

        if ($this->depositoModel->delete($id)) {
            return redirect()->to('/Depositos')->with('sucesso', 'Depósito excluído com sucesso!');
        } else {
            return redirect()->to('/Depositos')->with('erro', 'Erro ao excluir depósito.');
        }
    }
}
