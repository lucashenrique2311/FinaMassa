<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        <?= $usuario_data ? 'Editar Usuário' : 'Novo Usuário' ?>
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
        <span class="text-gray-900"><?= $usuario_data ? 'Editar' : 'Novo' ?></span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Usuarios') ?>">
        <i class="ki-filled ki-cross !text-base"></i>
        Cancelar
      </a>
    </div>
  </div>
</div>
<!-- End of Toolbar -->

<!-- Container -->
<div class="container-fixed" style="max-width: 1600px !important;">
  <div class="grid gap-5 lg:gap-7.5">
    <!-- Mensagens de Erro -->
    <?php if (session()->getFlashdata('erros')): ?>
      <?php $erros = session()->getFlashdata('erros'); ?>
      <?php if (is_array($erros)): ?>
        <div class="alert alert-danger flex flex-col gap-2 p-3 rounded-md bg-red-50 border border-red-200">
          <?php foreach ($erros as $erro): ?>
            <div class="flex items-center gap-2.5">
              <i class="ki-filled ki-information-2 text-red-500"></i>
              <span class="text-sm text-red-700"><?= esc($erro) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <?= $usuario_data ? 'Editar Usuário' : 'Novo Usuário' ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= $usuario_data ? base_url('Usuarios/atualizar/' . $usuario_data['id_usuario']) : base_url('Usuarios/salvar') ?>" method="post" id="form_usuario">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Nome -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="nome">
                Nome <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-profile-user"></i>
                <input 
                  type="text" 
                  id="nome" 
                  name="nome" 
                  placeholder="Nome completo" 
                  value="<?= old('nome', $usuario_data['nome'] ?? '') ?>" 
                  required
                />
              </label>
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="email">
                Email <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-sms"></i>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  placeholder="email@exemplo.com" 
                  value="<?= old('email', $usuario_data['email'] ?? '') ?>" 
                  required
                />
              </label>
            </div>

            <!-- Telefone -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="telefone">
                Telefone
              </label>
              <label class="input">
                <i class="ki-filled ki-phone"></i>
                <input 
                  type="text" 
                  id="telefone" 
                  name="telefone" 
                  placeholder="(00) 00000-0000" 
                  value="<?= old('telefone', $usuario_data['telefone'] ?? '') ?>"
                />
              </label>
            </div>

            <!-- Senha -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="senha">
                <?= $usuario_data ? 'Nova Senha (deixe em branco para manter)' : 'Senha' ?> 
                <?= $usuario_data ? '' : '<span class="text-red-500">*</span>' ?>
              </label>
              <label class="input" data-toggle-password="true">
                <i class="ki-filled ki-lock"></i>
                <input 
                  type="password" 
                  id="senha" 
                  name="senha" 
                  placeholder="Digite a senha" 
                  <?= $usuario_data ? '' : 'required' ?>
                />
                <button class="btn btn-icon" data-toggle-password-trigger="true" type="button">
                  <i class="ki-filled ki-eye text-gray-500 toggle-password-active:hidden"></i>
                  <i class="ki-filled ki-eye-slash text-gray-500 hidden toggle-password-active:block"></i>
                </button>
              </label>
            </div>

            <!-- Status -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="ativo">
                Status
              </label>
              <label class="switch">
                <input 
                  type="checkbox" 
                  id="ativo" 
                  name="ativo" 
                  value="1" 
                  <?= old('ativo', $usuario_data['ativo'] ?? 1) ? 'checked' : '' ?>
                />
                <span class="switch-label">
                  <span class="switch-label-active">Ativo</span>
                  <span class="switch-label-inactive">Inativo</span>
                </span>
              </label>
            </div>

            <!-- Admin -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="admin">
                Tipo de Usuário
              </label>
              <label class="switch">
                <input 
                  type="checkbox" 
                  id="admin" 
                  name="admin" 
                  value="1" 
                  <?= old('admin', $usuario_data['admin'] ?? 0) ? 'checked' : '' ?>
                />
                <span class="switch-label">
                  <span class="switch-label-active">Administrador</span>
                  <span class="switch-label-inactive">Usuário</span>
                </span>
              </label>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center gap-2.5 justify-end mt-7.5 pt-5 border-t border-gray-200">
            <a class="btn btn-light" href="<?= base_url('Usuarios') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              <?= $usuario_data ? 'Atualizar' : 'Salvar' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Máscara de telefone
document.getElementById('telefone')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length <= 10) {
    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
  } else {
    value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
  }
  e.target.value = value;
});

// Validação do formulário
document.getElementById('form_usuario')?.addEventListener('submit', function(e) {
  const nome = document.getElementById('nome').value.trim();
  const email = document.getElementById('email').value.trim();
  const senha = document.getElementById('senha').value;
  
  if (!nome) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha o nome.');
    return false;
  }
  
  if (!email) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, preencha o email.');
    return false;
  }
  
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, insira um email válido.');
    return false;
  }
  
  <?php if (!$usuario_data): ?>
  if (!senha || senha.length < 6) {
    e.preventDefault();
    SwalWarning('Atenção!', 'A senha deve ter pelo menos 6 caracteres.');
    return false;
  }
  <?php endif; ?>
});
</script>
</main>
