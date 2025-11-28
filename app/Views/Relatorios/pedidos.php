<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Relatório de Pedidos
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Relatórios</span>
      </div>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    
    <!-- Filtros -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">Filtros</h3>
      </div>
      <div class="card-body">
        <form method="GET" action="<?= base_url('Relatorios/pedidos') ?>" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Início</label>
            <input type="date" name="data_inicio" class="input input-sm" value="<?= esc($filtros['data_inicio']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Fim</label>
            <input type="date" name="data_fim" class="input input-sm" value="<?= esc($filtros['data_fim']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Status</label>
            <select name="status" class="select select-sm" style="width: 100%;">
              <option value="">Todos</option>
              <option value="ABERTO" <?= ($filtros['status'] ?? '') == 'ABERTO' ? 'selected' : '' ?>>Aberto</option>
              <option value="PREPARANDO" <?= ($filtros['status'] ?? '') == 'PREPARANDO' ? 'selected' : '' ?>>Preparando</option>
              <option value="PRONTO" <?= ($filtros['status'] ?? '') == 'PRONTO' ? 'selected' : '' ?>>Pronto</option>
              <option value="ENTREGUE" <?= ($filtros['status'] ?? '') == 'ENTREGUE' ? 'selected' : '' ?>>Entregue</option>
              <option value="CANCELADO" <?= ($filtros['status'] ?? '') == 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
            </select>
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Tipo</label>
            <select name="tipo_pedido" class="select select-sm" style="width: 100%;">
              <option value="">Todos</option>
              <option value="BALCAO" <?= ($filtros['tipo_pedido'] ?? '') == 'BALCAO' ? 'selected' : '' ?>>Balcão</option>
              <option value="DELIVERY" <?= ($filtros['tipo_pedido'] ?? '') == 'DELIVERY' ? 'selected' : '' ?>>Delivery</option>
              <option value="RETIRADA" <?= ($filtros['tipo_pedido'] ?? '') == 'RETIRADA' ? 'selected' : '' ?>>Retirada</option>
            </select>
          </div>
          <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="ki-filled ki-magnifier"></i>
              Filtrar
            </button>
            <a href="<?= base_url('Relatorios/pedidos') ?>" class="btn btn-sm btn-light">
              <i class="ki-filled ki-cross"></i>
              Limpar
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Estatísticas -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 shrink-0">
              <i class="ki-filled ki-chart-simple text-primary text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total de Vendas</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['total_vendas'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-shopping-cart text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total de Pedidos</span>
              <span class="text-lg font-bold text-gray-900"><?= $estatisticas['total_pedidos'] ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-info/10 shrink-0">
              <i class="ki-filled ki-dollar text-info text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Ticket Médio</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['ticket_medio'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-warning/10 shrink-0">
              <i class="ki-filled ki-calendar text-warning text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Período</span>
              <span class="text-lg font-bold text-gray-900"><?= date('d/m/Y', strtotime($filtros['data_inicio'])) ?> - <?= date('d/m/Y', strtotime($filtros['data_fim'])) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      <!-- Pedidos por Status -->
      <div class="card card-grid">
        <div class="card-header">
          <h3 class="card-title font-medium text-sm">Pedidos por Status</h3>
        </div>
        <div class="card-body">
          <div class="flex flex-col gap-3">
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
              $percentual = $estatisticas['total_pedidos'] > 0 ? ($quantidade / $estatisticas['total_pedidos']) * 100 : 0;
              ?>
              <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                  <span class="badge badge-sm <?= $statusClass[$status] ?? 'badge' ?>"><?= $statusLabels[$status] ?? $status ?></span>
                  <span class="text-sm text-gray-700"><?= $quantidade ?> pedido(s)</span>
                </div>
                <span class="text-sm font-medium text-gray-900"><?= number_format($percentual, 1) ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: <?= $percentual ?>%"></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Pedidos por Tipo -->
      <div class="card card-grid">
        <div class="card-header">
          <h3 class="card-title font-medium text-sm">Pedidos por Tipo</h3>
        </div>
        <div class="card-body">
          <div class="flex flex-col gap-3">
            <?php foreach ($estatisticas['pedidos_por_tipo'] as $tipo => $quantidade): ?>
              <?php
              $tipoLabels = [
                'BALCAO' => 'Balcão',
                'DELIVERY' => 'Delivery',
                'RETIRADA' => 'Retirada',
              ];
              $percentual = $estatisticas['total_pedidos'] > 0 ? ($quantidade / $estatisticas['total_pedidos']) * 100 : 0;
              ?>
              <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                  <span class="badge badge-sm"><?= $tipoLabels[$tipo] ?? $tipo ?></span>
                  <span class="text-sm text-gray-700"><?= $quantidade ?> pedido(s)</span>
                </div>
                <span class="text-sm font-medium text-gray-900"><?= number_format($percentual, 1) ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full" style="width: <?= $percentual ?>%"></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Pedidos -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">
          Pedidos (<?= count($pedidos) ?>)
        </h3>
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
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos)): ?>
                <tr>
                  <td colspan="6" class="text-center py-10 text-gray-500">
                    Nenhum pedido encontrado no período.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                  <tr>
                    <td class="font-medium">#<?= esc($pedido['numero_pedido']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td><?= esc($pedido['cliente_nome'] ?? 'Cliente não informado') ?></td>
                    <td>
                      <?php
                      $tipoLabels = [
                        'BALCAO' => 'Balcão',
                        'DELIVERY' => 'Delivery',
                        'RETIRADA' => 'Retirada'
                      ];
                      ?>
                      <span class="badge badge-sm"><?= $tipoLabels[$pedido['tipo_pedido']] ?? $pedido['tipo_pedido'] ?></span>
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

