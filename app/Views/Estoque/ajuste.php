<main class="grow" role="content">
<!-- Toolbar -->
<div class="pb-5">
  <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
      <h1 class="font-medium text-lg text-gray-900">
        Ajuste de Estoque
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
        <span class="text-gray-900">Ajuste</span>
      </div>
    </div>
    <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
      <a class="btn btn-sm btn-light" href="<?= base_url('Estoque') ?>">
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
    <!-- Mensagens -->
    <?php if (session()->getFlashdata('erro')): ?>
      <div class="alert alert-danger flex items-center gap-2.5 p-3 rounded-md bg-red-50 border border-red-200">
        <i class="ki-filled ki-information-2 text-red-500"></i>
        <span class="text-sm text-red-700"><?= session()->getFlashdata('erro') ?></span>
      </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Ajuste de Estoque
        </h3>
      </div>
      <div class="card-body">
        <form action="<?= base_url('Estoque/registrarAjuste') ?>" method="post" id="form_ajuste">
          <?= csrf_field() ?>
          
          <div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">
            <!-- Ingrediente Padrão -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="id_ingrediente_padrao">
                Ingrediente <span class="text-red-500">*</span>
              </label>
              <select id="id_ingrediente_padrao" name="id_ingrediente_padrao" class="select" required>
                <option value="">Selecione um ingrediente</option>
                <?php foreach ($ingredientes as $ingrediente): ?>
                  <option value="<?= $ingrediente['id_ingrediente_padrao'] ?>">
                    <?= esc($ingrediente['nome']) ?> (<?= esc($ingrediente['unidade_medida'] ?? 'UN') ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Depósito -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="id_deposito">
                Depósito <span class="text-red-500">*</span>
              </label>
              <select id="id_deposito" name="id_deposito" class="select" required>
                <option value="">Selecione um depósito</option>
                <?php foreach ($depositos as $deposito): ?>
                  <option value="<?= $deposito['id_deposito'] ?>">
                    <?= esc($deposito['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Quantidade -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="quantidade">
                Quantidade Atual <span class="text-red-500">*</span>
              </label>
              <label class="input">
                <i class="ki-filled ki-archive"></i>
                <input 
                  type="text" 
                  id="quantidade" 
                  name="quantidade" 
                  placeholder="0,000" 
                  required
                />
              </label>
              <span class="text-xs text-gray-500 mt-1">
                Informe a quantidade correta que deve estar no estoque
              </span>
            </div>

            <!-- Observações -->
            <div class="flex flex-col gap-1">
              <label class="form-label font-normal text-gray-900" for="observacoes">
                Observações
              </label>
              <textarea 
                id="observacoes" 
                name="observacoes" 
                class="textarea" 
                rows="3"
                placeholder="Motivo do ajuste"
              ></textarea>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex items-center gap-2.5 justify-end mt-7.5 pt-5 border-t border-gray-200">
            <a class="btn btn-light" href="<?= base_url('Estoque') ?>">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-filled ki-check"></i>
              Registrar Ajuste
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Container -->

<script>
// Máscara de quantidade (decimal com 3 casas)
document.getElementById('quantidade')?.addEventListener('input', function(e) {
  let value = e.target.value.replace(/[^\d,]/g, '');
  value = value.replace(',', '.');
  if (value.split('.').length > 2) {
    value = value.substring(0, value.lastIndexOf('.'));
  }
  e.target.value = value;
});

// Validação do formulário
document.getElementById('form_ajuste')?.addEventListener('submit', function(e) {
  const ingrediente = document.getElementById('id_ingrediente_padrao').value;
  const deposito = document.getElementById('id_deposito').value;
  const quantidade = document.getElementById('quantidade').value;
  
  if (!ingrediente) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, selecione um ingrediente.');
    return false;
  }
  
  if (!deposito) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, selecione um depósito.');
    return false;
  }
  
  if (quantidade === '' || isNaN(parseFloat(quantidade.replace(',', '.')))) {
    e.preventDefault();
    SwalWarning('Atenção!', 'Por favor, informe uma quantidade válida.');
    return false;
  }
});
</script>
</main>

