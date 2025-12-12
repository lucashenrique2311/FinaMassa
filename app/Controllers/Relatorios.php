<?php namespace App\Controllers;

use App\Models\PedidoVendaModel;
use App\Models\ItemPedidoModel;
use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use App\Models\ProdutoModel;
use App\Models\ProdutoComposicaoModel;
use App\Models\DepositoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Relatorios extends BaseController
{
    protected $pedidoModel;
    protected $itemPedidoModel;
    protected $estoqueModel;
    protected $movimentacaoModel;
    protected $produtoModel;
    protected $composicaoModel;
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
        $this->estoqueModel = new EstoqueModel();
        $this->movimentacaoModel = new MovimentacaoEstoqueModel();
        $this->produtoModel = new ProdutoModel();
        $this->composicaoModel = new ProdutoComposicaoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        $this->idUsuario = $this->usuario['ID_USUARIO'] ?? $this->usuario['id_usuario'] ?? null;
        
        // Carrega helper de complementos
        helper('complementos');
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
        
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * Relatório de Pedidos/Vendas
     */
    public function pedidos()
    {
        $dataInicio = $this->request->getGet('data_inicio') ?: date('Y-m-01');
        $dataFim = $this->request->getGet('data_fim') ?: date('Y-m-t');
        $status = $this->request->getGet('status') ?: '';
        $tipoPedido = $this->request->getGet('tipo_pedido') ?: '';

        $filtros = [
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'status' => $status,
            'tipo_pedido' => $tipoPedido,
        ];

        // Busca pedidos
        $pedidos = $this->pedidoModel->getPedidos($filtros);

        // Calcula estatísticas
        $totalVendas = 0;
        $totalPedidos = count($pedidos);
        $pedidosPorStatus = [];
        $pedidosPorTipo = [];
        $vendasPorDia = [];

        foreach ($pedidos as $pedido) {
            $totalVendas += floatval($pedido['total'] ?? 0);
            
            // Por status
            $statusPedido = $pedido['status'] ?? 'OUTRO';
            $pedidosPorStatus[$statusPedido] = ($pedidosPorStatus[$statusPedido] ?? 0) + 1;
            
            // Por tipo
            $tipo = $pedido['tipo_pedido'] ?? 'OUTRO';
            $pedidosPorTipo[$tipo] = ($pedidosPorTipo[$tipo] ?? 0) + 1;
            
            // Por dia
            $data = date('Y-m-d', strtotime($pedido['data_pedido']));
            $vendasPorDia[$data] = ($vendasPorDia[$data] ?? 0) + floatval($pedido['total'] ?? 0);
        }

        $data = [
            'title' => 'Relatório de Pedidos',
            'usuario' => $this->usuario,
            'pedidos' => $pedidos,
            'filtros' => $filtros,
            'estatisticas' => [
                'total_vendas' => $totalVendas,
                'total_pedidos' => $totalPedidos,
                'ticket_medio' => $totalPedidos > 0 ? $totalVendas / $totalPedidos : 0,
                'pedidos_por_status' => $pedidosPorStatus,
                'pedidos_por_tipo' => $pedidosPorTipo,
                'vendas_por_dia' => $vendasPorDia,
            ],
        ];

        echo view('Commons/header');
        echo view('Relatorios/pedidos', $data);
        echo view('Commons/footer');
    }

    /**
     * Relatório de Estoque
     */
    public function estoque()
    {
        $dataInicio = $this->request->getGet('data_inicio') ?: date('Y-m-01');
        $dataFim = $this->request->getGet('data_fim') ?: date('Y-m-t');
        $tipoMovimentacao = $this->request->getGet('tipo') ?: '';
        $idDeposito = $this->request->getGet('id_deposito') ?: '';

        // Busca estoque atual
        $estoque = $this->estoqueModel->getEstoque();

        // Busca movimentações
        $movimentacoes = $this->movimentacaoModel->getMovimentacoes([
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'tipo' => $tipoMovimentacao,
            'id_deposito' => $idDeposito,
        ]);

        // Calcula estatísticas
        $totalEntradas = 0;
        $totalSaidas = 0;
        $valorTotalEntradas = 0;
        $valorTotalSaidas = 0;
        $movimentacoesPorTipo = [];

        foreach ($movimentacoes as $mov) {
            $quantidade = floatval($mov['quantidade'] ?? 0);
            $custo = floatval($mov['custo_unitario'] ?? 0);
            $valor = $quantidade * $custo;

            if ($mov['tipo'] == 'ENTRADA') {
                $totalEntradas += $quantidade;
                $valorTotalEntradas += $valor;
            } elseif ($mov['tipo'] == 'SAIDA') {
                $totalSaidas += $quantidade;
                $valorTotalSaidas += $valor;
            }

            $tipo = $mov['tipo'] ?? 'OUTRO';
            $movimentacoesPorTipo[$tipo] = ($movimentacoesPorTipo[$tipo] ?? 0) + 1;
        }

        $data = [
            'title' => 'Relatório de Estoque',
            'usuario' => $this->usuario,
            'estoque' => $estoque,
            'movimentacoes' => $movimentacoes,
            'filtros' => [
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'tipo' => $tipoMovimentacao,
                'id_deposito' => $idDeposito,
            ],
            'estatisticas' => [
                'total_entradas' => $totalEntradas,
                'total_saidas' => $totalSaidas,
                'valor_total_entradas' => $valorTotalEntradas,
                'valor_total_saidas' => $valorTotalSaidas,
                'movimentacoes_por_tipo' => $movimentacoesPorTipo,
            ],
        ];

        echo view('Commons/header');
        echo view('Relatorios/estoque', $data);
        echo view('Commons/footer');
    }

    /**
     * Relatório de Produtos Mais Vendidos
     */
    public function produtos()
    {
        $dataInicio = $this->request->getGet('data_inicio') ?: date('Y-m-01');
        $dataFim = $this->request->getGet('data_fim') ?: date('Y-m-t');
        $limite = $this->request->getGet('limite') ?: 20;

        // Busca pedidos no período
        $pedidos = $this->pedidoModel->getPedidos([
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
        ]);

        // Agrupa produtos vendidos
        $produtosVendidos = [];
        $totalGeral = 0;

        foreach ($pedidos as $pedido) {
            $itens = $this->itemPedidoModel->getItensPedido($pedido['id_pedido']);
            
            foreach ($itens as $item) {
                $idProduto = $item['id_produto'] ?? null;
                $nomeProduto = $item['nome_produto'] ?? 'Produto não cadastrado';
                $quantidade = floatval($item['quantidade'] ?? 0);
                $subtotal = floatval($item['subtotal'] ?? 0);

                if ($idProduto) {
                    if (!isset($produtosVendidos[$idProduto])) {
                        $produtosVendidos[$idProduto] = [
                            'id_produto' => $idProduto,
                            'nome' => $nomeProduto,
                            'quantidade_total' => 0,
                            'valor_total' => 0,
                            'pedidos' => 0,
                        ];
                    }
                    $produtosVendidos[$idProduto]['quantidade_total'] += $quantidade;
                    $produtosVendidos[$idProduto]['valor_total'] += $subtotal;
                    $produtosVendidos[$idProduto]['pedidos'] += 1;
                } else {
                    // Produto não cadastrado
                    $key = 'nao_cadastrado_' . md5($nomeProduto);
                    if (!isset($produtosVendidos[$key])) {
                        $produtosVendidos[$key] = [
                            'id_produto' => null,
                            'nome' => $nomeProduto,
                            'quantidade_total' => 0,
                            'valor_total' => 0,
                            'pedidos' => 0,
                        ];
                    }
                    $produtosVendidos[$key]['quantidade_total'] += $quantidade;
                    $produtosVendidos[$key]['valor_total'] += $subtotal;
                    $produtosVendidos[$key]['pedidos'] += 1;
                }

                $totalGeral += $subtotal;
            }
        }

        // Ordena por valor total
        usort($produtosVendidos, function($a, $b) {
            return $b['valor_total'] <=> $a['valor_total'];
        });

        // Limita resultados
        $produtosVendidos = array_slice($produtosVendidos, 0, $limite);

        $data = [
            'title' => 'Relatório de Produtos Mais Vendidos',
            'usuario' => $this->usuario,
            'produtos' => $produtosVendidos,
            'filtros' => [
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'limite' => $limite,
            ],
            'total_geral' => $totalGeral,
        ];

        echo view('Commons/header');
        echo view('Relatorios/produtos', $data);
        echo view('Commons/footer');
    }

    /**
     * Relatório de Lucro/Prejuízo por Pedido
     */
    public function lucro()
    {
        $dataInicio = $this->request->getGet('data_inicio') ?: date('Y-m-01');
        $dataFim = $this->request->getGet('data_fim') ?: date('Y-m-t');
        $status = $this->request->getGet('status') ?: '';
        $tipoPedido = $this->request->getGet('tipo_pedido') ?: '';

        $filtros = [
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'status' => $status,
            'tipo_pedido' => $tipoPedido,
        ];

        // Busca pedidos
        $pedidos = $this->pedidoModel->getPedidos($filtros);

        // Processa cada pedido para calcular lucro/prejuízo
        $pedidosComLucro = [];
        $totalVendas = 0;
        $totalCustos = 0;
        $totalTaxaApp = 0;
        $totalTaxaEntrega = 0;
        $totalDescontos = 0;
        $totalLucro = 0;
        $totalPrejuizo = 0;
        $pedidosComLucroCount = 0;
        $pedidosComPrejuizoCount = 0;

        foreach ($pedidos as $pedido) {
            $itens = $this->itemPedidoModel->getItensPedido($pedido['id_pedido']);
            
            // Calcula custo total dos produtos
            $custoTotal = 0;
            
            foreach ($itens as $item) {
                $idProduto = $item['id_produto'] ?? null;
                $idProdutoMeioAMeio = $item['id_produto_meio_a_meio'] ?? null;
                $quantidade = converterQuantidadeParaFloat($item['quantidade'] ?? 0);
                
                // Se tem composição, calcula custo baseado na composição
                if ($idProduto) {
                    $composicao = $this->composicaoModel->getComposicao($idProduto);
                    
                    if (!empty($composicao)) {
                        // Tem composição - calcula custo baseado na composição
                        $custoProduto = 0;
                        foreach ($composicao as $ingrediente) {
                            $custoProduto += converterQuantidadeParaFloat($ingrediente['subtotal'] ?? 0);
                        }
                        // Se for meio a meio, divide o custo pela metade
                        if ($idProdutoMeioAMeio) {
                            $custoProduto = $custoProduto / 2;
                            
                            // Calcula custo do segundo produto também
                            $composicaoMeioAMeio = $this->composicaoModel->getComposicao($idProdutoMeioAMeio);
                            $custoProdutoMeioAMeio = 0;
                            foreach ($composicaoMeioAMeio as $ingrediente) {
                                $custoProdutoMeioAMeio += converterQuantidadeParaFloat($ingrediente['subtotal'] ?? 0);
                            }
                            $custoProduto += ($custoProdutoMeioAMeio / 2);
                        }
                        $custoTotal += $custoProduto * $quantidade;
                    } else {
                        // Não tem composição - calcula custo
                        $produto = $this->produtoModel->getProduto($idProduto);
                        if ($produto) {
                            // Se é ingrediente, tenta pegar custo médio do estoque (mais preciso)
                            $custoUnitario = converterQuantidadeParaFloat($produto['custo_unitario'] ?? 0);
                            
                            if ($produto['eh_ingrediente'] ?? false) {
                                // Busca custo médio do estoque (mais preciso para ingredientes)
                                // Pega o primeiro depósito ativo
                                $depositoModel = new DepositoModel();
                                $depositos = $depositoModel->getDepositos(['ativo' => 1]);
                                if (!empty($depositos)) {
                                    $estoque = $this->estoqueModel->getEstoqueProdutoDeposito($idProduto, $depositos[0]['id_deposito']);
                                    if ($estoque && $estoque['custo_medio'] > 0) {
                                        $custoUnitario = converterQuantidadeParaFloat($estoque['custo_medio']);
                                    }
                                }
                            }
                            
                            // Se for meio a meio, divide pela metade
                            if ($idProdutoMeioAMeio) {
                                $custoUnitario = $custoUnitario / 2;
                                
                                // Adiciona custo do segundo produto
                                $produtoMeioAMeio = $this->produtoModel->getProduto($idProdutoMeioAMeio);
                                if ($produtoMeioAMeio) {
                                    $custoMeioAMeio = converterQuantidadeParaFloat($produtoMeioAMeio['custo_unitario'] ?? 0);
                                    
                                    // Se é ingrediente, tenta pegar do estoque
                                    if ($produtoMeioAMeio['eh_ingrediente'] ?? false) {
                                        $depositoModel = new DepositoModel();
                                        $depositos = $depositoModel->getDepositos(['ativo' => 1]);
                                        if (!empty($depositos)) {
                                            $estoque = $this->estoqueModel->getEstoqueProdutoDeposito($idProdutoMeioAMeio, $depositos[0]['id_deposito']);
                                            if ($estoque && $estoque['custo_medio'] > 0) {
                                                $custoMeioAMeio = converterQuantidadeParaFloat($estoque['custo_medio']);
                                            }
                                        }
                                    }
                                    
                                    $custoUnitario += $custoMeioAMeio / 2;
                                }
                            }
                            $custoTotal += $custoUnitario * $quantidade;
                        }
                    }
                }
            }
            
            $subtotal = converterQuantidadeParaFloat($pedido['subtotal'] ?? 0);
            $desconto = converterQuantidadeParaFloat($pedido['desconto'] ?? 0);
            $taxaEntrega = converterQuantidadeParaFloat($pedido['taxa_entrega'] ?? 0);
            $taxaApp = converterQuantidadeParaFloat($pedido['taxa_app'] ?? 0);
            $total = converterQuantidadeParaFloat($pedido['total'] ?? 0);
            
            // Lucro/Prejuízo = Total - (Custo + Taxa App + Taxa Entrega)
            $lucroPrejuizo = $total - ($custoTotal + $taxaApp + $taxaEntrega);
            
            $pedidosComLucro[] = [
                'pedido' => $pedido,
                'itens' => $itens,
                'custo_total' => $custoTotal,
                'subtotal' => $subtotal,
                'desconto' => $desconto,
                'taxa_entrega' => $taxaEntrega,
                'taxa_app' => $taxaApp,
                'total' => $total,
                'lucro_prejuizo' => $lucroPrejuizo,
                'margem' => $total > 0 ? ($lucroPrejuizo / $total) * 100 : 0
            ];
            
            // Acumula totais
            $totalVendas += $total;
            $totalCustos += $custoTotal;
            $totalTaxaApp += $taxaApp;
            $totalTaxaEntrega += $taxaEntrega;
            $totalDescontos += $desconto;
            
            if ($lucroPrejuizo >= 0) {
                $totalLucro += $lucroPrejuizo;
                $pedidosComLucroCount++;
            } else {
                $totalPrejuizo += abs($lucroPrejuizo);
                $pedidosComPrejuizoCount++;
            }
        }

        $data = [
            'title' => 'Relatório de Lucro/Prejuízo',
            'usuario' => $this->usuario,
            'pedidos' => $pedidosComLucro,
            'filtros' => $filtros,
            'resumo' => [
                'total_vendas' => $totalVendas,
                'total_custos' => $totalCustos,
                'total_taxa_app' => $totalTaxaApp,
                'total_taxa_entrega' => $totalTaxaEntrega,
                'total_descontos' => $totalDescontos,
                'total_lucro' => $totalLucro,
                'total_prejuizo' => $totalPrejuizo,
                'lucro_liquido' => $totalLucro - $totalPrejuizo,
                'margem_geral' => $totalVendas > 0 ? (($totalLucro - $totalPrejuizo) / $totalVendas) * 100 : 0,
                'pedidos_com_lucro' => $pedidosComLucroCount,
                'pedidos_com_prejuizo' => $pedidosComPrejuizoCount,
                'total_pedidos' => count($pedidosComLucro)
            ],
        ];

        echo view('Commons/header');
        echo view('Relatorios/lucro', $data);
        echo view('Commons/footer');
    }
}

