<?php namespace App\Controllers;

use App\Models\PedidoVendaModel;
use App\Models\ItemPedidoModel;
use App\Models\EstoqueModel;
use App\Models\MovimentacaoEstoqueModel;
use App\Models\ProdutoModel;
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
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        $this->idUsuario = $this->usuario['ID_USUARIO'] ?? $this->usuario['id_usuario'] ?? null;
        
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
}

