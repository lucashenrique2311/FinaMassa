<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Dashboard
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <span class="text-gray-900">Visão Geral</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-primary" href="<?= base_url('Pedidos/novo') ?>">
        <i class="ki-filled ki-plus !text-base"></i>
        Novo Pedido
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    
    <!-- Cards de Estatísticas -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <!-- Vendas Hoje -->
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 shrink-0">
              <i class="ki-filled ki-dollar text-primary text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Vendas Hoje</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['vendas_hoje'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Pedidos Hoje -->
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-shopping-cart text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Pedidos Hoje</span>
              <span class="text-lg font-bold text-gray-900"><?= $estatisticas['pedidos_hoje'] ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Vendas do Mês -->
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-info/10 shrink-0">
              <i class="ki-filled ki-chart-simple text-info text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Vendas do Mês</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['vendas_mes'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Ticket Médio -->
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-warning/10 shrink-0">
              <i class="ki-filled ki-calendar text-warning text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Ticket Médio (Hoje)</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['ticket_medio_hoje'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfico de Vendas e Pedidos Recentes -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <!-- Gráfico de Vendas (Últimos 30 dias) -->
      <div class="card card-grid" style="flex: 2; min-width: 100%;">
        <div class="card-header">
          <h3 class="card-title font-medium text-sm">Vendas dos Últimos 30 Dias</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <div id="grafico_vendas" style="min-width: 100%; height: 300px;"></div>
        </div>
      </div>
      
      <!-- Pedidos por Status (Hoje) -->
      <div class="card card-grid" style="flex: 1; min-width: 250px;">
        <div class="card-header">
          <h3 class="card-title font-medium text-sm">Pedidos Hoje por Status</h3>
        </div>
        <div class="card-body">
          <div class="flex flex-col gap-3 px-3 pt-2">
            <?php if (empty($estatisticas['pedidos_por_status'])): ?>
              <p class="text-sm text-gray-500 text-center py-5">Nenhum pedido hoje</p>
            <?php else: ?>
              <?php foreach ($estatisticas['pedidos_por_status'] as $status => $quantidade): ?>
                <?php
                $statusLabels = [
                  'ABERTO' => 'Aberto',
                  'PREPARANDO' => 'Preparando',
                  'PRONTO' => 'Pronto',
                  'ENTREGUE' => 'Entregue',
                  'CANCELADO' => 'Cancelado',
                ];
                $statusClass = [
                  'ABERTO' => 'badge-warning',
                  'PREPARANDO' => 'badge-info',
                  'PRONTO' => 'badge-primary',
                  'ENTREGUE' => 'badge-success',
                  'CANCELADO' => 'badge-danger',
                ];
                $totalStatus = array_sum($estatisticas['pedidos_por_status']);
                $percentual = $totalStatus > 0 ? ($quantidade / $totalStatus) * 100 : 0;
                ?>
                <div class="flex items-center justify-between gap-3">
                  <div class="flex items-center gap-2">
                    <span class="badge badge-sm <?= $statusClass[$status] ?? 'badge' ?>"><?= $statusLabels[$status] ?? $status ?></span>
                    <span class="text-sm text-gray-700"><?= $quantidade ?></span>
                  </div>
                  <span class="text-sm font-medium text-gray-900"><?= number_format($percentual, 0) ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-primary h-2 rounded-full" style="width: <?= $percentual ?>%"></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Estoque Baixo e Produtos Mais Vendidos -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <!-- Estoque Baixo -->
      <div class="card card-grid" style="flex: 1; min-width: 300px;">
        <div class="card-header flex items-center justify-between">
          <h3 class="card-title font-medium text-sm">Estoque Baixo</h3>
          <a href="<?= base_url('Estoque') ?>" class="text-xs text-primary hover:underline">Ver todos</a>
        </div>
        <div class="card-body">
          <?php if (empty($estoque_baixo)): ?>
            <p class="text-sm text-gray-500 text-center py-5">Nenhum item com estoque baixo</p>
          <?php else: ?>
            <div class="flex flex-col gap-3">
              <?php foreach (array_slice($estoque_baixo, 0, 5) as $item): ?>
                <div class="flex items-center justify-between gap-3 p-2 rounded border border-warning/20 bg-warning/5">
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900"><?= esc($item['produto_nome'] ?? 'N/A') ?></span>
                    <span class="text-xs text-gray-500"><?= esc($item['deposito_nome'] ?? 'N/A') ?></span>
                  </div>
                  <div class="flex flex-col items-end">
                    <span class="text-sm font-bold text-warning"><?= number_format($item['quantidade'] ?? 0, 3, ',', '.') ?></span>
                    <span class="text-xs text-gray-500">Mín: <?= number_format($item['estoque_minimo'] ?? 0, 3, ',', '.') ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Produtos Mais Vendidos (30 dias) -->
      <div class="card card-grid" style="flex: 1; min-width: 300px;">
        <div class="card-header flex items-center justify-between">
          <h3 class="card-title font-medium text-sm">Produtos Mais Vendidos</h3>
          <a href="<?= base_url('Relatorios/produtos') ?>" class="text-xs text-primary hover:underline">Ver relatório</a>
        </div>
        <div class="card-body">
          <?php if (empty($produtos_mais_vendidos)): ?>
            <p class="text-sm text-gray-500 text-center py-5">Nenhuma venda nos últimos 30 dias</p>
          <?php else: ?>
            <div class="flex flex-col gap-3 px-3 pt-2">
              <?php foreach ($produtos_mais_vendidos as $index => $produto): ?>
                <div class="flex items-center justify-between gap-3">
                  <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500"><?= $index + 1 ?>º</span>
                    <span class="text-sm font-medium text-gray-900"><?= esc($produto['nome']) ?></span>
                  </div>
                  <span class="text-sm font-bold text-primary"><?= number_format($produto['quantidade_total'], 0, ',', '.') ?> un</span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Pedidos Recentes -->
    <div class="card card-grid min-w-full">
      <div class="card-header flex items-center justify-between">
        <h3 class="card-title font-medium text-sm">Pedidos Recentes</h3>
        <a href="<?= base_url('Pedidos') ?>" class="text-xs text-primary hover:underline">Ver todos</a>
      </div>
      <div class="card-body">
        <div class="scrollable-x-auto">
          <table class="table table-auto table-border">
            <thead>
              <tr>
                <th>Nº Pedido</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Total</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos_recentes)): ?>
                <tr>
                  <td colspan="7" class="text-center py-10 text-gray-500">
                    Nenhum pedido encontrado.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($pedidos_recentes as $pedido): ?>
                  <tr>
                    <td class="font-medium">#<?= esc($pedido['numero_pedido']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td><?= esc($pedido['cliente_nome'] ?? 'Cliente não informado') ?></td>
                    <td>
                      <?php
                      $tipoLabels = [
                        'BALCAO' => 'Balcão',
                        'APP' => 'App (UaiRango)'
                      ];
                      $tipoEntregaLabels = [
                        'DELIVERY' => 'Delivery',
                        'RETIRADA' => 'Retirada'
                      ];
                      $tipoExibicao = $tipoLabels[$pedido['tipo_pedido']] ?? $pedido['tipo_pedido'];
                      if ($pedido['tipo_pedido'] === 'APP' && !empty($pedido['tipo_entrega'])) {
                        $tipoExibicao .= ' - ' . ($tipoEntregaLabels[$pedido['tipo_entrega']] ?? $pedido['tipo_entrega']);
                      }
                      ?>
                      <span class="badge badge-sm"><?= $tipoExibicao ?></span>
                    </td>
                    <td>
                      <?php
                      $statusClass = '';
                      $statusLabel = '';
                      switch($pedido['status']) {
                        case 'ABERTO':
                          $statusClass = 'badge-warning';
                          $statusLabel = 'Aberto';
                          break;
                        case 'PREPARANDO':
                          $statusClass = 'badge-info';
                          $statusLabel = 'Preparando';
                          break;
                        case 'PRONTO':
                          $statusClass = 'badge-primary';
                          $statusLabel = 'Pronto';
                          break;
                        case 'ENTREGUE':
                          $statusClass = 'badge-success';
                          $statusLabel = 'Entregue';
                          break;
                        case 'CANCELADO':
                          $statusClass = 'badge-danger';
                          $statusLabel = 'Cancelado';
                          break;
                        default:
                          $statusClass = 'badge';
                          $statusLabel = $pedido['status'];
                      }
                      ?>
                      <span class="badge badge-sm <?= $statusClass ?>"><?= $statusLabel ?></span>
                    </td>
                    <td class="font-medium">R$ <?= number_format($pedido['total'] ?? 0, 2, ',', '.') ?></td>
                    <td>
                      <a href="<?= base_url('Pedidos/visualizar/' . $pedido['id_pedido']) ?>" class="btn btn-sm btn-icon btn-light" title="Visualizar">
                        <i class="ki-filled ki-eye"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- End of Container -->
