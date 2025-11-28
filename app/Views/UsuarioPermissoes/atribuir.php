<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Atribuir Permissões
      </h1>
      <div class="flex items-center gap-1 text-sm font-normal">
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Dashboard') ?>">
          Dashboard
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <a class="text-gray-700 hover:text-primary" href="<?= base_url('Usuarios') ?>">
          Usuários
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900">Permissões - <?= esc($usuario_data['nome']) ?></span>
      </div>
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

    <!-- Informações do Usuário -->
    <div class="card card-grid min-w-full">
      <div class="card-header">
        <h3 class="card-title font-medium text-sm">
          Usuário: <?= esc($usuario_data['nome']) ?>
        </h3>
      </div>
      <div class="card-body">
        <div class="grid gap-3 lg:grid-cols-3">
          <div>
            <span class="text-xs text-gray-600">Email:</span>
            <p class="text-sm font-medium text-gray-900"><?= esc($usuario_data['email']) ?></p>
          </div>
          <div>
            <span class="text-xs text-gray-600">Tipo:</span>
            <p class="text-sm font-medium text-gray-900">
              <?= ($usuario_data['admin'] ?? $usuario_data['ADMIN'] ?? 0) ? 'Administrador' : 'Usuário' ?>
            </p>
          </div>
          <div>
            <span class="text-xs text-gray-600">Status:</span>
            <p class="text-sm font-medium text-gray-900">
              <?= ($usuario_data['ativo'] ?? $usuario_data['ATIVO'] ?? 0) ? 'Ativo' : 'Inativo' ?>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulário de Permissões -->
    <div class="card card-grid min-w-full">
      <div class="card-header flex items-center justify-between">
        <h3 class="card-title font-medium text-sm">
          Selecione as Permissões
        </h3>
        <div class="flex items-center gap-2">
          <button type="button" class="btn btn-sm btn-light" onclick="selecionarTodas()">
            <i class="ki-filled ki-check"></i>
            Selecionar Todas
          </button>
          <button type="button" class="btn btn-sm btn-light" onclick="desmarcarTodas()">
            <i class="ki-filled ki-cross"></i>
            Desmarcar Todas
          </button>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="<?= base_url('UsuarioPermissoes/salvar') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="id_usuario" value="<?= $usuario_data['id_usuario'] ?? $usuario_data['ID_USUARIO'] ?>">

          <?php if (empty($permissoes_agrupadas)): ?>
            <div class="text-center py-10 text-gray-500">
              Nenhuma permissão cadastrada. 
              <a href="<?= base_url('Permissoes/criar') ?>" class="text-primary hover:underline">
                Cadastre permissões primeiro
              </a>
            </div>
          <?php else: ?>
            <div class="grid gap-4">
              <?php foreach ($permissoes_agrupadas as $modulo => $permissoesModulo): ?>
                <div class="border border-gray-200 rounded-md p-4">
                  <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" 
                           class="checkbox checkbox-sm" 
                           id="modulo_<?= esc($modulo) ?>"
                           onchange="toggleModulo('<?= esc($modulo) ?>', this.checked)">
                    <label for="modulo_<?= esc($modulo) ?>" class="font-medium text-sm text-gray-900 cursor-pointer">
                      <i class="ki-filled ki-folder text-primary"></i>
                      <?= esc(ucfirst(str_replace('_', ' ', $modulo))) ?>
                    </label>
                  </div>
                  <div class="grid gap-2 ml-6">
                    <?php foreach ($permissoesModulo as $permissao): ?>
                      <?php 
                      $checked = in_array($permissao['id_permissao'], $permissoes_usuario_ids ?? []);
                      ?>
                      <div class="flex items-center gap-2 p-2 bg-gray-50 rounded border border-gray-100">
                        <input type="checkbox" 
                               class="checkbox checkbox-sm" 
                               name="permissoes[]" 
                               id="permissao_<?= $permissao['id_permissao'] ?>"
                               value="<?= $permissao['id_permissao'] ?>"
                               data-modulo="<?= esc($modulo) ?>"
                               <?= $checked ? 'checked' : '' ?>>
                        <label for="permissao_<?= $permissao['id_permissao'] ?>" class="flex-1 cursor-pointer">
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
                              <span class="font-medium">Ação:</span> <?= esc(ucfirst($permissao['acao'])) ?>
                            </span>
                          </div>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
              <a href="<?= base_url('Usuarios') ?>" class="btn btn-sm btn-light">
                Cancelar
              </a>
              <button type="submit" class="btn btn-sm btn-primary">
                <i class="ki-filled ki-check"></i>
                Salvar Permissões
              </button>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
function toggleModulo(modulo, checked) {
  const checkboxes = document.querySelectorAll(`input[data-modulo="${modulo}"]`);
  checkboxes.forEach(cb => {
    cb.checked = checked;
  });
}

function selecionarTodas() {
  const checkboxes = document.querySelectorAll('input[name="permissoes[]"]');
  checkboxes.forEach(cb => {
    cb.checked = true;
  });
  // Marca também os checkboxes de módulo
  const modulos = document.querySelectorAll('input[id^="modulo_"]');
  modulos.forEach(cb => {
    cb.checked = true;
  });
}

function desmarcarTodas() {
  const checkboxes = document.querySelectorAll('input[name="permissoes[]"]');
  checkboxes.forEach(cb => {
    cb.checked = false;
  });
  // Desmarca também os checkboxes de módulo
  const modulos = document.querySelectorAll('input[id^="modulo_"]');
  modulos.forEach(cb => {
    cb.checked = false;
  });
}

// Atualiza checkbox de módulo quando todas as permissões do módulo são marcadas/desmarcadas
document.addEventListener('DOMContentLoaded', function() {
  const permissaoCheckboxes = document.querySelectorAll('input[name="permissoes[]"]');
  permissaoCheckboxes.forEach(cb => {
    cb.addEventListener('change', function() {
      const modulo = this.getAttribute('data-modulo');
      const moduloCheckbox = document.getElementById('modulo_' + modulo);
      if (moduloCheckbox) {
        const todasPermissoes = document.querySelectorAll(`input[data-modulo="${modulo}"]`);
        const todasMarcadas = Array.from(todasPermissoes).every(c => c.checked);
        moduloCheckbox.checked = todasMarcadas;
      }
    });
  });
});
</script>
</main>

