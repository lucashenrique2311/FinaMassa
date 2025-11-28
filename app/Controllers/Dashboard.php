<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PedidoVendaModel;
use App\Models\ItemPedidoModel;
use App\Models\EstoqueModel;
use App\Models\ProdutoModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $session;
    protected $usuario;
    protected $pedidoModel;
    protected $itemPedidoModel;
    protected $estoqueModel;
    protected $produtoModel;
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
        $this->produtoModel = new ProdutoModel();
        $this->idCliente = $this->usuario['ID_CLIENTE'] ?? $this->usuario['id_cliente'] ?? null;
        $this->idUsuario = $this->usuario['ID_USUARIO'] ?? $this->usuario['id_usuario'] ?? null;
        
        if($this->usuario == null){
            return redirect()->to('/');
        }
        
        date_default_timezone_set('America/Sao_Paulo');
        
        // Helper complementos se existir
        if (function_exists('helper')) {
            helper('complementos');
        }
    }

	public function index()
	{   
        // Data atual
        $dataHoje = date('Y-m-d');
        $dataMesAtual = date('Y-m');
        $dataInicioMes = date('Y-m-01');
        $dataFimMes = date('Y-m-t');
        
        // Últimos 30 dias para gráfico
        $dataInicio30Dias = date('Y-m-d', strtotime('-30 days'));
        
        // Estatísticas do dia
        $pedidosHoje = $this->pedidoModel->getPedidos([
            'data_inicio' => $dataHoje,
            'data_fim' => $dataHoje . ' 23:59:59',
        ]);
        
        $vendasHoje = 0;
        $pedidosHojeCount = count($pedidosHoje);
        foreach ($pedidosHoje as $pedido) {
            $vendasHoje += floatval($pedido['total'] ?? 0);
        }
        
        // Estatísticas do mês
        $pedidosMes = $this->pedidoModel->getPedidos([
            'data_inicio' => $dataInicioMes,
            'data_fim' => $dataFimMes . ' 23:59:59',
        ]);
        
        $vendasMes = 0;
        $pedidosMesCount = count($pedidosMes);
        foreach ($pedidosMes as $pedido) {
            $vendasMes += floatval($pedido['total'] ?? 0);
        }
        
        // Vendas dos últimos 30 dias (para gráfico)
        $pedidos30Dias = $this->pedidoModel->getPedidos([
            'data_inicio' => $dataInicio30Dias,
            'data_fim' => date('Y-m-d'),
        ]);
        
        $vendasPorDia = [];
        for ($i = 29; $i >= 0; $i--) {
            $data = date('Y-m-d', strtotime("-{$i} days"));
            $vendasPorDia[$data] = 0;
        }
        
        foreach ($pedidos30Dias as $pedido) {
            $data = date('Y-m-d', strtotime($pedido['data_pedido']));
            if (isset($vendasPorDia[$data])) {
                $vendasPorDia[$data] += floatval($pedido['total'] ?? 0);
            }
        }
        
        // Pedidos por status (hoje)
        $pedidosPorStatus = [];
        foreach ($pedidosHoje as $pedido) {
            $status = $pedido['status'] ?? 'OUTRO';
            $pedidosPorStatus[$status] = ($pedidosPorStatus[$status] ?? 0) + 1;
        }
        
        // Pedidos recentes (últimos 10)
        $pedidosRecentes = $this->pedidoModel->getPedidos([]);
        $pedidosRecentes = array_slice($pedidosRecentes, 0, 10);
        
        // Estoque baixo
        $estoque = $this->estoqueModel->getEstoque();
        $estoqueBaixo = [];
        foreach ($estoque as $item) {
            if (isset($item['estoque_minimo']) && $item['quantidade'] <= $item['estoque_minimo']) {
                $estoqueBaixo[] = $item;
            }
        }
        
        // Produtos mais vendidos (últimos 30 dias)
        $produtosVendidos = [];
        foreach ($pedidos30Dias as $pedido) {
            $itens = $this->itemPedidoModel->getItensPedido($pedido['id_pedido']);
            foreach ($itens as $item) {
                $idProduto = $item['id_produto'] ?? null;
                $nomeProduto = $item['nome_produto'] ?? 'Produto não cadastrado';
                $quantidade = floatval($item['quantidade'] ?? 0);
                
                if ($idProduto) {
                    if (!isset($produtosVendidos[$idProduto])) {
                        $produtosVendidos[$idProduto] = [
                            'id_produto' => $idProduto,
                            'nome' => $nomeProduto,
                            'quantidade_total' => 0,
                        ];
                    }
                    $produtosVendidos[$idProduto]['quantidade_total'] += $quantidade;
                }
            }
        }
        
        // Ordena por quantidade
        usort($produtosVendidos, function($a, $b) {
            return $b['quantidade_total'] <=> $a['quantidade_total'];
        });
        
        $produtosVendidos = array_slice($produtosVendidos, 0, 5);
        
        $data = [
            'title' => 'Dashboard',
            'usuario' => $this->usuario,
            'estatisticas' => [
                'vendas_hoje' => $vendasHoje,
                'pedidos_hoje' => $pedidosHojeCount,
                'vendas_mes' => $vendasMes,
                'pedidos_mes' => $pedidosMesCount,
                'ticket_medio_hoje' => $pedidosHojeCount > 0 ? $vendasHoje / $pedidosHojeCount : 0,
                'ticket_medio_mes' => $pedidosMesCount > 0 ? $vendasMes / $pedidosMesCount : 0,
                'pedidos_por_status' => $pedidosPorStatus,
                'vendas_por_dia' => $vendasPorDia,
            ],
            'pedidos_recentes' => $pedidosRecentes,
            'estoque_baixo' => $estoqueBaixo,
            'produtos_mais_vendidos' => $produtosVendidos,
        ];

        echo view('Commons/header');
        echo view('Dashboard/dashboard', $data);
        echo view('Commons/footer');
    }

    public function bling(){
        // Método temporário - será implementado depois
        $dados = [];
        return view('Dashboard/dashboard', $dados);
    }
}