</main>

<script>
// Gráfico de Vendas (ApexCharts)
document.addEventListener('DOMContentLoaded', function() {
  if (typeof ApexCharts !== 'undefined') {
    const vendasPorDia = <?= json_encode($estatisticas['vendas_por_dia']) ?>;
    const datas = Object.keys(vendasPorDia);
    const valores = Object.values(vendasPorDia);
    
    // Detecta se é mobile
    const isMobile = window.innerWidth <= 768;
    
    // No mobile, mostra apenas algumas datas (a cada 3 dias)
    let datasExibidas = datas;
    let valoresExibidos = valores;
    if (isMobile && datas.length > 15) {
      const step = Math.ceil(datas.length / 10); // Mostra aproximadamente 10 datas
      datasExibidas = datas.filter((_, index) => index % step === 0 || index === datas.length - 1);
      valoresExibidos = valores.filter((_, index) => index % step === 0 || index === valores.length - 1);
    }
    
    const options = {
      series: [{
        name: 'Vendas',
        data: valoresExibidos
      }],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: '60%',
          distributed: false,
          dataLabels: {
            position: 'top'
          }
        }
      },
      grid: {
        borderColor: '#e5e7eb',
        strokeDashArray: 4,
        xaxis: {
          lines: {
            show: false
          }
        },
        yaxis: {
          lines: {
            show: true
          }
        },
        padding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0
        }
      },
      xaxis: {
        categories: datasExibidas.map(data => {
          const d = new Date(data);
          return d.getDate() + '/' + (d.getMonth() + 1);
        }),
        labels: {
          rotate: -45,
          rotateAlways: false,
          style: {
            fontSize: '11px',
            colors: '#6b7280'
          },
          show: true,
          showDuplicates: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        labels: {
          formatter: function(val) {
            if (val >= 1000) {
              return 'R$ ' + (val / 1000).toFixed(1).replace('.', ',') + 'k';
            }
            return 'R$ ' + val.toFixed(0).replace('.', ',');
          },
          style: {
            fontSize: '11px',
            colors: '#6b7280'
          }
        }
      },
      tooltip: {
        theme: 'light',
        y: {
          formatter: function(val) {
            return 'R$ ' + val.toFixed(2).replace('.', ',');
          }
        },
        marker: {
          show: true
        }
      },
      colors: ['#c3753c'],
      responsive: [{
        breakpoint: 768,
        options: {
          chart: {
            height: 250
          },
          xaxis: {
            labels: {
              rotate: -45,
              rotateAlways: true,
              style: {
                fontSize: '10px'
              },
              showDuplicates: false,
              maxHeight: 80
            },
            tickAmount: 15
          },
          yaxis: {
            labels: {
              style: {
                fontSize: '10px'
              }
            }
          }
        }
      }, {
        breakpoint: 480,
        options: {
          chart: {
            height: 220
          },
          plotOptions: {
            bar: {
              columnWidth: '50%',
              borderRadius: 3
            }
          },
          xaxis: {
            labels: {
              rotate: -45,
              rotateAlways: true,
              style: {
                fontSize: '9px'
              },
              maxHeight: 80,
              showDuplicates: false,
              hideOverlappingLabels: true,
              minHeight: 60
            },
            tickAmount: 8
          },
          yaxis: {
            labels: {
              style: {
                fontSize: '9px'
              },
              formatter: function(val) {
                if (val >= 1000) {
                  return 'R$' + (val / 1000).toFixed(1).replace('.', ',') + 'k';
                }
                return 'R$' + val.toFixed(0);
              }
            }
          },
          tooltip: {
            style: {
              fontSize: '11px'
            }
          }
        }
      }]
    };
    
    const chart = new ApexCharts(document.querySelector("#grafico_vendas"), options);
    chart.render();
  }
});
</script>
