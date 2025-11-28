<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Permissões
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Permissões</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-primary" href="<?= base_url('Permissoes/criar') ?>">
        <i class="ki-filled ki-plus !text-base"></i>
        Nova Permissão
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

    <!-- Card com Permissões Agrupadas -->
    <div class="card card-grid min-w-full">
      <div class="card-header flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
        <h3 class="card-title font-medium text-sm">
          Total de <?= count($permissoes) ?> permissão(ões)
        </h3>
        <div class="flex flex-row gap-2.5 items-center">
          <select id="filtro_modulo" class="select select-sm w-auto min-w-[180px]">
            <option value="">Todos os Módulos</option>
            <?php 
            $modulos = array_unique(array_column($permissoes, 'modulo'));
            foreach ($modulos as $modulo): 
            ?>
              <option value="<?= esc($modulo) ?>" <?= ($filtros['modulo'] ?? '') == $modulo ? 'selected' : '' ?>>
                <?= esc(ucfirst($modulo)) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-sm btn-primary whitespace-nowrap" onclick="aplicarFiltros()">
            <i class="ki-filled ki-setting-4"></i>
            Filtrar
          </button>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($permissoes_agrupadas)): ?>
          <div class="text-center py-10 text-gray-500">
            Nenhuma permissão encontrada.
          </div>
        <?php else: ?>
          <div class="grid gap-4">
            <?php foreach ($permissoes_agrupadas as $modulo => $permissoesModulo): ?>
              <div class="border border-gray-200 rounded-md p-4">
                <h4 class="font-medium text-sm text-gray-900 mb-3 flex items-center gap-2">
                  <i class="ki-filled ki-folder text-primary"></i>
                  <?= esc(ucfirst($modulo)) ?>
                </h4>
                <div class="grid gap-2">
                  <?php foreach ($permissoesModulo as $permissao): ?>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-100">
                      <div class="flex flex-col gap-1">
                        <span class="font-medium text-sm text-gray-900">
                          <?= esc($permissao['nome']) ?>
                        </span>
                        <?php if ($permissao['descricao']): ?>
                          <span class="text-xs text-gray-600">
                            <?= esc($permissao['descricao']) ?>
                          </span>
                        <?php endif; ?>
                        <span class="text-xs text-gray-500">
                          <span class="font-medium">Módulo:</span> <?= esc($permissao['modulo']) ?> | 
                          <span class="font-medium">Ação:</span> <?= esc($permissao['acao']) ?>
                        </span>
                      </div>
                      <div class="flex items-center gap-2">
                        <a href="<?= base_url('Permissoes/editar/' . $permissao['id_permissao']) ?>" 
                           class="btn btn-sm btn-light" 
                           title="Editar">
                          <i class="ki-filled ki-notepad-edit"></i>
                        </a>
                        <a href="#" 
                           class="btn btn-sm btn-light-danger" 
                           title="Excluir"
                           onclick="return confirmarExclusao('<?= base_url('Permissoes/excluir/' . $permissao['id_permissao']) ?>', 'Excluir Permissão?', 'Tem certeza que deseja excluir esta permissão? Esta ação não pode ser desfeita!')">
                          <i class="ki-filled ki-trash"></i>
                        </a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
function aplicarFiltros() {
  const modulo = document.getElementById('filtro_modulo').value;
  const url = new URL(window.location.href);
  
  if (modulo) {
    url.searchParams.set('modulo', modulo);
  } else {
    url.searchParams.delete('modulo');
  }
  
  window.location.href = url.toString();
}
</script>
</main>

