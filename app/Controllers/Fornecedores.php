<?php namespace App\Controllers;

use App\Models\FornecedorModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Fornecedores extends BaseController
{
    protected $fornecedorModel;
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
        $this->fornecedorModel = new FornecedorModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os fornecedores
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
            'order_by' => $this->request->getGet('order_by') ?? 'razao_social',
            'order_dir' => $this->request->getGet('order_dir') ?? 'ASC',
        ];

        $fornecedores = $this->fornecedorModel->getFornecedores($filtros);
        
        $data = [
            'title' => 'Fornecedores',
            'usuario' => $this->usuario,
            'fornecedores' => $fornecedores,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Fornecedores/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $data = [
            'title' => 'Novo Fornecedor',
            'usuario' => $this->usuario,
            'fornecedor_data' => null,
        ];

        echo view('Commons/header');
        echo view('Fornecedores/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $fornecedor_data = $this->fornecedorModel->getFornecedor($id);
        
        if (!$fornecedor_data) {
            return redirect()->to('/Fornecedores')->with('erro', 'Fornecedor não encontrado.');
        }

        $data = [
            'title' => 'Editar Fornecedor',
            'usuario' => $this->usuario,
            'fornecedor_data' => $fornecedor_data,
        ];

        echo view('Commons/header');
        echo view('Fornecedores/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo fornecedor
     */
    public function salvar()
    {
        // Validação de CNPJ/CPF
        $cnpj = preg_replace('/[^0-9]/', '', $this->request->getPost('cnpj') ?? '');
        $cpf = preg_replace('/[^0-9]/', '', $this->request->getPost('cpf') ?? '');
        
        if (!empty($cnpj) && !validar_cnpj($cnpj)) {
            return redirect()->back()->withInput()->with('erro', 'CNPJ inválido.');
        }
        
        if (!empty($cpf) && !validar_cpf($cpf)) {
            return redirect()->back()->withInput()->with('erro', 'CPF inválido.');
        }
        
        if (empty($cnpj) && empty($cpf)) {
            return redirect()->back()->withInput()->with('erro', 'Informe CNPJ ou CPF.');
        }

        $dados = [
            'id_cliente' => $this->idCliente,
            'razao_social' => $this->request->getPost('razao_social'),
            'nome_fantasia' => $this->request->getPost('nome_fantasia'),
            'cnpj' => $cnpj,
            'cpf' => $cpf,
            'inscricao_estadual' => $this->request->getPost('inscricao_estadual'),
            'telefone' => $this->request->getPost('telefone'),
            'celular' => $this->request->getPost('celular'),
            'email' => $this->request->getPost('email'),
            'cep' => preg_replace('/[^0-9]/', '', $this->request->getPost('cep') ?? ''),
            'endereco' => $this->request->getPost('endereco'),
            'numero' => $this->request->getPost('numero'),
            'complemento' => $this->request->getPost('complemento'),
            'bairro' => $this->request->getPost('bairro'),
            'cidade' => $this->request->getPost('cidade'),
            'estado' => $this->request->getPost('estado'),
            'observacoes' => $this->request->getPost('observacoes'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        if ($this->fornecedorModel->insert($dados)) {
            return redirect()->to('/Fornecedores')->with('sucesso', 'Fornecedor criado com sucesso!');
        } else {
            $erros = $this->fornecedorModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza fornecedor existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id_fornecedor');
        }
        
        $fornecedor_data = $this->fornecedorModel->getFornecedor($id);
        
        if (!$fornecedor_data) {
            return redirect()->to('/Fornecedores')->with('erro', 'Fornecedor não encontrado.');
        }

        // Validação de CNPJ/CPF
        $cnpj = preg_replace('/[^0-9]/', '', $this->request->getPost('cnpj') ?? '');
        $cpf = preg_replace('/[^0-9]/', '', $this->request->getPost('cpf') ?? '');
        
        if (!empty($cnpj) && !validar_cnpj($cnpj)) {
            return redirect()->back()->withInput()->with('erro', 'CNPJ inválido.');
        }
        
        if (!empty($cpf) && !validar_cpf($cpf)) {
            return redirect()->back()->withInput()->with('erro', 'CPF inválido.');
        }
        
        if (empty($cnpj) && empty($cpf)) {
            return redirect()->back()->withInput()->with('erro', 'Informe CNPJ ou CPF.');
        }

        $dados = [
            'razao_social' => $this->request->getPost('razao_social'),
            'nome_fantasia' => $this->request->getPost('nome_fantasia'),
            'cnpj' => $cnpj,
            'cpf' => $cpf,
            'inscricao_estadual' => $this->request->getPost('inscricao_estadual'),
            'telefone' => $this->request->getPost('telefone'),
            'celular' => $this->request->getPost('celular'),
            'email' => $this->request->getPost('email'),
            'cep' => preg_replace('/[^0-9]/', '', $this->request->getPost('cep') ?? ''),
            'endereco' => $this->request->getPost('endereco'),
            'numero' => $this->request->getPost('numero'),
            'complemento' => $this->request->getPost('complemento'),
            'bairro' => $this->request->getPost('bairro'),
            'cidade' => $this->request->getPost('cidade'),
            'estado' => $this->request->getPost('estado'),
            'observacoes' => $this->request->getPost('observacoes'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        if ($this->fornecedorModel->update($id, $dados)) {
            return redirect()->to('/Fornecedores')->with('sucesso', 'Fornecedor atualizado com sucesso!');
        } else {
            $erros = $this->fornecedorModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui fornecedor (soft delete)
     */
    public function excluir($id)
    {
        $fornecedor_data = $this->fornecedorModel->getFornecedor($id);

        if (!$fornecedor_data) {
            return redirect()->to('/Fornecedores')->with('erro', 'Fornecedor não encontrado.');
        }

        if ($this->fornecedorModel->delete($id)) {
            return redirect()->to('/Fornecedores')->with('sucesso', 'Fornecedor excluído com sucesso!');
        } else {
            return redirect()->to('/Fornecedores')->with('erro', 'Erro ao excluir fornecedor.');
        }
    }

    /**
     * Busca CEP via API ViaCEP
     */
    public function buscarCep()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->request->getGet('cep') ?? '');
        
        if (strlen($cep) != 8) {
            return $this->response->setJSON([
                'erro' => true,
                'mensagem' => 'CEP inválido'
            ]);
        }
        
        // Busca CEP na API ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $response) {
            $data = json_decode($response, true);
            
            if (isset($data['erro'])) {
                return $this->response->setJSON([
                    'erro' => true,
                    'mensagem' => 'CEP não encontrado'
                ]);
            }
            
            return $this->response->setJSON([
                'erro' => false,
                'endereco' => $data['logradouro'] ?? '',
                'bairro' => $data['bairro'] ?? '',
                'cidade' => $data['localidade'] ?? '',
                'estado' => $data['uf'] ?? '',
                'complemento' => $data['complemento'] ?? ''
            ]);
        }
        
        return $this->response->setJSON([
            'erro' => true,
            'mensagem' => 'Erro ao buscar CEP'
        ]);
    }
}
