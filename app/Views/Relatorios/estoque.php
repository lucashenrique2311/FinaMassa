<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Relatório de Estoque
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
        <form method="GET" action="<?= base_url('Relatorios/estoque') ?>" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Início</label>
            <input type="date" name="data_inicio" class="input input-sm" value="<?= esc($filtros['data_inicio']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Data Fim</label>
            <input type="date" name="data_fim" class="input input-sm" value="<?= esc($filtros['data_fim']) ?>" required style="width: 100%;">
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Tipo</label>
            <select name="tipo" class="select select-sm" style="width: 100%;">
              <option value="">Todos</option>
              <option value="ENTRADA" <?= ($filtros['tipo'] ?? '') == 'ENTRADA' ? 'selected' : '' ?>>Entrada</option>
              <option value="SAIDA" <?= ($filtros['tipo'] ?? '') == 'SAIDA' ? 'selected' : '' ?>>Saída</option>
              <option value="AJUSTE" <?= ($filtros['tipo'] ?? '') == 'AJUSTE' ? 'selected' : '' ?>>Ajuste</option>
            </select>
          </div>
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label text-xs font-medium text-gray-700">Depósito</label>
            <select name="id_deposito" class="select select-sm" style="width: 100%;">
              <option value="">Todos</option>
              <?php
              $depositos = (new \App\Models\DepositoModel())->getDepositos(['ativo' => 1]);
              foreach ($depositos as $dep): ?>
                <option value="<?= $dep['id_deposito'] ?>" <?= ($filtros['id_deposito'] ?? '') == $dep['id_deposito'] ? 'selected' : '' ?>>
                  <?= esc($dep['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="ki-filled ki-magnifier"></i>
              Filtrar
            </button>
            <a href="<?= base_url('Relatorios/estoque') ?>" class="btn btn-sm btn-light">
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
            <div class="flex items-center justify-center size-12 rounded-full bg-success/10 shrink-0">
              <i class="ki-filled ki-arrow-down text-success text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total Entradas</span>
              <span class="text-lg font-bold text-gray-900"><?= number_format($estatisticas['total_entradas'], 3, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-danger/10 shrink-0">
              <i class="ki-filled ki-arrow-up text-danger text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Total Saídas</span>
              <span class="text-lg font-bold text-gray-900"><?= number_format($estatisticas['total_saidas'], 3, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 shrink-0">
              <i class="ki-filled ki-dollar text-primary text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Valor Entradas</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['valor_total_entradas'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-grid" style="flex: 1; min-width: 200px;">
        <div class="card-body">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center size-12 rounded-full bg-warning/10 shrink-0">
              <i class="ki-filled ki-dollar text-warning text-xl"></i>
            </div>
            <div class="flex flex-col">
              <span class="text-2sm font-medium text-gray-500">Valor Saídas</span>
              <span class="text-lg font-bold text-gray-900">R$ <?= number_format($estatisticas['valor_total_saidas'], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Estoque Atual -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">Estoque Atual</h3>
      </div>
      <div class="card-body">
        <div class="scrollable-x-auto">
          <table class="table table-auto table-border">
            <thead>
              <tr>
                <th>Ingrediente</th>
                <th>Depósito</th>
                <th>Quantidade</th>
                <th>Custo Médio</th>
                <th>Valor Total</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($estoque)): ?>
                <tr>
                  <td colspan="5" class="text-center py-10 text-gray-500">
                    Nenhum item em estoque.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($estoque as $item): ?>
                  <tr>
                    <td class="font-medium"><?= esc($item['produto_nome'] ?? 'N/A') ?></td>
                    <td><?= esc($item['deposito_nome'] ?? 'N/A') ?></td>
                    <td><?= number_format($item['quantidade'] ?? 0, 3, ',', '.') ?> <?= esc($item['unidade_medida'] ?? '') ?></td>
                    <td>R$ <?= number_format($item['custo_medio'] ?? 0, 2, ',', '.') ?></td>
                    <td class="font-medium">R$ <?= number_format(($item['quantidade'] ?? 0) * ($item['custo_medio'] ?? 0), 2, ',', '.') ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Movimentações -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">
          Movimentações (<?= count($movimentacoes) ?>)
        </h3>
      </div>
      <div class="card-body">
        <div class="scrollable-x-auto">
          <table class="table table-auto table-border">
            <thead>
              <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Ingrediente</th>
                <th>Depósito</th>
                <th>Quantidade</th>
                <th>Custo Unit.</th>
                <th>Valor Total</th>
                <th>Observações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($movimentacoes)): ?>
                <tr>
                  <td colspan="8" class="text-center py-10 text-gray-500">
                    Nenhuma movimentação encontrada no período.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($movimentacoes as $mov): ?>
                  <tr>
                    <td><?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?></td>
                    <td>
                      <?php
                      $tipoClass = [
                        'ENTRADA' => 'badge-success',
                        'SAIDA' => 'badge-danger',
                        'AJUSTE' => 'badge-warning',
                      ];
                      ?>
                      <span class="badge badge-sm <?= $tipoClass[$mov['tipo']] ?? 'badge' ?>"><?= esc($mov['tipo']) ?></span>
                    </td>
                    <td><?= esc($mov['produto_nome'] ?? 'N/A') ?></td>
                    <td><?= esc($mov['deposito_nome'] ?? 'N/A') ?></td>
                    <td><?= number_format($mov['quantidade'] ?? 0, 3, ',', '.') ?></td>
                    <td>R$ <?= number_format($mov['custo_unitario'] ?? 0, 2, ',', '.') ?></td>
                    <td class="font-medium">R$ <?= number_format(($mov['quantidade'] ?? 0) * ($mov['custo_unitario'] ?? 0), 2, ',', '.') ?></td>
                    <td><?= esc($mov['observacoes'] ?? '') ?></td>
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

