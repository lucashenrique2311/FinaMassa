<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Relatório de Lucro/Prejuízo
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Relatorios/pedidos') ?>">
          Relatórios
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Lucro/Prejuízo</span>
      </div>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    
    <!-- Filtros -->
    <div class="card">
      <div class="card-body py-3">
        <form method="get" action="<?= base_url('Relatorios/lucro') ?>" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Início</label>
            <input type="date" name="data_inicio" class="input input-sm" style="width: 100%;" value="<?= esc($filtros['data_inicio'] ?? '') ?>">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Fim</label>
            <input type="date" name="data_fim" class="input input-sm" style="width: 100%;" value="<?= esc($filtros['data_fim'] ?? '') ?>">
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
              <option value="APP" <?= ($filtros['tipo_pedido'] ?? '') == 'APP' ? 'selected' : '' ?>>App (UaiRango)</option>
            </select>
          </div>
          <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="ki-filled ki-magnifier"></i>
              Filtrar
            </button>
            <a href="<?= base_url('Relatorios/lucro') ?>" class="btn btn-sm btn-light">
              <i class="ki-filled ki-cross"></i>
              Limpar
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Resumo -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-dollar text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total de Vendas</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($resumo['total_vendas'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-danger/10 shrink-0">
              <i class="ki-filled ki-dollar text-danger text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total de Custos</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($resumo['total_custos'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-arrow-up text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Lucro Total</span>
              <span class="text-lg font-bold text-success">R$ <?= number_format($resumo['total_lucro'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-danger/10 shrink-0">
              <i class="ki-filled ki-arrow-down text-danger text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Prejuízo Total</span>
              <span class="text-lg font-bold text-danger">R$ <?= number_format($resumo['total_prejuizo'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full <?= $resumo['lucro_liquido'] >= 0 ? 'bg-success/10' : 'bg-danger/10' ?> shrink-0">
              <i class="ki-filled ki-chart text-<?= $resumo['lucro_liquido'] >= 0 ? 'success' : 'danger' ?> text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Lucro Líquido</span>
              <span class="text-lg font-bold <?= $resumo['lucro_liquido'] >= 0 ? 'text-success' : 'text-danger' ?>">R$ <?= number_format($resumo['lucro_liquido'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 shrink-0">
              <i class="ki-filled ki-percent text-primary text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Margem Geral</span>
              <span class="text-lg font-bold text-gray-900"><?= number_format($resumo['margem_geral'], 2, ',', '.') ?>%</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumo de Taxas -->
    <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-warning/10 shrink-0">
              <i class="ki-filled ki-dollar text-warning text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Taxa App</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($resumo['total_taxa_app'], 2, ',', '.') ?></span>
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
              <span class="text-2sm font-medium text-gray-500">Taxa Entrega</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($resumo['total_taxa_entrega'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-gray-100 shrink-0">
              <i class="ki-filled ki-minus text-gray-600 text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Descontos</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($resumo['total_descontos'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-check text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Pedidos com Lucro</span>
              <span class="text-lg font-bold text-success"><?= $resumo['pedidos_com_lucro'] ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-danger/10 shrink-0">
              <i class="ki-filled ki-cross text-danger text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Pedidos com Prejuízo</span>
              <span class="text-lg font-bold text-danger"><?= $resumo['pedidos_com_prejuizo'] ?></span>
            </div>
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
                <th>Subtotal</th>
                <th>Desconto</th>
                <th>Taxa Entrega</th>
                <th>Taxa App</th>
                <th>Custo</th>
                <th>Total</th>
                <th>Lucro/Prejuízo</th>
                <th>Margem</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos)): ?>
                <tr>
                  <td colspan="13" class="text-center py-10 text-gray-500">
                    Nenhum pedido encontrado no período.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($pedidos as $item): ?>
                  <?php $pedido = $item['pedido']; ?>
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
                    <td class="font-medium">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    <td class="text-danger">R$ <?= number_format($item['desconto'], 2, ',', '.') ?></td>
                    <td class="font-medium">R$ <?= number_format($item['taxa_entrega'], 2, ',', '.') ?></td>
                    <td class="font-medium">R$ <?= number_format($item['taxa_app'], 2, ',', '.') ?></td>
                    <td class="text-danger font-medium">R$ <?= number_format($item['custo_total'], 2, ',', '.') ?></td>
                    <td class="font-medium">R$ <?= number_format($item['total'], 2, ',', '.') ?></td>
                    <td class="font-bold <?= $item['lucro_prejuizo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                      R$ <?= number_format($item['lucro_prejuizo'], 2, ',', '.') ?>
                    </td>
                    <td class="font-medium <?= $item['margem'] >= 0 ? 'text-success' : 'text-danger' ?>">
                      <?= number_format($item['margem'], 2, ',', '.') ?>%
                    </td>
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

