<?php namespace App\Controllers;

use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use App\Models\ProdutoModel;
use App\Models\DepositoModel;
use App\Models\FornecedorModel;
use App\Models\IngredientePadraoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Estoque extends BaseController
{
    protected $estoqueModel;
    protected $movimentacaoModel;
    protected $produtoModel;
    protected $depositoModel;
    protected $fornecedorModel;
    protected $ingredientePadraoModel;
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
        $this->estoqueModel = new EstoqueModel();
        $this->movimentacaoModel = new MovimentacaoEstoqueModel();
        $this->produtoModel = new ProdutoModel();
        $this->depositoModel = new DepositoModel();
        $this->fornecedorModel = new FornecedorModel();
        $this->ingredientePadraoModel = new IngredientePadraoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista estoque
     */
    public function index()
    {
        $filtros = [
            'id_deposito' => $this->request->getGet('id_deposito'),
            'id_produto' => $this->request->getGet('id_produto'),
            'estoque_baixo' => $this->request->getGet('estoque_baixo'),
        ];

        $estoque = $this->estoqueModel->getEstoque($filtros);
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        
        $data = [
            'title' => 'Controle de Estoque',
            'usuario' => $this->usuario,
            'estoque' => $estoque,
            'depositos' => $depositos,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Estoque/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de entrada de estoque
     */
    public function entrada()
    {
        // Busca ingredientes padrão (não produtos)
        $ingredientes = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        $fornecedores = $this->fornecedorModel->getFornecedores(['ativo' => 1]);
        
        // Ingrediente pré-selecionado (se vier na URL)
        $idIngredienteSelecionado = $this->request->getGet('id_ingrediente');
        $estoqueAtual = null;
        if ($idIngredienteSelecionado) {
            // Busca produto vinculado ao ingrediente padrão
            $ingrediente = $this->ingredientePadraoModel->getIngredientePadrao($idIngredienteSelecionado);
            if ($ingrediente) {
                // Busca produto pelo código ING-{id} ou pelo nome
                $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
                    ->where('codigo', 'ING-' . $idIngredienteSelecionado)
                    ->first();
                
                if ($produtoVinculado) {
                    // Busca estoque atual do produto em todos os depósitos
                    $estoqueAtual = [];
                    foreach ($depositos as $deposito) {
                        $estoque = $this->estoqueModel->getEstoqueProdutoDeposito($produtoVinculado['id_produto'], $deposito['id_deposito']);
                        if ($estoque) {
                            $estoqueAtual[] = [
                                'deposito' => $deposito['nome'],
                                'quantidade' => $estoque['quantidade'],
                                'custo_medio' => $estoque['custo_medio'],
                            ];
                        }
                    }
                }
            }
        }
        
        $data = [
            'title' => 'Entrada de Estoque',
            'usuario' => $this->usuario,
            'ingredientes' => $ingredientes,
            'depositos' => $depositos,
            'fornecedores' => $fornecedores,
            'id_ingrediente_selecionado' => $idIngredienteSelecionado,
            'estoque_atual' => $estoqueAtual,
        ];

        echo view('Commons/header');
        echo view('Estoque/entrada', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de saída de estoque
     */
    public function saida()
    {
        // Busca ingredientes padrão (não produtos)
        $ingredientes = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        
        $data = [
            'title' => 'Saída de Estoque',
            'usuario' => $this->usuario,
            'ingredientes' => $ingredientes,
            'depositos' => $depositos,
        ];

        echo view('Commons/header');
        echo view('Estoque/saida', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de ajuste de estoque
     */
    public function ajuste()
    {
        // Busca ingredientes padrão (não produtos)
        $ingredientes = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        
        $data = [
            'title' => 'Ajuste de Estoque',
            'usuario' => $this->usuario,
            'ingredientes' => $ingredientes,
            'depositos' => $depositos,
        ];

        echo view('Commons/header');
        echo view('Estoque/ajuste', $data);
        echo view('Commons/footer');
    }

    /**
     * Registra entrada de estoque
     */
    public function registrarEntrada()
    {
        $idIngredientePadrao = $this->request->getPost('id_ingrediente_padrao');
        $idDeposito = $this->request->getPost('id_deposito');
        $quantidade = $this->request->getPost('quantidade');
        $custoUnitario = $this->request->getPost('custo_unitario');
        $idFornecedor = $this->request->getPost('id_fornecedor');
        $observacoes = $this->request->getPost('observacoes');

        if (!$idIngredientePadrao) {
            return redirect()->back()->with('erro', 'Ingrediente não selecionado!');
        }

        // Busca ingrediente padrão
        $ingrediente = $this->ingredientePadraoModel->getIngredientePadrao($idIngredientePadrao);
        if (!$ingrediente) {
            return redirect()->back()->with('erro', 'Ingrediente padrão não encontrado!');
        }

        // Converte valores
        $quantidade = floatval(str_replace(',', '.', $quantidade));
        $custoUnitario = floatval(str_replace(',', '.', str_replace('.', '', $custoUnitario)));

        // Busca ou cria produto vinculado ao ingrediente padrão
        $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
            ->where('codigo', 'ING-' . $idIngredientePadrao)
            ->first();

        if (!$produtoVinculado) {
            // Cria produto vinculado
            $produtoDados = [
                'id_cliente' => $this->idCliente,
                'codigo' => 'ING-' . $idIngredientePadrao,
                'nome' => $ingrediente['nome'],
                'categoria' => $ingrediente['categoria'],
                'unidade_medida' => $ingrediente['unidade_medida'],
                'custo_unitario' => $custoUnitario,
                'estoque_minimo' => 0,
                'eh_ingrediente' => 1,
                'controla_estoque' => 1,
                'ativo' => 1,
            ];
            
            $idProduto = $this->produtoModel->insert($produtoDados);
            if (!$idProduto) {
                return redirect()->back()->with('erro', 'Erro ao criar produto vinculado!');
            }
        } else {
            $idProduto = $produtoVinculado['id_produto'];
            // Atualiza custo unitário do produto
            $this->produtoModel->update($idProduto, [
                'custo_unitario' => $custoUnitario
            ]);
        }

        // Busca estoque atual
        $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $idDeposito);
        $quantidadeAtual = $estoqueAtual ? $estoqueAtual['quantidade'] : 0;
        $custoMedioAtual = $estoqueAtual ? $estoqueAtual['custo_medio'] : 0;

        // Calcula novo custo médio ponderado
        $valorAtual = $quantidadeAtual * $custoMedioAtual;
        $valorEntrada = $quantidade * $custoUnitario;
        $novaQuantidade = $quantidadeAtual + $quantidade;
        $novoCustoMedio = $novaQuantidade > 0 ? ($valorAtual + $valorEntrada) / $novaQuantidade : $custoUnitario;

        // Atualiza estoque
        $this->estoqueModel->atualizarEstoque($idProduto, $idDeposito, $novaQuantidade, $novoCustoMedio);

        // Registra movimentação
        $dadosMovimentacao = [
            'id_produto' => $idProduto,
            'id_deposito' => $idDeposito,
            'tipo' => 'ENTRADA',
            'quantidade' => $quantidade,
            'custo_unitario' => $custoUnitario,
        ];
        
        if ($idFornecedor) {
            $dadosMovimentacao['id_fornecedor'] = $idFornecedor;
        }
        
        $dadosMovimentacao['observacoes'] = $observacoes ? $observacoes : 'Entrada de estoque - ' . $ingrediente['nome'];
        
        $this->movimentacaoModel->registrarMovimentacao($dadosMovimentacao);

        return redirect()->to('/Estoque')->with('sucesso', 'Entrada de estoque registrada com sucesso!');
    }

    /**
     * Retorna estoque atual de um produto (AJAX)
     */
    public function getEstoqueAtual()
    {
        $idProduto = $this->request->getGet('id_produto');
        
        if (!$idProduto) {
            return $this->response->setJSON(['estoque' => []]);
        }
        
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        $estoque = [];
        
        foreach ($depositos as $deposito) {
            $estoqueItem = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $deposito['id_deposito']);
            if ($estoqueItem) {
                $estoque[] = [
                    'deposito' => $deposito['nome'],
                    'quantidade' => $estoqueItem['quantidade'],
                    'custo_medio' => $estoqueItem['custo_medio'],
                ];
            }
        }
        
        return $this->response->setJSON(['estoque' => $estoque]);
    }

    /**
     * Retorna estoque atual de um ingrediente padrão (AJAX)
     */
    public function getEstoqueAtualIngrediente()
    {
        $idIngredientePadrao = $this->request->getGet('id_ingrediente_padrao');
        
        if (!$idIngredientePadrao) {
            return $this->response->setJSON(['estoque' => []]);
        }
        
        // Busca produto vinculado ao ingrediente padrão
        $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
            ->where('codigo', 'ING-' . $idIngredientePadrao)
            ->first();
        
        if (!$produtoVinculado) {
            return $this->response->setJSON(['estoque' => []]);
        }
        
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        $estoque = [];
        
        foreach ($depositos as $deposito) {
            $estoqueItem = $this->estoqueModel->getEstoqueProdutoDeposito($produtoVinculado['id_produto'], $deposito['id_deposito']);
            if ($estoqueItem) {
                $estoque[] = [
                    'deposito' => $deposito['nome'],
                    'quantidade' => $estoqueItem['quantidade'],
                    'custo_medio' => $estoqueItem['custo_medio'],
                ];
            }
        }
        
        return $this->response->setJSON(['estoque' => $estoque]);
    }

    /**
     * Registra saída de estoque
     */
    public function registrarSaida()
    {
        $idIngredientePadrao = $this->request->getPost('id_ingrediente_padrao');
        $idDeposito = $this->request->getPost('id_deposito');
        $quantidade = $this->request->getPost('quantidade');
        $observacoes = $this->request->getPost('observacoes');

        if (!$idIngredientePadrao) {
            return redirect()->back()->with('erro', 'Ingrediente não selecionado!');
        }

        // Busca ingrediente padrão
        $ingrediente = $this->ingredientePadraoModel->getIngredientePadrao($idIngredientePadrao);
        if (!$ingrediente) {
            return redirect()->back()->with('erro', 'Ingrediente padrão não encontrado!');
        }

        // Converte quantidade
        $quantidade = floatval(str_replace(',', '.', $quantidade));

        // Busca produto vinculado ao ingrediente padrão
        $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
            ->where('codigo', 'ING-' . $idIngredientePadrao)
            ->first();

        if (!$produtoVinculado) {
            return redirect()->back()->with('erro', 'Produto vinculado ao ingrediente não encontrado!');
        }

        $idProduto = $produtoVinculado['id_produto'];

        // Busca estoque atual
        $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $idDeposito);
        
        if (!$estoqueAtual || $estoqueAtual['quantidade'] < $quantidade) {
            return redirect()->back()->with('erro', 'Estoque insuficiente!');
        }

        $novaQuantidade = $estoqueAtual['quantidade'] - $quantidade;

        // Atualiza estoque
        $this->estoqueModel->atualizarEstoque($idProduto, $idDeposito, $novaQuantidade, $estoqueAtual['custo_medio']);

        // Registra movimentação
        $this->movimentacaoModel->registrarMovimentacao([
            'id_produto' => $idProduto,
            'id_deposito' => $idDeposito,
            'tipo' => 'SAIDA',
            'quantidade' => $quantidade,
            'custo_unitario' => $estoqueAtual['custo_medio'],
            'observacoes' => $observacoes ? $observacoes : 'Saída de estoque - ' . $ingrediente['nome'],
        ]);

        return redirect()->to('/Estoque')->with('sucesso', 'Saída de estoque registrada com sucesso!');
    }

    /**
     * Registra ajuste de estoque
     */
    public function registrarAjuste()
    {
        $idIngredientePadrao = $this->request->getPost('id_ingrediente_padrao');
        $idDeposito = $this->request->getPost('id_deposito');
        $quantidade = $this->request->getPost('quantidade');
        $observacoes = $this->request->getPost('observacoes');

        if (!$idIngredientePadrao) {
            return redirect()->back()->with('erro', 'Ingrediente não selecionado!');
        }

        // Busca ingrediente padrão
        $ingrediente = $this->ingredientePadraoModel->getIngredientePadrao($idIngredientePadrao);
        if (!$ingrediente) {
            return redirect()->back()->with('erro', 'Ingrediente padrão não encontrado!');
        }

        // Converte quantidade
        $quantidade = floatval(str_replace(',', '.', $quantidade));

        // Busca produto vinculado ao ingrediente padrão
        $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
            ->where('codigo', 'ING-' . $idIngredientePadrao)
            ->first();

        if (!$produtoVinculado) {
            return redirect()->back()->with('erro', 'Produto vinculado ao ingrediente não encontrado!');
        }

        $idProduto = $produtoVinculado['id_produto'];

        // Busca estoque atual
        $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $idDeposito);
        $quantidadeAtual = $estoqueAtual ? $estoqueAtual['quantidade'] : 0;
        $diferenca = $quantidade - $quantidadeAtual;

        // Atualiza estoque
        $this->estoqueModel->atualizarEstoque($idProduto, $idDeposito, $quantidade, $estoqueAtual['custo_medio'] ?? 0);

        // Registra movimentação
        $this->movimentacaoModel->registrarMovimentacao([
            'id_produto' => $idProduto,
            'id_deposito' => $idDeposito,
            'tipo' => 'AJUSTE',
            'quantidade' => abs($diferenca),
            'custo_unitario' => $estoqueAtual['custo_medio'] ?? 0,
            'observacoes' => $observacoes ? $observacoes : 'Ajuste de estoque - ' . $ingrediente['nome'],
        ]);

        return redirect()->to('/Estoque')->with('sucesso', 'Ajuste de estoque registrado com sucesso!');
    }

    /**
     * Histórico de movimentações
     */
    public function historico()
    {
        $filtros = [
            'tipo' => $this->request->getGet('tipo'),
            'id_produto' => $this->request->getGet('id_produto'),
            'id_deposito' => $this->request->getGet('id_deposito'),
            'data_inicio' => $this->request->getGet('data_inicio'),
            'data_fim' => $this->request->getGet('data_fim'),
        ];

        $movimentacoes = $this->movimentacaoModel->getMovimentacoes($filtros);
        // Busca ingredientes padrão para o filtro
        $ingredientes = $this->ingredientePadraoModel->getIngredientesPadrao(['ativo' => 1]);
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        
        $data = [
            'title' => 'Histórico de Estoque',
            'usuario' => $this->usuario,
            'movimentacoes' => $movimentacoes,
            'ingredientes' => $ingredientes,
            'depositos' => $depositos,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Estoque/historico', $data);
        echo view('Commons/footer');
    }
}

