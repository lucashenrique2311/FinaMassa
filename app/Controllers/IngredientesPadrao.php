<?php namespace App\Controllers;

use App\Models\IngredientePadraoModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use App\Models\DepositoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class IngredientesPadrao extends BaseController
{
    protected $ingredientePadraoModel;
    protected $produtoModel;
    protected $estoqueModel;
    protected $movimentacaoModel;
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
        $this->ingredientePadraoModel = new IngredientePadraoModel();
        $this->produtoModel = new ProdutoModel();
        $this->estoqueModel = new EstoqueModel();
        $this->movimentacaoModel = new MovimentacaoEstoqueModel();
        $this->depositoModel = new DepositoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os ingredientes padrão
     */
    public function index()
    {
        $filtros = [
            'ativo' => $this->request->getGet('ativo'),
            'categoria' => $this->request->getGet('categoria'),
        ];

        $ingredientes = $this->ingredientePadraoModel->getIngredientesPadrao($filtros);
        $categorias = $this->ingredientePadraoModel->getCategorias();
        
        // Busca estoque atual de cada ingrediente
        $estoqueModel = new \App\Models\EstoqueModel();
        $depositoModel = new \App\Models\DepositoModel();
        $depositos = $depositoModel->getDepositos(['ativo' => 1]);
        
        foreach ($ingredientes as &$ingrediente) {
            // Busca produto vinculado ao ingrediente padrão
            $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
                ->where('codigo', 'ING-' . $ingrediente['id_ingrediente_padrao'])
                ->first();
            
            $estoqueTotal = 0;
            $custoMedioTotal = 0;
            $depositosComEstoque = [];
            
            if ($produtoVinculado) {
                // Soma estoque de todos os depósitos
                foreach ($depositos as $deposito) {
                    $estoque = $estoqueModel->getEstoqueProdutoDeposito($produtoVinculado['id_produto'], $deposito['id_deposito']);
                    if ($estoque && $estoque['quantidade'] > 0) {
                        $estoqueTotal += $estoque['quantidade'];
                        $depositosComEstoque[] = [
                            'deposito' => $deposito['nome'],
                            'quantidade' => $estoque['quantidade'],
                            'custo_medio' => $estoque['custo_medio'],
                        ];
                    }
                }
                
                // Calcula custo médio ponderado
                if ($estoqueTotal > 0) {
                    $valorTotal = 0;
                    foreach ($depositosComEstoque as $dep) {
                        $valorTotal += $dep['quantidade'] * $dep['custo_medio'];
                    }
                    $custoMedioTotal = $valorTotal / $estoqueTotal;
                }
            }
            
            $ingrediente['estoque_total'] = $estoqueTotal;
            $ingrediente['custo_medio_total'] = $custoMedioTotal;
            $ingrediente['depositos_estoque'] = $depositosComEstoque;
        }
        unset($ingrediente);
        
        $data = [
            'title' => 'Ingredientes Padrão',
            'usuario' => $this->usuario,
            'ingredientes' => $ingredientes,
            'categorias' => $categorias,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('IngredientesPadrao/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function criar()
    {
        $categorias = $this->ingredientePadraoModel->getCategorias();
        
        $data = [
            'title' => 'Novo Ingrediente Padrão',
            'usuario' => $this->usuario,
            'ingrediente_data' => null,
            'categorias' => $categorias,
        ];

        echo view('Commons/header');
        echo view('IngredientesPadrao/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de edição
     */
    public function editar($id)
    {
        $ingrediente_data = $this->ingredientePadraoModel->getIngredientePadrao($id);
        
        if (!$ingrediente_data) {
            return redirect()->to('/IngredientesPadrao')->with('erro', 'Ingrediente padrão não encontrado.');
        }

        $categorias = $this->ingredientePadraoModel->getCategorias();

        $data = [
            'title' => 'Editar Ingrediente Padrão',
            'usuario' => $this->usuario,
            'ingrediente_data' => $ingrediente_data,
            'categorias' => $categorias,
        ];

        echo view('Commons/header');
        echo view('IngredientesPadrao/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo ingrediente padrão
     */
    public function salvar()
    {
        $quantidadeInicial = $this->request->getPost('quantidade_inicial');
        if ($quantidadeInicial) {
            // Remove pontos de milhar e substitui vírgula por ponto
            $quantidadeInicial = str_replace('.', '', $quantidadeInicial); // Remove separadores de milhar
            $quantidadeInicial = str_replace(',', '.', $quantidadeInicial); // Converte vírgula decimal para ponto
            $quantidadeInicial = floatval($quantidadeInicial);
        } else {
            $quantidadeInicial = 0.000;
        }
        
        $dados = [
            'id_cliente' => $this->idCliente,
            'nome' => $this->request->getPost('nome'),
            'categoria' => $this->request->getPost('categoria'),
            'unidade_medida' => $this->request->getPost('unidade_medida') ?? 'UN',
            'custo_padrao' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('custo_padrao') ?? '0')),
            'quantidade_inicial' => $quantidadeInicial,
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        $idIngredientePadrao = $this->ingredientePadraoModel->insert($dados);
        
        if ($idIngredientePadrao) {
            // Se tem quantidade inicial, faz entrada automática no estoque
            if ($quantidadeInicial > 0) {
                $this->registrarEntradaInicial($idIngredientePadrao, $quantidadeInicial, $dados['custo_padrao']);
            }
            
            return redirect()->to('/IngredientesPadrao')->with('sucesso', 'Ingrediente padrão criado com sucesso!');
        } else {
            $erros = $this->ingredientePadraoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza ingrediente padrão existente
     */
    public function atualizar($id = null)
    {
        if (!$id) {
            $id = $this->request->getPost('id_ingrediente_padrao');
        }
        
        $ingrediente_data = $this->ingredientePadraoModel->getIngredientePadrao($id);
        
        if (!$ingrediente_data) {
            return redirect()->to('/IngredientesPadrao')->with('erro', 'Ingrediente padrão não encontrado.');
        }

        $quantidadeInicial = $this->request->getPost('quantidade_inicial');
        if ($quantidadeInicial) {
            // Remove pontos de milhar e substitui vírgula por ponto
            $quantidadeInicial = str_replace('.', '', $quantidadeInicial); // Remove separadores de milhar
            $quantidadeInicial = str_replace(',', '.', $quantidadeInicial); // Converte vírgula decimal para ponto
            $quantidadeInicial = floatval($quantidadeInicial);
        } else {
            $quantidadeInicial = 0.000;
        }
        
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'categoria' => $this->request->getPost('categoria'),
            'unidade_medida' => $this->request->getPost('unidade_medida') ?? 'UN',
            'custo_padrao' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('custo_padrao') ?? '0')),
            'quantidade_inicial' => $quantidadeInicial,
            'ativo' => $this->request->getPost('ativo') ? 1 : 0,
        ];

        if ($this->ingredientePadraoModel->update($id, $dados)) {
            // Se quantidade inicial foi alterada e é maior que zero, verifica se precisa fazer entrada
            $quantidadeAnterior = $ingrediente_data['quantidade_inicial'] ?? 0;
            if ($quantidadeInicial > 0 && $quantidadeInicial != $quantidadeAnterior) {
                // Busca estoque atual do produto vinculado
                $produtoVinculado = $this->produtoModel->where('id_cliente', $this->idCliente)
                    ->where('codigo', 'ING-' . $id)
                    ->first();
                
                if ($produtoVinculado) {
                    // Busca depósitos ativos
                    $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
                    if (!empty($depositos)) {
                        $idDeposito = $depositos[0]['id_deposito'];
                        $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($produtoVinculado['id_produto'], $idDeposito);
                        $quantidadeAtual = $estoqueAtual ? $estoqueAtual['quantidade'] : 0;
                        
                        // Se a quantidade inicial é maior que o estoque atual, faz entrada da diferença
                        if ($quantidadeInicial > $quantidadeAtual) {
                            $quantidadeEntrada = $quantidadeInicial - $quantidadeAtual;
                            $this->registrarEntradaInicial($id, $quantidadeEntrada, $dados['custo_padrao'], $produtoVinculado['id_produto']);
                        }
                    }
                } else {
                    // Se não tem produto vinculado, cria e faz entrada
                    $this->registrarEntradaInicial($id, $quantidadeInicial, $dados['custo_padrao']);
                }
            }
            
            return redirect()->to('/IngredientesPadrao')->with('sucesso', 'Ingrediente padrão atualizado com sucesso!');
        } else {
            $erros = $this->ingredientePadraoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Exclui ingrediente padrão
     */
    public function excluir($id)
    {
        $ingrediente_data = $this->ingredientePadraoModel->getIngredientePadrao($id);

        if (!$ingrediente_data) {
            return redirect()->to('/IngredientesPadrao')->with('erro', 'Ingrediente padrão não encontrado.');
        }

        // Garante que está excluindo apenas do cliente correto
        $builder = $this->ingredientePadraoModel->builder();
        $builder->where('id_ingrediente_padrao', $id);
        $builder->where('id_cliente', $this->idCliente);
        
        $resultado = $builder->delete();

        if ($resultado) {
            return redirect()->to('/IngredientesPadrao')->with('sucesso', 'Ingrediente padrão excluído com sucesso!');
        } else {
            return redirect()->to('/IngredientesPadrao')->with('erro', 'Erro ao excluir ingrediente padrão.');
        }
    }

    /**
     * Registra entrada inicial no estoque
     */
    private function registrarEntradaInicial($idIngredientePadrao, $quantidade, $custoPadrao, $idProdutoExistente = null)
    {
        // Busca ingrediente padrão
        $ingrediente = $this->ingredientePadraoModel->getIngredientePadrao($idIngredientePadrao);
        if (!$ingrediente) {
            return false;
        }

        // Busca depósitos ativos
        $depositos = $this->depositoModel->getDepositos(['ativo' => 1]);
        if (empty($depositos)) {
            // Se não tem depósito, não pode fazer entrada
            return false;
        }
        
        // Usa o primeiro depósito ativo
        $idDeposito = $depositos[0]['id_deposito'];
        $custoUnitario = floatval($custoPadrao);

        // Busca ou cria produto vinculado
        if ($idProdutoExistente) {
            $idProduto = $idProdutoExistente;
        } else {
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
                    return false;
                }
            } else {
                $idProduto = $produtoVinculado['id_produto'];
            }
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
        $this->movimentacaoModel->registrarMovimentacao([
            'id_produto' => $idProduto,
            'id_deposito' => $idDeposito,
            'tipo' => 'ENTRADA',
            'quantidade' => $quantidade,
            'custo_unitario' => $custoUnitario,
            'observacoes' => 'Entrada inicial automática - ' . $ingrediente['nome'],
        ]);

        return true;
    }
}

