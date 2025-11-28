<?php namespace App\Controllers;

use App\Models\CategoriaProdutoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class CategoriasProduto extends BaseController
{
    protected $categoriaModel;
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
        $this->categoriaModel = new CategoriaProdutoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todas as categorias
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
        ];

        $categorias = $this->categoriaModel->getCategorias($filtros);
        
        $data = [
            'title' => 'Categorias de Produtos',
            'usuario' => $this->usuario,
            'categorias' => $categorias,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('CategoriasProduto/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $data = [
            'title' => 'Nova Categoria',
            'usuario' => $this->usuario,
            'categoria_data' => null,
        ];

        echo view('Commons/header');
        echo view('CategoriasProduto/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $categoria_data = $this->categoriaModel->getCategoria($id);
        
        if (!$categoria_data) {
            return redirect()->to('/CategoriasProduto')->with('erro', 'Categoria não encontrada.');
        }

        $data = [
            'title' => 'Editar Categoria',
            'usuario' => $this->usuario,
            'categoria_data' => $categoria_data,
        ];

        echo view('Commons/header');
        echo view('CategoriasProduto/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva nova categoria
     */
    public function salvar()
    {
        $dados = [
            'id_cliente' => $this->idCliente,
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'cor' => $this->request->getPost('cor'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        // Verifica se já existe categoria com mesmo nome
        $existe = $this->categoriaModel->getCategoriaPorNome($dados['nome']);
        if ($existe) {
            return redirect()->back()->withInput()->with('erro', 'Já existe uma categoria com este nome.');
        }

        if ($this->categoriaModel->insert($dados)) {
            return redirect()->to('/CategoriasProduto')->with('sucesso', 'Categoria criada com sucesso!');
        } else {
            $erros = $this->categoriaModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza categoria existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id_categoria');
        }
        
        $categoria_data = $this->categoriaModel->getCategoria($id);
        
        if (!$categoria_data) {
            return redirect()->to('/CategoriasProduto')->with('erro', 'Categoria não encontrada.');
        }

        $dados = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'cor' => $this->request->getPost('cor'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        // Verifica se já existe outra categoria com mesmo nome
        $existe = $this->categoriaModel->getCategoriaPorNome($dados['nome']);
        if ($existe && $existe['id_categoria'] != $id) {
            return redirect()->back()->withInput()->with('erro', 'Já existe outra categoria com este nome.');
        }

        if ($this->categoriaModel->update($id, $dados)) {
            return redirect()->to('/CategoriasProduto')->with('sucesso', 'Categoria atualizada com sucesso!');
        } else {
            $erros = $this->categoriaModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui categoria
     */
    public function excluir($id)
    {
        $categoria_data = $this->categoriaModel->getCategoria($id);

        if (!$categoria_data) {
            return redirect()->to('/CategoriasProduto')->with('erro', 'Categoria não encontrada.');
        }

        // Verifica se há produtos usando esta categoria
        $produtoModel = new \App\Models\ProdutoModel();
        $produtos = $produtoModel->getProdutos(['categoria' => $categoria_data['nome']]);
        if (count($produtos) > 0) {
            return redirect()->to('/CategoriasProduto')->with('erro', 'Não é possível excluir esta categoria pois existem produtos cadastrados com ela.');
        }

        if ($this->categoriaModel->delete($id)) {
            return redirect()->to('/CategoriasProduto')->with('sucesso', 'Categoria excluída com sucesso!');
        } else {
            return redirect()->to('/CategoriasProduto')->with('erro', 'Erro ao excluir categoria.');
        }
    }
}

