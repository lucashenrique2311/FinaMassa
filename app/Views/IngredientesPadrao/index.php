<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Ingredientes Padrão
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Ingredientes Padrão</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-primary" href="<?= base_url('IngredientesPadrao/criar') ?>">
        <i class="ki-filled ki-plus !text-base"></i>
        Novo Ingrediente Padrão
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
          Mostrando <?= count($ingredientes) ?> ingrediente(s) padrão
        </h3>
        <div class="flex flex-col lg:flex-row gap-3 w-full lg:w-auto lg:items-center">
          <!-- Campo de Busca -->
          <div class="flex flex-1 lg:flex-initial lg:min-w-[280px]">
            <label class="input input-sm w-full">
              <i class="ki-filled ki-magnifier"></i>
              <input id="busca" placeholder="Buscar ingredientes..." type="text" value="">
            </label>
          </div>
          <!-- Filtros -->
          <div class="flex flex-row gap-2.5 items-center">
            <select id="filtro_categoria" class="select select-sm w-auto min-w-[160px]">
              <option value="">Todas as Categorias</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= esc($cat) ?>" <?= ($filtros['categoria'] ?? '') == $cat ? 'selected' : '' ?>>
                  <?= esc($cat) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <select id="filtro_ativo" class="select select-sm w-auto min-w-[140px]">
              <option value="">Todos os Status</option>
              <option value="1" <?= ($filtros['ativo'] ?? '') == '1' ? 'selected' : '' ?>>Ativo</option>
              <option value="0" <?= ($filtros['ativo'] ?? '') == '0' ? 'selected' : '' ?>>Inativo</option>
            </select>
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
                      <span class="sort-label font-normal text-gray-700">Nome</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[150px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Categoria</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[100px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Unidade</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[120px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Custo Padrão</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[150px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Estoque Atual</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="min-w-[100px]">
                    <span class="sort">
                      <span class="sort-label font-normal text-gray-700">Status</span>
                      <span class="sort-icon"></span>
                    </span>
                  </th>
                  <th class="w-28 text-center">
                    <span class="sort-label font-normal text-gray-700">Ações</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($ingredientes)): ?>
                  <tr>
                    <td colspan="8" class="text-center py-10 text-gray-500">
                      Nenhum ingrediente padrão encontrado.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($ingredientes as $ing): ?>
                    <tr>
                      <td class="text-center">
                        <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="<?= $ing['id_ingrediente_padrao'] ?>">
                      </td>
                      <td>
                        <div class="flex items-center gap-2.5">
                          <div class="flex items-center justify-center size-8 rounded-full bg-primary/10 shrink-0">
                            <i class="ki-filled ki-box text-primary"></i>
                          </div>
                          <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 mb-px">
                              <?= esc($ing['nome']) ?>
                            </span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <?php if ($ing['categoria']): ?>
                          <span class="badge badge-sm"><?= esc($ing['categoria']) ?></span>
                        <?php else: ?>
                          <span class="text-gray-400">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        <?= esc($ing['unidade_medida'] ?? 'UN') ?>
                      </td>
                      <td class="text-gray-800 font-medium">
                        R$ <?= number_format($ing['custo_padrao'] ?? 0, 2, ',', '.') ?>
                      </td>
                      <td>
                        <?php if (isset($ing['estoque_total']) && $ing['estoque_total'] > 0): ?>
                          <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">
                              <?php
                              // Formata quantidade removendo zeros à direita
                              $qtd = floatval($ing['estoque_total']);
                              $qtdFormatada = rtrim(rtrim(number_format($qtd, 3, ',', '.'), '0'), ',');
                              echo $qtdFormatada . ' ' . esc($ing['unidade_medida'] ?? 'UN');
                              ?>
                            </span>
                            <?php if (isset($ing['custo_medio_total']) && $ing['custo_medio_total'] > 0): ?>
                              <span class="text-xs text-gray-500">
                                Custo médio: R$ <?= number_format($ing['custo_medio_total'], 2, ',', '.') ?>
                              </span>
                            <?php endif; ?>
                            <?php if (!empty($ing['depositos_estoque'])): ?>
                              <div class="mt-1">
                                <?php foreach ($ing['depositos_estoque'] as $dep): ?>
                                  <span class="text-xs text-gray-500 block">
                                    <?php
                                    $qtdDep = floatval($dep['quantidade']);
                                    $qtdDepFormatada = rtrim(rtrim(number_format($qtdDep, 3, ',', '.'), '0'), ',');
                                    echo esc($dep['deposito']) . ': ' . $qtdDepFormatada;
                                    ?>
                                  </span>
                                <?php endforeach; ?>
                              </div>
                            <?php endif; ?>
                          </div>
                        <?php else: ?>
                          <span class="text-gray-400 text-sm">Sem estoque</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($ing['ativo']): ?>
                          <span class="badge badge-sm badge-success">Ativo</span>
                        <?php else: ?>
                          <span class="badge badge-sm badge-danger">Inativo</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="flex justify-center gap-2">
                          <a class="btn btn-sm btn-icon btn-light" href="<?= base_url('IngredientesPadrao/editar/' . $ing['id_ingrediente_padrao']) ?>" title="Editar">
                            <i class="ki-filled ki-notepad-edit"></i>
                          </a>
                          <a class="btn btn-sm btn-icon btn-light" href="#" 
                             onclick="return confirmarExclusao('<?= base_url('IngredientesPadrao/excluir/' . $ing['id_ingrediente_padrao']) ?>', 'Excluir Ingrediente Padrão?', 'Tem certeza que deseja excluir este ingrediente padrão? Esta ação não pode ser desfeita!')" title="Excluir">
                            <i class="ki-filled ki-trash"></i>
                          </a>
                        </div>
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
              <span data-datatable-info="true">1-<?= count($ingredientes) ?> de <?= count($ingredientes) ?></span>
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
  const categoria = document.getElementById('filtro_categoria').value;
  const ativo = document.getElementById('filtro_ativo').value;
  
  const params = new URLSearchParams();
  if (categoria !== '') params.append('categoria', categoria);
  if (ativo !== '') params.append('ativo', ativo);
  
  window.location.href = '<?= base_url('IngredientesPadrao') ?>?' + params.toString();
}
</script>
</main>

