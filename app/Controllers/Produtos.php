<?php namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\ProdutoComposicaoModel;
use App\Models\IngredientePadraoModel;
use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Produtos extends BaseController
{
    protected $produtoModel;
    protected $composicaoModel;
    protected $ingredientePadraoModel;
    protected $estoqueModel;
    protected $movimentacaoModel;
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
        $this->produtoModel = new ProdutoModel();
        $this->composicaoModel = new ProdutoComposicaoModel();
        $this->ingredientePadraoModel = new IngredientePadraoModel();
        $this->estoqueModel = new EstoqueModel();
        $this->movimentacaoModel = new MovimentacaoEstoqueModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os produtos
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'ativo' => $this->request->getGet('ativo'),
            'categoria' => $this->request->getGet('categoria'),
            'order_by' => $this->request->getGet('order_by') ?? 'nome',
            'order_dir' => $this->request->getGet('order_dir') ?? 'ASC',
            // Mostra apenas produtos finais (não ingredientes)
            'eh_ingrediente' => 0,
        ];

        $produtos = $this->produtoModel->getProdutos($filtros);
        $categorias = $this->produtoModel->getCategorias();
        
        $data = [
            'title' => 'Produtos',
            'usuario' => $this->usuario,
            'produtos' => $produtos,
            'categorias' => $categorias,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Produtos/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $categoriasCompletas = $this->produtoModel->getCategoriasCompletas();
        $categorias = array_column($categoriasCompletas, 'nome');
        // Busca apenas produtos que são ingredientes para usar na composição
        $ingredientes = $this->produtoModel->getProdutos(['ativo' => 1, 'eh_ingrediente' => 1]);
        $ingredientesPadrao = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $categoriasIngredientes = $this->ingredientePadraoModel->getCategorias();
        
        $data = [
            'title' => 'Novo Produto',
            'usuario' => $this->usuario,
            'produto_data' => null,
            'categorias' => $categorias,
            'categorias_completas' => $categoriasCompletas,
            'ingredientes' => $ingredientes,
            'ingredientes_padrao' => $ingredientesPadrao,
            'categorias_ingredientes' => $categoriasIngredientes,
            'composicao' => [],
        ];

        echo view('Commons/header');
        echo view('Produtos/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $produto_data = $this->produtoModel->getProduto($id);
        
        if (!$produto_data) {
            return redirect()->to('/Produtos')->with('erro', 'Produto não encontrado.');
        }

        $categoriasCompletas = $this->produtoModel->getCategoriasCompletas();
        $categorias = array_column($categoriasCompletas, 'nome');
        // Busca apenas produtos que são ingredientes para usar na composição
        $ingredientes = $this->produtoModel->getProdutos(['ativo' => 1, 'eh_ingrediente' => 1]);
        $ingredientesPadrao = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $categoriasIngredientes = $this->ingredientePadraoModel->getCategorias();
        $composicao = $this->composicaoModel->getComposicao($id);

        $data = [
            'title' => 'Editar Produto',
            'usuario' => $this->usuario,
            'produto_data' => $produto_data,
            'categorias' => $categorias,
            'categorias_completas' => $categoriasCompletas,
            'ingredientes' => $ingredientes,
            'ingredientes_padrao' => $ingredientesPadrao,
            'categorias_ingredientes' => $categoriasIngredientes,
            'composicao' => $composicao,
        ];

        echo view('Commons/header');
        echo view('Produtos/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo produto
     */
    public function salvar()
    {
        // Produtos são sempre produtos finais (pizzas), não ingredientes
        $dados = [
            'id_cliente' => $this->idCliente,
            'codigo' => $this->request->getPost('codigo'),
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'categoria' => $this->request->getPost('categoria'),
            'unidade_medida' => $this->request->getPost('unidade_medida') ?? 'UN',
            'custo_unitario' => str_replace(',', '.', $this->request->getPost('custo_unitario') ?? '0'),
            'preco_venda' => str_replace(',', '.', $this->request->getPost('preco_venda') ?? '0'),
            'estoque_minimo' => str_replace(',', '.', $this->request->getPost('estoque_minimo') ?? '0'),
            'eh_ingrediente' => 0, // Produtos são sempre produtos finais
            'controla_estoque' => 0, // Produtos finais não controlam estoque
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        // Upload de imagem - COMENTADO TEMPORARIAMENTE
        /*
        $file = $this->request->getFile('imagem');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Valida tipo de arquivo
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('erro', 'Tipo de arquivo inválido. Use apenas JPG, PNG ou GIF.');
            }
            
            // Valida tamanho (2MB)
            if ($file->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('erro', 'Imagem muito grande. Tamanho máximo: 2MB.');
            }
            
            $newName = $file->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/produtos/';
            
            // Cria diretório se não existir
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($file->move($uploadPath, $newName)) {
                $dados['imagem'] = 'uploads/produtos/' . $newName;
            }
        }
        */

        $idProduto = $this->produtoModel->insert($dados);
        
        if ($idProduto) {
            // Salva composição se houver
            $composicaoJson = $this->request->getPost('composicao_json');
            if ($composicaoJson) {
                $itens = json_decode($composicaoJson, true);
                if (is_array($itens) && count($itens) > 0) {
                    $this->composicaoModel->salvarComposicao($idProduto, $itens);
                    
                    // Atualiza custo do produto baseado na composição
                    $custoTotal = $this->composicaoModel->calcularCustoTotal($idProduto);
                    $this->produtoModel->update($idProduto, ['custo_unitario' => $custoTotal]);
                }
            }
            
            return redirect()->to('/Produtos')->with('sucesso', 'Produto criado com sucesso!');
        } else {
            $erros = $this->produtoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza produto existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id_produto');
        }
        
        $produto_data = $this->produtoModel->getProduto($id);
        
        if (!$produto_data) {
            return redirect()->to('/Produtos')->with('erro', 'Produto não encontrado.');
        }

        // Produtos são sempre produtos finais (pizzas), não ingredientes
        $dados = [
            'codigo' => $this->request->getPost('codigo'),
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'categoria' => $this->request->getPost('categoria'),
            'unidade_medida' => $this->request->getPost('unidade_medida') ?? 'UN',
            'custo_unitario' => str_replace(',', '.', $this->request->getPost('custo_unitario') ?? '0'),
            'preco_venda' => str_replace(',', '.', $this->request->getPost('preco_venda') ?? '0'),
            'estoque_minimo' => str_replace(',', '.', $this->request->getPost('estoque_minimo') ?? '0'),
            'eh_ingrediente' => 0, // Produtos são sempre produtos finais
            'controla_estoque' => 0, // Produtos finais não controlam estoque
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        // Upload de imagem (se houver nova) - COMENTADO TEMPORARIAMENTE
        /*
        $file = $this->request->getFile('imagem');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Valida tipo de arquivo
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('erro', 'Tipo de arquivo inválido. Use apenas JPG, PNG ou GIF.');
            }
            
            // Valida tamanho (2MB)
            if ($file->getSize() > 2097152) {
                return redirect()->back()->withInput()->with('erro', 'Imagem muito grande. Tamanho máximo: 2MB.');
            }
            
            // Remove imagem antiga se existir
            if (!empty($produto_data['imagem'])) {
                $oldImagePath = WRITEPATH . $produto_data['imagem'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $newName = $file->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/produtos/';
            
            // Cria diretório se não existir
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($file->move($uploadPath, $newName)) {
                $dados['imagem'] = 'uploads/produtos/' . $newName;
            }
        } elseif ($this->request->getPost('remover_imagem')) {
            // Remove imagem se solicitado
            if (!empty($produto_data['imagem'])) {
                $oldImagePath = WRITEPATH . $produto_data['imagem'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $dados['imagem'] = null;
            }
        }
        */

        if ($this->produtoModel->update($id, $dados)) {
            // Salva composição se houver
            $composicaoJson = $this->request->getPost('composicao_json');
            if ($composicaoJson) {
                $itens = json_decode($composicaoJson, true);
                if (is_array($itens)) {
                    $this->composicaoModel->salvarComposicao($id, $itens);
                    
                    // Atualiza custo do produto baseado na composição
                    $custoTotal = $this->composicaoModel->calcularCustoTotal($id);
                    $this->produtoModel->update($id, ['custo_unitario' => $custoTotal]);
                }
            } else {
                // Se não tem composição, remove a antiga
                $this->composicaoModel->removerComposicao($id);
            }
            
            return redirect()->to('/Produtos')->with('sucesso', 'Produto atualizado com sucesso!');
        } else {
            $erros = $this->produtoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui produto (soft delete)
     */
    public function excluir($id)
    {
        $produto_data = $this->produtoModel->getProduto($id);

        if (!$produto_data) {
            return redirect()->to('/Produtos')->with('erro', 'Produto não encontrado.');
        }

        if ($this->produtoModel->delete($id)) {
            return redirect()->to('/Produtos')->with('sucesso', 'Produto excluído com sucesso!');
        } else {
            return redirect()->to('/Produtos')->with('erro', 'Erro ao excluir produto.');
        }
    }
}
