<?php namespace App\Controllers;

use App\Models\PedidoVendaModel;
use App\Models\ItemPedidoModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use App\Models\ProdutoComposicaoModel;
use App\Models\IngredientePadraoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Pedidos extends BaseController
{
    protected $pedidoModel;
    protected $itemPedidoModel;
    protected $produtoModel;
    protected $estoqueModel;
    protected $movimentacaoModel;
    protected $composicaoModel;
    protected $ingredientePadraoModel;
    protected $session;
    protected $usuario;
    protected $idCliente;
    protected $idUsuario;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->usuario = $this->session->get('dadoslogin');
        $this->pedidoModel = new PedidoVendaModel();
        $this->itemPedidoModel = new ItemPedidoModel();
        $this->produtoModel = new ProdutoModel();
        $this->estoqueModel = new EstoqueModel();
        $this->movimentacaoModel = new MovimentacaoEstoqueModel();
        $this->composicaoModel = new ProdutoComposicaoModel();
        $this->ingredientePadraoModel = new IngredientePadraoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        $this->idUsuario = $this->usuario['ID_USUARIO'] ?? $this->usuario['id_usuario'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
    }

    /**
     * Lista todos os pedidos
     */
    public function index()
    {
        $filtros = [
            'busca' => $this->request->getGet('busca'),
            'status' => $this->request->getGet('status'),
            'tipo_pedido' => $this->request->getGet('tipo_pedido'),
            'data_inicio' => $this->request->getGet('data_inicio'),
            'data_fim' => $this->request->getGet('data_fim'),
        ];

        $pedidos = $this->pedidoModel->getPedidos($filtros);
        
        $data = [
            'title' => 'Pedidos de Venda',
            'usuario' => $this->usuario,
            'pedidos' => $pedidos,
            'filtros' => $filtros,
        ];

        echo view('Commons/header');
        echo view('Pedidos/index', $data);
        echo view('Commons/footer');
    }

    /**
     * Exibe formulário de criação
     */
    public function novo()
    {
        // Busca apenas produtos finais (não ingredientes)
        $produtos = $this->produtoModel->getProdutos(['ativo' => 1, 'eh_ingrediente' => 0]);
        
        $data = [
            'title' => 'Novo Pedido',
            'usuario' => $this->usuario,
            'produtos' => $produtos,
            'pedido_data' => null,
        ];

        echo view('Commons/header');
        echo view('Pedidos/form', $data);
        echo view('Commons/footer');
    }

    /**
     * Busca dados do cliente por nome ou telefone
     */
    public function buscarCliente()
    {
        $nome = $this->request->getGet('nome');
        $telefone = $this->request->getGet('telefone');
        
        if (empty($nome) && empty($telefone)) {
            return $this->response->setJSON(['encontrado' => false]);
        }
        
        $db = \Config\Database::connect();
        $builder = $db->table('pedidos_venda');
        $builder->select('cliente_nome, cliente_telefone, cliente_endereco');
        $builder->where('id_cliente', $this->idCliente);
        $builder->where('deleted_at', null);
        
        if (!empty($nome) && !empty($telefone)) {
            // Busca por ambos
            $builder->groupStart();
            $builder->like('cliente_nome', $nome);
            $telefoneLimpo = preg_replace('/\D/', '', $telefone);
            $builder->orWhere("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cliente_telefone, '(', ''), ')', ''), ' ', ''), '-', ''), '.', '')", $telefoneLimpo);
            $builder->groupEnd();
        } elseif (!empty($nome)) {
            // Busca apenas por nome
            $builder->like('cliente_nome', $nome);
        } elseif (!empty($telefone)) {
            // Busca apenas por telefone
            $telefoneLimpo = preg_replace('/\D/', '', $telefone);
            $builder->where("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cliente_telefone, '(', ''), ')', ''), ' ', ''), '-', ''), '.', '')", $telefoneLimpo);
        }
        
        $builder->orderBy('data_pedido', 'DESC');
        $builder->limit(1);
        
        $pedido = $builder->get()->getRowArray();
        
        if ($pedido) {
            return $this->response->setJSON([
                'encontrado' => true,
                'cliente_nome' => $pedido['cliente_nome'],
                'cliente_telefone' => $pedido['cliente_telefone'],
                'cliente_endereco' => $pedido['cliente_endereco'] ?? ''
            ]);
        }
        
        return $this->response->setJSON(['encontrado' => false]);
    }

    /**
     * Visualiza pedido
     */
    public function visualizar($id)
    {
        $pedido = $this->pedidoModel->getPedido($id);
        
        if (!$pedido) {
            return redirect()->to('/Pedidos')->with('erro', 'Pedido não encontrado.');
        }

        $itens = $this->itemPedidoModel->getItensPedido($id);
        
        $data = [
            'title' => 'Visualizar Pedido',
            'usuario' => $this->usuario,
            'pedido' => $pedido,
            'itens' => $itens,
        ];

        echo view('Commons/header');
        echo view('Pedidos/visualizar', $data);
        echo view('Commons/footer');
    }

    /**
     * Salva novo pedido
     */
    public function salvar()
    {
        $dados = [
            'id_cliente' => $this->idCliente,
            'data_pedido' => $this->request->getPost('data_pedido') ?: date('Y-m-d H:i:s'),
            'cliente_nome' => $this->request->getPost('cliente_nome'),
            'cliente_telefone' => $this->request->getPost('cliente_telefone'),
            'cliente_endereco' => $this->request->getPost('cliente_endereco'),
            'tipo_pedido' => $this->request->getPost('tipo_pedido') ?: 'BALCAO',
            'status' => $this->request->getPost('status') ?: 'ABERTO',
            'subtotal' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('subtotal') ?? '0')),
            'desconto' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('desconto') ?? '0')),
            'taxa_entrega' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('taxa_entrega') ?? '0')),
            'total' => str_replace(',', '.', str_replace('.', '', $this->request->getPost('total') ?? '0')),
            'forma_pagamento' => $this->request->getPost('forma_pagamento'),
            'observacoes' => $this->request->getPost('observacoes'),
            'id_usuario' => $this->idUsuario,
        ];

        $idPedido = $this->pedidoModel->insert($dados);
        
        if ($idPedido) {
            // Salva itens do pedido
            $itens = json_decode($this->request->getPost('itens') ?? '[]', true);
            foreach ($itens as $item) {
                // Busca nome do produto se tiver ID
                $nomeProduto = '';
                if (!empty($item['id_produto'])) {
                    $produto = $this->produtoModel->getProduto($item['id_produto']);
                    $nomeProduto = $produto['nome'] ?? '';
                }
                
                // Busca nome do produto meio a meio se tiver
                $nomeProdutoMeioAMeio = '';
                if (!empty($item['id_produto_meio_a_meio'])) {
                    $produtoMeioAMeio = $this->produtoModel->getProduto($item['id_produto_meio_a_meio']);
                    $nomeProdutoMeioAMeio = $produtoMeioAMeio['nome'] ?? '';
                }
                
                // Prepara dados para inserção usando query builder diretamente
                $db = \Config\Database::connect();
                $builder = $db->table('itens_pedido');
                
                $dadosItem = [
                    'id_pedido' => intval($idPedido),
                    'id_produto' => !empty($item['id_produto']) ? intval($item['id_produto']) : null,
                    'nome_produto' => $nomeProduto ?: '',
                    'quantidade' => floatval(str_replace(',', '.', $item['quantidade'] ?? '1')),
                    'preco_unitario' => floatval(str_replace(',', '.', str_replace('.', '', $item['preco_unitario'] ?? '0'))),
                    'desconto' => 0.00,
                    'subtotal' => floatval(str_replace(',', '.', str_replace('.', '', $item['subtotal'] ?? '0'))),
                ];
                
                // Campos opcionais - converte strings vazias para null explicitamente
                $idMeioAMeio = $item['id_produto_meio_a_meio'] ?? null;
                if ($idMeioAMeio === '' || $idMeioAMeio === null || empty($idMeioAMeio)) {
                    $dadosItem['id_produto_meio_a_meio'] = null;
                } else {
                    $dadosItem['id_produto_meio_a_meio'] = intval($idMeioAMeio);
                }
                
                if (empty($nomeProdutoMeioAMeio) || $nomeProdutoMeioAMeio === '') {
                    $dadosItem['nome_produto_meio_a_meio'] = null;
                } else {
                    $dadosItem['nome_produto_meio_a_meio'] = $nomeProdutoMeioAMeio;
                }
                
                $observacoes = $item['observacoes'] ?? null;
                if (empty($observacoes) || trim($observacoes) === '') {
                    $dadosItem['observacoes'] = null;
                } else {
                    $dadosItem['observacoes'] = $observacoes;
                }
                
                // Usa query builder diretamente para ter controle total
                $builder->insert($dadosItem);
            }
            
            // Baixa estoque dos ingredientes ao criar o pedido
            $depositos = (new \App\Models\DepositoModel())->getDepositos(['ativo' => 1]);
            
            if (!empty($depositos)) {
                $idDeposito = $depositos[0]['id_deposito'];
                $pedido = $this->pedidoModel->getPedido($idPedido);
                
                foreach ($itens as $item) {
                    $idProduto = $item['id_produto'] ?? null;
                    $idProdutoMeioAMeio = $item['id_produto_meio_a_meio'] ?? null;
                    $quantidadeProduto = floatval(str_replace(',', '.', $item['quantidade'] ?? '1'));
                    
                    // Verifica se é meio a meio
                    $ehMeioAMeio = !empty($idProdutoMeioAMeio);
                    
                    // Se for meio a meio, divide a quantidade pela metade para cada sabor
                    $fatorDivisao = $ehMeioAMeio ? 0.5 : 1.0;
                    
                    // Processa o primeiro produto (ou único se não for meio a meio)
                    if ($idProduto) {
                        $this->processarBaixaEstoque($idProduto, $quantidadeProduto * $fatorDivisao, $idDeposito, $idPedido, $pedido, $item, $ehMeioAMeio ? ' (1º sabor)' : '');
                    }
                    
                    // Se for meio a meio, processa o segundo produto também
                    if ($ehMeioAMeio && $idProdutoMeioAMeio) {
                        $this->processarBaixaEstoque($idProdutoMeioAMeio, $quantidadeProduto * $fatorDivisao, $idDeposito, $idPedido, $pedido, $item, ' (2º sabor)');
                    }
                }
            }
            
            return redirect()->to('/Pedidos')->with('sucesso', 'Pedido criado com sucesso!');
        } else {
            $erros = $this->pedidoModel->errors();
            return redirect()->back()->withInput()->with('erros', $erros);
        }
    }

    /**
     * Atualiza status do pedido
     */
    public function atualizarStatus($id)
    {
        $pedido = $this->pedidoModel->getPedido($id);
        
        if (!$pedido) {
            return redirect()->to('/Pedidos')->with('erro', 'Pedido não encontrado.');
        }

        $novoStatus = $this->request->getPost('status');
        
        if ($this->pedidoModel->update($id, ['status' => $novoStatus])) {
            // Estoque já foi descontado na criação do pedido, não precisa descontar novamente
            return redirect()->to('/Pedidos')->with('sucesso', 'Status do pedido atualizado com sucesso!');
        } else {
            return redirect()->to('/Pedidos')->with('erro', 'Erro ao atualizar status do pedido.');
        }
    }

    /**
     * Processa baixa de estoque de um produto
     */
    private function processarBaixaEstoque($idProduto, $quantidadeProduto, $idDeposito, $idPedido, $pedido, $item, $sufixo = '')
    {
        if (!$idProduto) {
            return;
        }
        
        // Busca composição do produto (ingredientes)
        $composicao = $this->composicaoModel->getComposicao($idProduto);
        
        if (empty($composicao)) {
            // Se não tem composição, pode ser um produto simples (ingrediente direto)
            // Verifica se o próprio produto é um ingrediente
            $produto = $this->produtoModel->getProduto($idProduto);
            if ($produto && $produto['eh_ingrediente']) {
                $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $idDeposito);
                
                if ($estoqueAtual && $estoqueAtual['quantidade'] >= $quantidadeProduto) {
                    $novaQuantidade = $estoqueAtual['quantidade'] - $quantidadeProduto;
                    $this->estoqueModel->atualizarEstoque($idProduto, $idDeposito, $novaQuantidade, $estoqueAtual['custo_medio']);
                    
                    $this->movimentacaoModel->registrarMovimentacao([
                        'id_produto' => $idProduto,
                        'id_deposito' => $idDeposito,
                        'tipo' => 'SAIDA',
                        'quantidade' => $quantidadeProduto,
                        'custo_unitario' => $estoqueAtual['custo_medio'],
                        'id_pedido_venda' => $idPedido,
                        'observacoes' => 'Saída por pedido #' . $pedido['numero_pedido'] . $sufixo,
                    ]);
                }
            }
        } else {
            // Tem composição - baixa estoque de cada ingrediente proporcionalmente
            foreach ($composicao as $ingrediente) {
                // Quantidade do ingrediente = quantidade_na_composicao * quantidade_do_produto_vendido
                $quantidadeIngrediente = floatval($ingrediente['quantidade']) * $quantidadeProduto;
                $idIngrediente = $ingrediente['id_ingrediente'];
                $nomeIngrediente = $ingrediente['nome_ingrediente'] ?? null;
                
                // Se tem id_ingrediente, é um produto cadastrado
                if ($idIngrediente) {
                    $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($idIngrediente, $idDeposito);
                    
                    if ($estoqueAtual && $estoqueAtual['quantidade'] >= $quantidadeIngrediente) {
                        $novaQuantidade = $estoqueAtual['quantidade'] - $quantidadeIngrediente;
                        $this->estoqueModel->atualizarEstoque($idIngrediente, $idDeposito, $novaQuantidade, $estoqueAtual['custo_medio']);
                        
                        $this->movimentacaoModel->registrarMovimentacao([
                            'id_produto' => $idIngrediente,
                            'id_deposito' => $idDeposito,
                            'tipo' => 'SAIDA',
                            'quantidade' => $quantidadeIngrediente,
                            'custo_unitario' => $estoqueAtual['custo_medio'],
                            'id_pedido_venda' => $idPedido,
                            'observacoes' => 'Saída por pedido #' . $pedido['numero_pedido'] . ' - Produto: ' . ($item['nome_produto'] ?? '') . $sufixo,
                        ]);
                    }
                } elseif ($nomeIngrediente) {
                    // É um ingrediente padrão - busca primeiro o ingrediente padrão pelo nome
                    $ingredientePadrao = $this->ingredientePadraoModel->where('id_cliente', $this->idCliente)
                        ->where('nome', $nomeIngrediente)
                        ->first();
                    
                    if ($ingredientePadrao) {
                        // Busca o produto vinculado pelo código ING-{id_ingrediente_padrao}
                        $produtoIngrediente = $this->produtoModel->where('id_cliente', $this->idCliente)
                            ->where('codigo', 'ING-' . $ingredientePadrao['id_ingrediente_padrao'])
                            ->where('eh_ingrediente', 1)
                            ->first();
                        
                        if ($produtoIngrediente) {
                            $estoqueAtual = $this->estoqueModel->getEstoqueProdutoDeposito($produtoIngrediente['id_produto'], $idDeposito);
                            
                            if ($estoqueAtual && $estoqueAtual['quantidade'] >= $quantidadeIngrediente) {
                                $novaQuantidade = $estoqueAtual['quantidade'] - $quantidadeIngrediente;
                                $this->estoqueModel->atualizarEstoque($produtoIngrediente['id_produto'], $idDeposito, $novaQuantidade, $estoqueAtual['custo_medio']);
                                
                                $this->movimentacaoModel->registrarMovimentacao([
                                    'id_produto' => $produtoIngrediente['id_produto'],
                                    'id_deposito' => $idDeposito,
                                    'tipo' => 'SAIDA',
                                    'quantidade' => $quantidadeIngrediente,
                                    'custo_unitario' => $estoqueAtual['custo_medio'],
                                    'id_pedido_venda' => $idPedido,
                                    'observacoes' => 'Saída por pedido #' . $pedido['numero_pedido'] . ' - Produto: ' . ($item['nome_produto'] ?? '') . ' - Ingrediente: ' . $nomeIngrediente . $sufixo,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Altera status de múltiplos pedidos
     */
    public function alterarStatusLote()
    {
        $ids = $this->request->getPost('ids');
        $novoStatus = $this->request->getPost('status');
        
        if (empty($ids) || !is_array($ids)) {
            return redirect()->to('/Pedidos')->with('erro', 'Nenhum pedido selecionado.');
        }
        
        if (empty($novoStatus)) {
            return redirect()->to('/Pedidos')->with('erro', 'Status não informado.');
        }
        
        $atualizados = 0;
        foreach ($ids as $id) {
            $id = intval($id);
            $pedido = $this->pedidoModel->getPedido($id);
            
            if ($pedido && $this->pedidoModel->update($id, ['status' => $novoStatus])) {
                $atualizados++;
            }
        }
        
        if ($atualizados > 0) {
            return redirect()->to('/Pedidos')->with('sucesso', "Status de {$atualizados} pedido(s) atualizado(s) com sucesso!");
        } else {
            return redirect()->to('/Pedidos')->with('erro', 'Nenhum pedido foi atualizado.');
        }
    }

    /**
     * Exclui múltiplos pedidos (soft delete)
     */
    public function excluirLote()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids) || !is_array($ids)) {
            return redirect()->to('/Pedidos')->with('erro', 'Nenhum pedido selecionado.');
        }
        
        $excluidos = 0;
        foreach ($ids as $id) {
            $id = intval($id);
            $pedido = $this->pedidoModel->getPedido($id);
            
            if ($pedido && $this->pedidoModel->delete($id)) {
                $excluidos++;
            }
        }
        
        if ($excluidos > 0) {
            return redirect()->to('/Pedidos')->with('sucesso', "{$excluidos} pedido(s) excluído(s) com sucesso!");
        } else {
            return redirect()->to('/Pedidos')->with('erro', 'Nenhum pedido foi excluído.');
        }
    }

    /**
     * Exclui pedido (soft delete)
     */
    public function excluir($id)
    {
        $pedido = $this->pedidoModel->getPedido($id);

        if (!$pedido) {
            return redirect()->to('/Pedidos')->with('erro', 'Pedido não encontrado.');
        }

        if ($this->pedidoModel->delete($id)) {
            return redirect()->to('/Pedidos')->with('sucesso', 'Pedido excluído com sucesso!');
        } else {
            return redirect()->to('/Pedidos')->with('erro', 'Erro ao excluir pedido.');
        }
    }
}
