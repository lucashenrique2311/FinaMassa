<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Relatório de Produtos Mais Vendidos
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
        <form method="GET" action="<?= base_url('Relatorios/produtos') ?>" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Início</label>
            <input type="date" name="data_inicio" class="input input-sm" value="<?= esc($filtros['data_inicio']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Fim</label>
            <input type="date" name="data_fim" class="input input-sm" value="<?= esc($filtros['data_fim']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Limite de Resultados</label>
            <select name="limite" class="select select-sm" style="width: 100%;">
              <option value="10" <?= ($filtros['limite'] ?? 20) == 10 ? 'selected' : '' ?>>Top 10</option>
              <option value="20" <?= ($filtros['limite'] ?? 20) == 20 ? 'selected' : '' ?>>Top 20</option>
              <option value="50" <?= ($filtros['limite'] ?? 20) == 50 ? 'selected' : '' ?>>Top 50</option>
              <option value="100" <?= ($filtros['limite'] ?? 20) == 100 ? 'selected' : '' ?>>Top 100</option>
            </select>
          </div>
          <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="ki-filled ki-magnifier"></i>
              Filtrar
            </button>
            <a href="<?= base_url('Relatorios/produtos') ?>" class="btn btn-sm btn-light">
              <i class="ki-filled ki-cross"></i>
              Limpar
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Estatísticas -->
    <div class="card card-grid">
      <div class="card-body">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 shrink-0">
            <i class="ki-filled ki-dollar text-primary text-xl"></i>
          </div>
          <div class="flex flex-col">
            <span class="text-2sm font-medium text-gray-500">Total Geral de Vendas no Período</span>
            <span class="text-2xl font-bold text-gray-900">R$ <?= number_format($total_geral, 2, ',', '.') ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Produtos -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">
          Produtos Mais Vendidos (<?= count($produtos) ?>)
        </h3>
      </div>
      <div class="card-body">
        <div class="scrollable-x-auto">
          <table class="table table-auto table-border">
            <thead>
              <tr>
                <th class="w-12">#</th>
                <th>Produto</th>
                <th>Quantidade Vendida</th>
                <th>Valor Total</th>
                <th>Nº de Pedidos</th>
                <th>Ticket Médio</th>
                <th>% do Total</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($produtos)): ?>
                <tr>
                  <td colspan="7" class="text-center py-10 text-gray-500">
                    Nenhum produto vendido no período.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($produtos as $index => $produto): ?>
                  <?php
                  $percentual = $total_geral > 0 ? ($produto['valor_total'] / $total_geral) * 100 : 0;
                  $ticketMedio = $produto['pedidos'] > 0 ? $produto['valor_total'] / $produto['pedidos'] : 0;
                  ?>
                  <tr>
                    <td class="text-center font-medium text-gray-500"><?= $index + 1 ?></td>
                    <td class="font-medium"><?= esc($produto['nome']) ?></td>
                    <td><?= number_format($produto['quantidade_total'], 3, ',', '.') ?></td>
                    <td class="font-medium text-primary">R$ <?= number_format($produto['valor_total'], 2, ',', '.') ?></td>
                    <td><?= $produto['pedidos'] ?> pedido(s)</td>
                    <td>R$ <?= number_format($ticketMedio, 2, ',', '.') ?></td>
                    <td>
                      <div class="flex items-center gap-2">
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                          <div class="bg-primary h-2 rounded-full" style="width: <?= min($percentual, 100) ?>%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700"><?= number_format($percentual, 1) ?>%</span>
                      </div>
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

