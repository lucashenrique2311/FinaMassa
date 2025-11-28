<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Histórico de Estoque
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Estoque') ?>">
          Estoque
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Histórico</span>
      </div>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Card com DataTable -->
    <div class="card card-grid min-w-full">
      <div class="card-header flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
        <h3 class="card-title font-medium text-sm">
          Mostrando <?= count($movimentacoes) ?> movimentação(ões)
        </h3>
        <div class="flex flex-col lg:flex-row gap-3 w-full lg:w-auto lg:items-center">
          <!-- Filtros -->
          <div class="flex flex-row gap-2.5 items-center flex-wrap">
            <select id="filtro_tipo" class="select select-sm w-auto min-w-[140px]">
              <option value="">Todos os Tipos</option>
              <option value="ENTRADA" <?= ($filtros['tipo'] ?? '') == 'ENTRADA' ? 'selected' : '' ?>>Entrada</option>
              <option value="SAIDA" <?= ($filtros['tipo'] ?? '') == 'SAIDA' ? 'selected' : '' ?>>Saída</option>
              <option value="AJUSTE" <?= ($filtros['tipo'] ?? '') == 'AJUSTE' ? 'selected' : '' ?>>Ajuste</option>
            </select>
            <select id="filtro_ingrediente" class="select select-sm w-auto min-w-[180px]">
              <option value="">Todos os Ingredientes</option>
              <?php foreach ($ingredientes as $ing): ?>
                <option value="<?= $ing['id_ingrediente_padrao'] ?>" <?= ($filtros['id_ingrediente_padrao'] ?? '') == $ing['id_ingrediente_padrao'] ? 'selected' : '' ?>>
                  <?= esc($ing['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <select id="filtro_deposito" class="select select-sm w-auto min-w-[180px]">
              <option value="">Todos os Depósitos</option>
              <?php foreach ($depositos as $dep): ?>
                <option value="<?= $dep['id_deposito'] ?>" <?= ($filtros['id_deposito'] ?? '') == $dep['id_deposito'] ? 'selected' : '' ?>>
                  <?= esc($dep['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <input type="date" id="filtro_data_inicio" class="input input-sm w-auto min-w-[150px]" value="<?= esc($filtros['data_inicio'] ?? '') ?>">
            <input type="date" id="filtro_data_fim" class="input input-sm w-auto min-w-[150px]" value="<?= esc($filtros['data_fim'] ?? '') ?>">
            <button class="btn btn-sm btn-primary whitespace-nowrap" onclick="aplicarFiltros()">
              <i class="ki-filled ki-setting-4"></i>
              Filtrar
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div data-datatable="true" data-datatable-page-size="10">
          <div class="scrollable-x-auto">
            <table class="table table-auto table-border" data-datatable-table="true">
              <thead>
                <tr>
                  <th class="w-[60px] text-center">
                    <input class="checkbox checkbox-sm" data-datatable-check="true" type="checkbox">
                  </th>
                  <th class="min-w-[150px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Data</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[200px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Ingrediente</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[150px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Depósito</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[100px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Tipo</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Quantidade</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Custo Unit.</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[150px]">
                    <span class="sort-label font-normal text-gray-700">Fornecedor</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($movimentacoes)): ?>
                  <tr>
                    <td colspan="8" class="text-center py-10 text-gray-500">
                      Nenhuma movimentação encontrada.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($movimentacoes as $mov): ?>
                    <tr>
                      <td class="text-center">
                        <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="<?= $mov['id_movimentacao'] ?>">
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?>
                      </td>
                      <td>
                        <div class="flex items-center gap-2.5">
                          <div class="flex items-center justify-center size-8 rounded-full bg-primary/10 shrink-0">
                            <i class="ki-filled ki-box text-primary"></i>
                          </div>
                          <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 mb-px">
                              <?= esc($mov['produto_nome'] ?? '-') ?>
                            </span>
                            <?php if ($mov['produto_codigo']): ?>
                              <span class="text-xs text-gray-500">
                                Cód: <?= esc($mov['produto_codigo']) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= esc($mov['deposito_nome'] ?? '-') ?>
                      </td>
                      <td>
                        <?php
                        $tipoClass = '';
                        $tipoLabel = '';
                        switch($mov['tipo']) {
                          case 'ENTRADA':
                            $tipoClass = 'badge-success';
                            $tipoLabel = 'Entrada';
                            break;
                          case 'SAIDA':
                            $tipoClass = 'badge-danger';
                            $tipoLabel = 'Saída';
                            break;
                          case 'AJUSTE':
                            $tipoClass = 'badge-warning';
                            $tipoLabel = 'Ajuste';
                            break;
                          default:
                            $tipoClass = 'badge';
                            $tipoLabel = $mov['tipo'];
                        }
                        ?>
                        <span class="badge badge-sm <?= $tipoClass ?>"><?= $tipoLabel ?></span>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= number_format($mov['quantidade'] ?? 0, 3, ',', '.') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        R$ <?= number_format($mov['custo_unitario'] ?? 0, 2, ',', '.') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= esc($mov['fornecedor_nome'] ?? '-') ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
            <div class="flex items-center gap-2 order-2 md:order-1">
              Mostrar
              <select class="select select-sm w-16" data-datatable-size="true" name="perpage">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
              </select>
              por página
            </div>
            <div class="flex items-center gap-4 order-1 md:order-2">
              <span data-datatable-info="true">1-<?= count($movimentacoes) ?> de <?= count($movimentacoes) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
function aplicarFiltros() {
  const tipo = document.getElementById('filtro_tipo').value;
  const ingrediente = document.getElementById('filtro_ingrediente').value;
  const deposito = document.getElementById('filtro_deposito').value;
  const dataInicio = document.getElementById('filtro_data_inicio').value;
  const dataFim = document.getElementById('filtro_data_fim').value;
  
  const params = new URLSearchParams();
  if (tipo !== '') params.append('tipo', tipo);
  if (ingrediente !== '') params.append('id_ingrediente_padrao', ingrediente);
  if (deposito !== '') params.append('id_deposito', deposito);
  if (dataInicio) params.append('data_inicio', dataInicio);
  if (dataFim) params.append('data_fim', dataFim);
  
  window.location.href = '<?= base_url('Estoque/historico') ?>?' + params.toString();
}
</script>
</main>

