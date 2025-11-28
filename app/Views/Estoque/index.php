<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Controle de Estoque
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Estoque</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-primary" href="<?= base_url('Estoque/entrada') ?>">
        <i class="ki-filled ki-plus !text-base"></i>
        Nova Entrada
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Mensagens -->
    <?php if (session()->getFlashdata('sucesso')): ?>
      <div class="alert alert-success flex items-center gap-2.5 p-3 rounded-md bg-green-50 border border-green-200">
        <i class="ki-filled ki-check-circle text-green-500"></i>
        <span class="text-sm text-green-700"><?= session()->getFlashdata('sucesso') ?></span>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erro')): ?>
      <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
        <i class="ki-filled ki-information-2 text-red-500"></i>
        <span class="text-sm text-red-700"><?= session()->getFlashdata('erro') ?></span>
      </div>
    <?php endif; ?>

    <!-- Card com DataTable -->
    <div class="card card-grid min-w-full">
      <div class="card-header flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
        <h3 class="card-title font-medium text-sm">
          Mostrando <?= count($estoque) ?> item(ns) em estoque
        </h3>
        <div class="flex flex-col lg:flex-row gap-3 w-full lg:w-auto lg:items-center">
          <!-- Filtros -->
          <div class="flex flex-row gap-2.5 items-center">
            <select id="filtro_deposito" class="select select-sm w-auto min-w-[180px]">
              <option value="">Todos os Depósitos</option>
              <?php foreach ($depositos as $dep): ?>
                <option value="<?= $dep['id_deposito'] ?>" <?= ($filtros['id_deposito'] ?? '') == $dep['id_deposito'] ? 'selected' : '' ?>>
                  <?= esc($dep['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <label class="checkbox-group">
              <input class="checkbox checkbox-sm" type="checkbox" id="filtro_estoque_baixo" <?= ($filtros['estoque_baixo'] ?? '') ? 'checked' : '' ?>>
              <span class="checkbox-label text-sm">Estoque Baixo</span>
            </label>
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
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Quantidade</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Custo Médio</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Valor Total</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($estoque)): ?>
                  <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">
                      Nenhum item em estoque encontrado.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($estoque as $item): ?>
                    <tr>
                      <td class="text-center">
                        <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="<?= $item['id_estoque'] ?>">
                      </td>
                      <td>
                        <div class="flex items-center gap-2.5">
                          <div class="flex items-center justify-center size-8 rounded-full bg-primary/10 shrink-0">
                            <i class="ki-filled ki-box text-primary"></i>
                          </div>
                          <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 mb-px">
                              <?= esc($item['produto_nome'] ?? '-') ?>
                            </span>
                            <?php if ($item['produto_codigo']): ?>
                              <span class="text-xs text-gray-500">
                                Cód: <?= esc($item['produto_codigo']) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= esc($item['deposito_nome'] ?? '-') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= number_format($item['quantidade'] ?? 0, 3, ',', '.') ?> <?= esc($item['unidade_medida'] ?? 'UN') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        R$ <?= number_format($item['custo_medio'] ?? 0, 2, ',', '.') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        R$ <?= number_format(($item['quantidade'] ?? 0) * ($item['custo_medio'] ?? 0), 2, ',', '.') ?>
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
              <span data-datatable-info="true">1-<?= count($estoque) ?> de <?= count($estoque) ?></span>
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
  const deposito = document.getElementById('filtro_deposito').value;
  const estoqueBaixo = document.getElementById('filtro_estoque_baixo').checked;
  
  const params = new URLSearchParams();
  if (deposito !== '') params.append('id_deposito', deposito);
  if (estoqueBaixo) params.append('estoque_baixo', '1');
  
  window.location.href = '<?= base_url('Estoque') ?>?' + params.toString();
}
</script>
</main>

